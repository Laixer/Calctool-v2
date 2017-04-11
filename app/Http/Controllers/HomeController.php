<?php

namespace CalculatieTool\Http\Controllers;

use \CalculatieTool\Models\Project;
use \Jenssegers\Agent\Agent;
use Illuminate\Http\Request;

use \Auth;

class HomeController extends Controller
{
    /**
     * Instantiate the dashboard controller.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('payzone');

        //
    }

    /**
     * Update user status to set user online.
     *
     * @return void
     */
    private function setUserOnline()
    {
        if (session()->has('swap_session'))
            return;

        Auth::user()->online_at = \DB::raw('NOW()');
        Auth::user()->save();
    }

    /**
     * Get the welcome message according to the
     * current time of day.
     *
     * @return void
     */
    private function welcomeMessage()
    {
        $time = date("H");
        if ($time >= "6" && $time < "12") {
            return "Goedemorgen";
        } else if ($time >= "12" && $time < "17") {
            return "Goedenmiddag";
        } else if ($time >= "17") {
            return "Goedenavond";
        } else if ($time >= "0") {
            return "Goedenacht";
        }

        return "Timeless";
    }

    /**
     * Show the dashboard.
     *
     * @return Response
     */
    public function __invoke()
    {
        if (Auth::user()->isSystem())
            return redirect('/admin');

        $this->setUserOnline();

        return view('base.home', [
            'agent' => new Agent(),
            'projectCount' => Project::where('user_id', Auth::user()->id)->count(),
            'welcomeMessage' => $this->welcomeMessage(),
        ]);
    }

}
