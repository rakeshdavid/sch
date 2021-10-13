@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
<div class="row m-b-20">
        <div class="col-xs-12">
            <h3> Agency List </h3>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12">
            <table id="coaches-table" class="table table-hover table-striped table-bordered" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Action</th>
                    <th>Audition</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($agency as $agenci)
                        <tr>
                            <td>{{ $agenci->first_name }}</td>
                            <td>{{ $agenci->last_name }}</td>
                            <td>{{ $agenci->email }}</td>
                            <td class=" text-left"><a href="{{ url('agency/'.$agenci->id.'/edit') }}"><i class="fa fa-edit"></i></a></td>
                            <td><a href="{{url('agency/auditions/')}}/{{$agenci->id}}">Check Auditions</a></td>
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