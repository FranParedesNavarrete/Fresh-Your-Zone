<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function aboutUs() {
        return view('information.aboutUs');
    }

    public function termsAndConditions()
    {
        return view('information.termsAndConditions');
    }

    public function privacyPolicy() 
    {
        return view('information.privacyPolicy');
    }

    public function cookiesPolicy()
    {
        return view('information.cookiesPolicy');
    }

    public function legalNotice()
    {
        return view('information.legalNotice');
    }
}
