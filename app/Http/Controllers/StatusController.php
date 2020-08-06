<?php

namespace App\Http\Controllers;

class StatusController extends Controller
{
    public function index()
    {
        return response()->json('ok');
    }
}
