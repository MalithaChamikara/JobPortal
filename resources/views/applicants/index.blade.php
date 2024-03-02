@extends('layouts.admin.main');

@section('content')

<div class='container mt-5'>

    <div class=" row justify-content-center ">

        <div class="card mb-4">

            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created on</th>
                            <th>Applicants count</th>
                            <th>View Job</th>
                            <th>View Applicants</th>

                        </tr>
                    </thead>

                    <tbody>
                        @foreach($listings as $list)
                        <tr>
                            <td>{{$list->title}}</td>
                            <td>{{$list->created_at}}</td>
                            <td>{{$list->users_count}}</td>
                            <td>View Job</td>
                            <td><a href="{{route('applicant.show',$list->slug)}}">View</a></td>



                        </tr>


                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection