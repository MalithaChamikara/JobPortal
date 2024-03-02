@extends('layouts.app');

@section('content')

<div class='container mt-5'>
    <div class="d-flex justify-content-between">
        <h4>Recommended Jobs</h4>
        <div class="dropdown">
            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Salary
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{route('listing.index',['sort'=>'salary_high_to_low'])}}">High to Low</a></li>
                <li><a class="dropdown-item" href="{{route('listing.index',['sort'=>'salary_low_to_high'])}}">Low to High</a></li>

            </ul>

            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Date
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{route('listing.index',['date'=>'latest'])}}">Latest</a></li>
                <li><a class="dropdown-item" href="{{route('listing.index',['date'=>'oldest'])}}">Oldest</a></li>

            </ul>

            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Job Type
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{route('listing.index',['job_type'=>'Fulltime'])}}">Fulltime</a></li>
                <li><a class="dropdown-item" href="{{route('listing.index',['job_type'=>'Parttime'])}}">Parttime</a></li>
                <li><a class="dropdown-item" href="{{route('listing.index',['job_type'=>'Casual'])}}">Casual</a></li>
                <li><a class="dropdown-item" href="{{route('listing.index',['job_type'=>'Contract'])}}">Contract</a></li>
            </ul>
        </div>
    </div>

    <div class="row mt-2 g-1">
        @foreach($jobs as $job)
        <div class="col-md-3">

            <div class="card p-2 {{$job->job_type}}">
                <div class="text-right">
                    <small class="badge text-bg-info">{{$job->job_type}}</small>
                </div>
                <div class="text-center mt-2 p-3">
                    <img src="{{Storage::url($job->profile->profile_pic)}}" width="100" class="rounded-circle">
                    <span class="d-block font-weight-bold">{{$job->title}}</span>
                    <br><span>{{$job->profile->name}}</span>
                    <div class="d-flex flex-row  justify-content-center">
                        <small class="ml-1">{{$job->address}}</small>
                    </div>
                    <div class="d-flex  justify-content-between mt-3">
                        <span>${{number_format($job->salary,2)}}</span>
                        <a href="{{route('jobs.show',[$job->slug])}}"><button class="btn btn-sm-dark btn-outline-dark">Apply</button></a>
                    </div>
                </div>
            </div>

        </div>
        @endforeach
    </div>
</div>
<style>
    .Fulltime {
        background-color: green;
        color: #fff;
    }

    .Parttime {
        background-color: blue;
        color: #fff;
    }

    .Casual {
        background-color: red;
        color: #fff;
    }

    .Contract {
        background-color: purple;
        color: #fff;
    }
</style>

@endsection