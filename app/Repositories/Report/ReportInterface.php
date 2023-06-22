<?php

namespace App\Repositories\Report;

use Illuminate\Http\Request;

interface ReportInterface
{
    public function adminGetTypeReport();

    public function adminCreateTypeReport(Request $request);
    
    public function adminShowTypeReport($id);
    
    public function adminUpdateTypeReport(Request $request, $id);

    public function adminDeleteTypeReport($id);

    public function adminGetListReportedUser();

    public function adminShowDetailReportedUser($id);

    public function adminSuspendUser(Request $request, $id);

    public function adminCancelSuspendUser($id);

    public function userReportOtherUser(Request $request, $id);

    public function userGetTypeReport();
}
