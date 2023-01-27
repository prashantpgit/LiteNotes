<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index(){
       $student = new Student();
       $student->name = "rahul";
       $student->email = "rahul@admin.com";
       $student->save();
    }
}
