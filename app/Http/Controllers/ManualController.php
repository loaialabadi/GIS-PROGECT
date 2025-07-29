<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Placemark;


class ManualController extends Controller
{
       


    public function chooseType()
    {
        return view('manual.choose_type');
    }

    public function surveyForm()
    {
        return view('manual.survey_form');
    }

    public function utilitiesForm()
    {
        return view('manual.utilities_form');
    }

    public function trackingForm()
    {
        return view('manual.tracking.tracking_form');
    }



    

}
