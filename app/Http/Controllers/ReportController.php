<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Report\ReportRepository;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private ReportRepository $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function getReportedUsers()
    {
        return $this->reportRepository->adminGetListReportedUser();
    }

    public function showDetailReportedUser($id)
    {
        return $this->reportRepository->adminShowDetailReportedUser($id);
    }

    public function suspend(Request $request, $id)
    {
        return $this->reportRepository->adminSuspendUser($request, $id);
    }

    public function cancelSuspend($id)
    {
        return $this->reportRepository->adminCancelSuspendUser($id);
    }
}
