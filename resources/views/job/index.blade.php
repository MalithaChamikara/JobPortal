@extends('layouts.admin.main');

@section('content')

<div class='container mt-5'>

    <div class=" row justify-content-center ">
        <h1>All Jobs</h1>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Your jobs
                @if(Session::has('success'))
                <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created on</th>
                            <th>Edit</th>
                            <th>Delete</th>

                        </tr>
                    </thead>

                    <tbody>
                        @foreach($listing as $list)
                        <tr>
                            <td>{{$list->title}}</td>
                            <td>{{$list->created_at}}</td>
                            <td><a href="{{route('job.edit',[$list->id])}}">Edit</a></td>
                            <td><a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal{{$list->id}}">Delete</a></td>

                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal{{$list->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <form action="{{route('job.delete',[$list->id])}}" method="POST">@csrf
                                @method('DELETE')


                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure want to delete this Job?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection