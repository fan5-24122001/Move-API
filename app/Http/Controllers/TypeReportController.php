<?php

namespace App\Http\Controllers;

use App\Repositories\Report\ReportRepository;
use Illuminate\Http\Request;

class TypeReportController extends Controller
{
    private ReportRepository $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->reportRepository->adminGetTypeReport();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('type_reports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->reportRepository->adminCreateTypeReport($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $typeReport = $this->reportRepository->adminShowTypeReport($id);
        return view('type_reports.show', compact('typeReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $typeReport = $this->reportRepository->adminShowTypeReport($id);
        return view('type_reports.edit', compact('typeReport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->reportRepository->adminUpdateTypeReport($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->reportRepository->adminDeleteTypeReport($id);
    }
}
