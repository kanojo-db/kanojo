<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class AgeGateController extends Controller
{
    public function index(Request $request)
    {
        $ageCookie = $request->cookie('age_verified');

        if ($ageCookie) {
            return redirect('/');
        }

        return Inertia::render('Age');
    }

    public function store(Request $request)
    {
        Cookie::queue('age_verified', true, /* 1 year */ 525600);

        return redirect()->intended('/');
    }
}
