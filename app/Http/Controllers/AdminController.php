<?php


class AdminController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function dash()
    {
        parent::show();
        return View::make('admins.dash');
    }
}
