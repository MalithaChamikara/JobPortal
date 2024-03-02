@extends('layouts.app');

@section('content')

<div class='container mt-5'>
    <div class="row justify-content-center">
        @if(Session::has('success'))
        <div class="alert alert-success">{{Session::get('success')}}</div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger">{{Session::get('error')}}</div>
        @endif
        <h2>User Profile</h2>
        <form action="{{route('user.update.profile')}}" method="POST" enctype="multipart/form-data">@csrf
            <div class="col-md-8">
                <div class="form-group">
                    <label for="profileImage">Profile Image</label>
                    <input type="file" class="form-control" id="name" name="profile_pic">
                    @if(auth()->user()->profile_pic)
                    <img src="{{Storage::url(auth()->user()->profile_pic)}}" width="150" height="100px" class="mt-3">

                    @endif
                </div>

                <div class="form-group">
                    <label for="name"> Your Name</label>
                    <input type="text" class="form-control" value="{{auth()->user()->name}}">
                </div>

                <div class="form-group mt-5 ">
                    <button class="btn btn-success" type="submit">Update</button>
                </div>
            </div>
        </form>

    </div>


    <div class="row justify-content-center">
        <h2>Change your Password </h2>

        <form action="{{route('user.password')}}" method="POST">@csrf
            <div class="col-md-8">
                <div class="form-group">
                    <label for="cuurent_password">Current Password</label>
                    <input type="password" class="form-control" name="current_password">

                </div>

                <div class="form-group">
                    <label for="password"> New Password</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <div class="form-group">
                    <label for="confirm_password"> Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password">
                </div>

                <div class="form-group mt-5 ">
                    <button class="btn btn-success" type="submit">Change Password</button>
                </div>
            </div>
        </form>

    </div>

    <div class="row justify-content-center">
        <h2>Upload Your Resume </h2>

        <form action="{{route('upload.resume')}}" method="POST">@csrf
            <div class="col-md-8">
                <div class="form-group">
                    <label for="resume">Upload Resume</label>
                    <input type="file" class="form-control" name="resume" id="resume">

                </div>


                <div class="form-group mt-3 ">
                    <button class="btn btn-success" type="submit">Upload</button>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection