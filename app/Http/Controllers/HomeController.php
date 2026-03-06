<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('app', [
            'props' => [
                'component' => 'MailApp',
                'user' => $request->user(),
            ]
        ]);
    }
}
