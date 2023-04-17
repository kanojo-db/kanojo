<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

class PrivacyPolicyController extends Controller
{
    /**
     * Show the privacy policy for the application.
     *
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $policyFile = resource_path('markdown/privacy-policy.md');

        return Inertia::render('PrivacyPolicy', [
            'policy' => file_get_contents($policyFile),
        ]);
    }
}
