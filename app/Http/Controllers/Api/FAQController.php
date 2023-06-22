<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FAQ\FAQRepository;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    private FAQRepository $faqRespository;
     
    public function __construct(FAQRepository $faqRespository)
    {
        $this->faqRespository = $faqRespository;
    }

    public function getFAQs(){
        return $this->faqRespository->getFAQs();
    }
}
