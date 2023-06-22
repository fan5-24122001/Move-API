<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Report\ReportRepository;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class ReportUserController extends Controller
{
    use JsonResponseTrait;

    private ReportRepository $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->middleware('checkReportedThisUser')->only('reportUser');
    }

    public function reportUser(Request $request, $id)
    {
        return $this->reportRepository->userReportOtherUser($request, $id);
    }

    public function getTypeReports()
    {
        return $this->reportRepository->userGetTypeReport();
    }
}
