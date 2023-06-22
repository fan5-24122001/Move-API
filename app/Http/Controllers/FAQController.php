<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFAQRequest;
use App\Repositories\FAQ\FAQRepository;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    private FAQRepository $faqResponsitory;

    public function __construct(FAQRepository $faqResponsitory)
    {
        $this->faqResponsitory = $faqResponsitory;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->faqResponsitory->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFAQRequest $request)
    {
        $this->faqResponsitory->store($request);
        return redirect()->route('faqs.index')->with('success', 'Add new FAQ success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $faq =  $this->faqResponsitory->show($id);
        return view('faqs.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $faq =  $this->faqResponsitory->show($id);
        return view('faqs.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateFAQRequest $request, string $id)
    {
        $this->faqResponsitory->update($request, $id);
        return redirect()->route('faqs.index')->with('success', 'Update FAQ success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->faqResponsitory->destroy($id);
        return redirect()->route('faqs.index')->with('success', 'Delete FAQ success');
    }
}
