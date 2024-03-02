<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UploadResumeController extends Controller
{
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file')->store('resume', 'public');
            User::where('id', auth()->user()->id)->update([
                'resume' => $file
            ]);
            return back()->with('success', 'succcess');
        }
        return response()->json(['error' => 'error']);
    }
}
