<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Middleware\isPremiumUser;
use App\Http\Requests\EditJobFormRequest;
use App\Http\Requests\JobPostFormrequest;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostJobController extends Controller
{
  protected  $listing;
  public function __construct(Listing $listing)
  {

    $this->listing = $listing;
    $this->middleware("auth");
    $this->middleware(isPremiumUser::class)->only(["create", "store"]);
  }
  public function create()
  {
    return view('job.create');
  }

  public function index(Listing $listing)
  {
    $listing = Listing::where('user_id', auth()->user()->id)->get();
    return view('job.index', compact('listing'));
  }

  public function store(JobPostFormrequest $request)
  {


    $imagePath = $request->file('feature_image')->store('images', 'public');

    $post = new Listing();
    $post->title = $request->title;
    $post->user_id = auth()->id();
    $post->description = $request->description;
    $post->feature_image = $imagePath;
    $post->application_close_date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
    $post->roles = $request->roles;
    $post->job_type = $request->job_type;
    $post->address = $request->address;
    $post->salary = $request->salary;
    $post->slug = Str::slug($request->title) . '.' . Str::uuid(); //make slug as unique with the title slug helper will convert any text to slug
    $post->save();
    return back();
  }

  public function edit(Listing $listing)
  {
    return view('job.edit', compact('listing'));
  }

  public function update($id, EditJobFormRequest $request)
  {
    //check if the employer is uploading the feature image
    if ($request->hasFile('feature_image')) {
      $featureImage = $request->file('feature_image')->store('images', 'public');
      Listing::find($id)->update(['feature_image' => $featureImage]);
    }

    //update other data without updating feature_image
    Listing::find($id)->update($request->except('feature_image'));
    return back()->with('success', 'Your job has been succesfullly updated');
  }

  public function destroy($id)
  {
    Listing::find($id)->delete();
    return back()->with('success', 'Your job has succesfully deleted');
  }
}
