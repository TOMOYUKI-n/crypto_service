<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    //不要
    public function connect()
    {
        return view('account.connect');
    }
    
    public function link()
    {
        return view('account.link');
    }
}
