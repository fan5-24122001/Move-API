<?php

namespace App\Repositories\FAQ;

use Illuminate\Http\Request;

interface FAQInterface
{
    public function index();

    public function store(Request $request);

    public function show($id);

    public function update(Request $request, $id);

    public function destroy($id);

    public function getFAQs();
}
