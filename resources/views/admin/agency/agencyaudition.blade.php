@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
<div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Agency's Audition List </h3>
        </div>
        <div class="col-xs-12">
            <h3>Agency: {{$agency->first_name}} </h3>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12">
            <table id="coaches-table" class="table table-hover table-striped table-bordered" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>Audition name</th>
                    <th>Title</th>
                    <th>Fee</th>
                    <th>Deadline</th>
                    <th>Location</th>
                    <th>Participants</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($auditions as $audition)
                        <tr>
                            <td>{{ $audition->audition_name }}</td>
                            <td>{{ $audition->title }}</td>
                            <td>$ {{ $audition->audition_fee }}</td>
                            <td class=" text-left">{{ $audition->deadline }}</td>
                            <td class=" text-left">{{ $audition->location }}</td>
                            <td><a href="{{url('audition/participants')}}/{{$audition->id}}">Check</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('js')
    <script src="/assets/js/datatables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.js"></script>
    <script>
    	$(document).ready(function () {
    		 $('#coaches-table').DataTable({
                "serverSide": false,
                "showInfo": true,
                "orderable": false,
                "bSort" : false
            });
    	});
    </script>
@endsection