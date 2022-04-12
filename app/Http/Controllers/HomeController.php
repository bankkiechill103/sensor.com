<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Hash;

class HomeController extends Controller
{
    public function index()
    {
      return view('welcome');
    }
}
