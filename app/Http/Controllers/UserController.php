<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     const JOB_SEEKER  = 'seeker';
     const JOB_POSTER = 'employer';
     public function createSeeker()
     {
          return view('user.seeker-register');
     }

     public function createEmployer()
     {
          return view('user.employer-register');
     }

     public function storeSeeker(RegistrationFormRequest $request)
     {

          $validatedData = $request->validate([
               'name' => 'required|string|max:255',
               'email' => 'required|string|email|max:255|unique:users',
               'password' => 'required|string|min:8',
          ]);

          $user = User::create([
               'name' => request('name'),
               'email' => request('email'),
               'password' => bcrypt(request('password')),
               'user_type' => self::JOB_SEEKER

          ]);
          //  if($user){
          //     $user->sendEmailVerificationNotification();
          //     
          //  }else{
          //     return back()->withInput()->with('errorMessage','Failed to create user account');
          //  };
          return response()->json('Success');
          //  return redirect()->route('login')->with('successMessage','Your account was created');


     }

     public function storeEmployer(RegistrationFormRequest $request)
     {

          $validatedData = $request->validate([
               'name' => 'required|string|max:255',
               'email' => 'required|string|email|max:255|unique:users',
               'password' => 'required|string|min:8',
          ]);

          $user = User::create([
               'name' => request('name'),
               'email' => request('email'),
               'password' => bcrypt(request('password')),
               'user_type' => self::JOB_POSTER,
               'user_trial' => now()->addWeek()

          ]);
          // if($user){

          //     $user->sendEmailVerificationNotification();
          //     
          //  }else{
          //     return back()->withInput()->with('errorMessage','Failed to create user account');
          //  }
          //for format dates in laravel use Carbon instance
          //\Carbon\Carbon::parse($date)->format('y-m-d');
          return response()->json('Success');

          //  return redirect()->route('login')->with('successMessage','Your account was created');
     }

     public function login()
     {
          return view('user.login');
     }

     public function postLogin(Request $request)
     {
          $request->validate([
               'email' => 'required',
               'password' => 'required'
          ]);

          $credentials = $request->only('email', 'password');
          if (Auth::attempt($credentials)) {
               if (auth()->user()->user_type == 'employer') {
                    return redirect()->to('dashboard');
               } else {
                    return redirect()->to('/');
               };
          };
          return "invalid email or password";
     }

     public function logout()
     {
          //logging out the user using auth helper and user will rediect to the login page
          auth()->logout();
          return redirect()->route('login');
     }

     public function profile()
     {
          return view('profile.index');
     }

     public function seekerProfile()
     {
          return view('seeker.profile');
     }

     public function changePassword(Request $request, User $user)
     {
          $request->validate([
               'current_password' => 'required',
               'password' => 'required|min:8|confirmed'
               //with this confirmed key word laravel will automaticaly check new password and confirm password match
          ]);

          $this->$user = auth()->user();

          if (!$this->$user) {
               return redirect()->route('login')->with('error', 'Please log in to change your password');
          }
          //check with the password in database 
          if (!Hash::check($request->current_password, $this->$user->password)) {
               return back()->with('error', 'Curent password is incorrect');
          }

          $this->$user->password = Hash::make($request->password);
          $this->$user->save();
          return back()->with('success', 'Your password updated succesfully');
     }

     public function uploadResume(Request $request)
     {
          $request->validate([
               'resume' => 'required|mimes:pdf,docx,doc',
          ]);
          dd($request);
          if ($request->hasFile('resume')) {
               $resumePath = $request->file('resume')->store('resume', 'public');
               User::find(auth()->user()->id)->update(['resume' => $resumePath]);
               return back()->with('success', 'Your resume has been succesfullly updated');
          }
     }

     public function update(Request $request)
     {
          //check if the employer is uploading the profile_pic
          if ($request->hasFile('profile_pic')) {
               $imagePath = $request->file('profile_pic')->store('profile', 'public');
               User::find(auth()->user()->id)->update(['profile_pic' => $imagePath]);
          }

          //update other data without updating profile_pic
          User::find(auth()->user()->id)->update($request->except('profile_pic'));
          return back()->with('success', 'Your profile has been succesfullly updated');
     }

     public function jobsapplied()
     {
          $userJobs = User::with('listings')->where('id', auth()->user()->id)->get();

          return view('seeker.jobs-applied', compact('userJobs'));
     }
}
