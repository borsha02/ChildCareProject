<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = auth()->user()->role;

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'caregiver':
                return redirect()->route('caregiver.dashboard');
            case 'parent':
                return redirect()->route('parent.dashboard');
            default:
                return redirect()->route('home');
        }
    }

   public function programs()
    {
        return view('programs');
    }

   public function  activities()
    {
        return view('activities');
    }


     public function contact()
    {
        return view('contact');
    }

}
