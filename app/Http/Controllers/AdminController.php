<?php

namespace App\Http\Controllers;



class AdminController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function dash()
    {
        parent::show();
        return view('admins.dash');
    }
}
