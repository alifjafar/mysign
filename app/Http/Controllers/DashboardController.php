<?php

namespace App\Http\Controllers;

use App\Requester;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard.index');
    }
}
