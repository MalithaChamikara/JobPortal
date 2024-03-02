@extends('layouts.admin.main');

@section('content')

<div class='container mt-5'>

    <div class=" row  ">
        <div class="col-md-8 mt-5">
            <h1>{{$listing->title}}</h1>
            @if(Session::has('success'))
            <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
        </div>
        @foreach($listing->users as $user)
        <div class="card mt-5 {{$user->pivot->shortlister ? 'card-bg':''}}">
            <div class="row g-0">
                <div class="col-auto">
                    @if($user->profile_pic)
                    <img src="{{Storage::url($user->profile_pic)}}" alt="profile picture" class="rounded-circle" style="width: 150px;">
                    @else
                    <img src="https://placehold.co/400" alt="profile picture" class="rounded-circle" style="width: 150px;">
                    @endif
                </div>
                <div class="col-auto">
                    <div class="card-body">
                        <p class="fw-bold">{{$user->name}}</p>
                        <p class="card-text">{{$user->email}}</p>
                        <p class="card-text">{{$user->pivot->created_at}}</p>
                    </div>
                </div>
                <div class="col-auto align-self-center">
                    <form action="{{route('applicant.shortlist',[$listing->id,$user->id])}}" method="POST">@csrf
                        <a href="{{Storage::url($user->resume)}}" class="btn btn-primary" target="_blank">Download Resume</a>
                        <button type="submit" class="{{$user->pivot->shortlister ? 'btn btn-success':'btn btn-dark'}}">
                            {{$user->pivot->shortlister ? 'shortlisted':'shortlist'}}


                        </button>
                    </form>
                </div>

            </div>
        </div>

        @endforeach
    </div>
</div>
<style>
    .card-bg {
        background-color: green;
    }
</style>
@endsection