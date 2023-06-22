<?php 

namespace App\Repositories\Report;

use App\Jobs\CancelSuspendJob;
use App\Mail\AdminSuspendedUserNotification;
use App\Mail\CancelSuspensionNotification;
use App\Mail\ReportNotification;
use App\Mail\SuspendedNotification;
use App\Models\Report;
use App\Models\TypeReport;
use App\Models\User;
use App\Traits\JsonResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReportRepository implements ReportInterface
{
    use JsonResponseTrait;

    private Report $report;
    private TypeReport $typeReport;
    private User $user;

    public function __construct(Report $report, TypeReport $typeReport, User $user)
    {
        $this->report = $report;
        $this->typeReport = $typeReport;
        $this->user = $user;
    }

    public function adminGetTypeReport()
    {
        $typeReports = $this->typeReport->simplePaginate(10);
        return view('type_reports.index', compact('typeReports'));
    }

    public function adminCreateTypeReport(Request $request)
    {
        $this->typeReport->create([
            'name' => $request->name,
        ]);
        return redirect()->route('type-reports.index')->with('success', 'Create new Reason successfull');
    }

    public function adminUpdateTypeReport(Request $request, $id)
    {
        $typeReport = $this->typeReport->findOrFail($id);

        $typeReport->update([
            'name' => $request->name,
        ]);

        return redirect()->route('type-reports.index')->with('success', 'Update the Reason successfull');
    }

    public function adminShowTypeReport($id)
    {
        return $this->typeReport->findOrFail($id);
    }

    public function adminDeleteTypeReport($id)
    {
        $this->typeReport->destroy($id);
        return redirect()->route('type-reports.index')->with('success', 'Delete the reason successfull');
    }

    public function adminGetListReportedUser()
    {
        $reports = $this->user->select(
            'users.id',
            'users.username',
            'users.email',
            DB::raw('COUNT(reports.id) as times_report'),
            DB::raw('MAX(reports.created_at) as reported_date'),
        )->join('reports', 'users.id', '=', 'reports.reported_user_id')
        ->groupBy('users.id', 'users.username', 'users.email')
        ->orderBy('reported_date', 'asc')
        ->paginate(10);
        
        return view('reports.index', compact('reports'));
    }

    public function adminShowDetailReportedUser($id)
    {
        $reportedUsers = $this->report->where('reported_user_id', $id)->paginate(5);
        $user = $this->user->findOrFail($id);

        return view('reports.show', compact('reportedUsers', 'user'));
    }

    public function adminSuspendUser(Request $request, $id)
    {
        $duration = $request->input('duration');
        $suspendedUntil = now()->addDays($duration);

        $user = User::findOrFail($id);
        $user->update([
            'is_suspended' => 1,
            'suspended_until' => $suspendedUntil,
        ]);

        DB::table('personal_access_tokens')->where('tokenable_id', $id)->delete();

        dispatch(new CancelSuspendJob($user))->delay(Carbon::now()->addDays($duration));

        Mail::to($user->email)->send(new AdminSuspendedUserNotification($user, $duration));

        return redirect()->back()->with('success', 'Suspend this user successfully');
    }

    public function adminCancelSuspendUser($id)
    {
        $user = $this->user->findOrFail($id);

        if($user->is_suspended == 1){
            $user->update([
                $user->is_suspended = 0,
                $user->suspended_until = null,
            ]);

            $reportedUser = User::findOrFail($id);

            Mail::to($reportedUser->email)->send(new CancelSuspensionNotification($reportedUser));
        }

        return redirect()->back()->with('success', 'Cancel suspend this user successfully');
    }

    public function userReportOtherUser(Request $request, $id)
    {
        $typeReport = TypeReport::find($request->type_report_id);
        $reportedUser = User::find($id);
        $reporter = User::find(auth()->user()->id);
        
        if(!$typeReport || !$reportedUser || !$reporter)
        {
            return new JsonResponse([
                'success' => false,
                'massage' => 'Not found User or reason report',
                'status_code' => 404,
            ], 404);
        }
        
        $report =  $this->report->create([
            'user_id' => auth()->user()->id,
            'reported_user_id' => $id,
            'type_report_id' => $request->type_report_id,
        ]);

        Mail::to($reportedUser->email)->send(new ReportNotification($reportedUser, $reporter, $typeReport));

        $reportCount = Report::where('reported_user_id', $id)->count();

        if ($reportCount >= 3) {
            $reportedUser->update([
                'is_suspended' => 1,
                'suspended_until' => now()->addDays(30),
            ]);

            $suspensionDays = 30;
            $user = User::find($id);

            DB::table('personal_access_tokens')->where('tokenable_id', $id)->delete();

            dispatch(new CancelSuspendJob($user))->delay(Carbon::now()->addDays($suspensionDays));

            Mail::to($reportedUser->email)->send(new SuspendedNotification($reportedUser, $suspensionDays));
        }

        return new JsonResponse([
            'success' => true,
            'massage' => 'Report this user successfully',
            'status_code' => 200,
        ], 200);
    }

    public function userGetTypeReport()
    {
        $typeReports = $this->typeReport->all();

        return $this->result($typeReports, 200, true);
    }

}
