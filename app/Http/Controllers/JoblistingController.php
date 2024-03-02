<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\User;

class JoblistingController extends Controller
{
    public function index(Request $request)
    {
        //get query parametrs from the request
        $salary = $request->query("sort");
        $date = $request->query("date");
        $jobType = $request->query("job_type");

        //get all the listings
        $listings  = Listing::query();

        //filter by salary
        if ($salary === 'salary_high_to_low') {
            $listings->orderByRaw('CAST(salary AS UNSIGNED) DESC');
        } else if ($salary === 'salary_low_to_high') {
            $listings->orderByRaw('CAST(salary AS UNSIGNED) ASC');
        }

        //filter by date 
        if ($date === 'latest') {
            $listings->orderBy('created_at', 'desc');
        } else if ($date === 'oldest') {
            $listings->orderBy('created_at', 'asc');
        }

        //filter by job type

        if ($jobType === 'Fulltime') {
            $listings->where('job_type', 'Fulltime');
        } else if ($jobType === 'Parttime') {
            $listings->where('job_type', 'Parttime');
        } elseif ($jobType === 'Casual') {
            $listings->where('job_type', 'Casual');
        } elseif ($jobType === 'Contract') {
            $listings->where('job_type', 'Contract');
        }


        //eager loading users with listing user relationship function profile
        $jobs =  $listings->with('profile')->get();
        return view("home", compact("jobs"));
    }
    public function show(Listing $listing)
    {
        return view("show", compact("listing"));
    }

    public function company($id)
    {
        $company =  User::with('jobs')->where('id', $id)->where('user_type', 'employer')->first();
        return view('company', compact('company'));
    }
}
