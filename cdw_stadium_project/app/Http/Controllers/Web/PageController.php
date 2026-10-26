<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function about()
    {
        return view('web.pages.about');
    }

    public function contact()
    {
        return view('web.pages.contact');
    }

    public function privacy()
    {
        return view('web.pages.privacy');
    }
}
