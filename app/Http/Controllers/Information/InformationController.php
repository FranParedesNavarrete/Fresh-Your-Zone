<?php

namespace App\Http\Controllers\Information;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    // Función para la vista de Sobre Nosotros
    public function aboutUs() {
        return view('information.aboutUs');
    }

    // Función para la vista de Terminos y Condiciones
    public function termsAndConditions()
    {
        return view('information.termsAndConditions');
    }

    // Función para la vista de Politica de Privacidad
    public function privacyPolicy() 
    {
        return view('information.privacyPolicy');
    }

    // Función para la vista de Politica de Cookies
    public function cookiesPolicy()
    {
        return view('information.cookiesPolicy');
    }

    // Función para la vista de Aviso Legal
    public function legalNotice()
    {
        return view('information.legalNotice');
    }
}
