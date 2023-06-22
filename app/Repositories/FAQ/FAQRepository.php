<?php

namespace App\Repositories\FAQ;

use App\Models\FAQ;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class FAQRepository implements FAQInterface
{
    use JsonResponseTrait;

    private FAQ $faq;

    public function __construct(FAQ $faq)
    {
        $this->faq = $faq;
    }

    public function index()
    {
        $faqs = $this->faq->all();
        return view('faqs.index', compact('faqs'));
    }

    public function store(Request $request)
    {
       return $this->faq->create([
          'title' => $request->title,
          'content' => $request->content,  
       ]);
    }

    public function show($id)
    {
        return $this->faq->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $faq = $this->faq->find($id);
        if ($faq) {
            $faq->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
            return $faq;
        }
    }

    public function destroy($id)
    {
        return $this->faq->destroy($id);
    }

    public function getFAQs(){
        $faqs = $this->faq->all();
        return $this->result($faqs, 200, true);
    }
}
