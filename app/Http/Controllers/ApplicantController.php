<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ShortlistMail;
use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApplicantController extends Controller
{
    public function index()
    {
        //get all the listings created by the employeer with all applied users details
        //eager loading
        $listings = Listing::latest()->withCount('users')->where('id', auth()->user()->id)->get();
        // $records = DB::table('listing_user')->whereIn('listing_id', $listings->pluck('id'))->get();
        // dd($records);

        return view('applicants.index', compact('listings'));
    }


    //Route Model binding
    public function show(Listing $listing)
    {
        //use policy with authorize() method
        $this->authorize('view', $listing);
        $listing =  Listing::with('users')->where('slug', $listing->slug)->first();

        return view('applicants.show', compact('listing'));
    }

    public function shortlist($listingId, $userId)
    {
        $listing = Listing::find($listingId);
        $user = User::find($userId);

        if ($listing) {
            //if the listing exist update the shortlister field
            $listing->users()->updateExistingPivot($userId, ['shortlister' => true]);
            Mail::to($user->email)->queue(new ShortlistMail($user->name, $listing->title));
            return back()->with('success', 'User is shortlisted');
        }

        return back();
    }

    public function apply($listingId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in');
        }
        //get the current user
        $user = auth()->user();
        //ensure that the user is not null
        if (!$user) {
            return back()->with('error', 'user not found');
        }
        //update listing_user pivot table 
        $user->listings()->syncWithoutDetaching($listingId);
        return back()->with('success', 'you application was succesfully submitted');
    }
}
