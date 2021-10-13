@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
<div class="row m-b-20">
        <div class="col-xs-12">
            <h3> Challenges List </h3>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12">
            <table id="coaches-table" class="table table-hover table-striped table-bordered" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>Challenge name</th>
                    <th>Coach Name</th>
                    <th>Title</th>
                    <th>Fee</th>
                    <th>Deadline</th>
                    <th>Gift</th>
                    <th>Participants</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($challenges as $challenge)
                        <tr>
                            <td>{{ $challenge->challenges_name }}</td>
                            <td>{{ $challenge->user->first_name}} {{$challenge->user->last_name}}</td>
                            <td>{{ $challenge->title }}</td>
                            <td>$ {{ $challenge->challenges_fee }}</td>
                            <td class=" text-left">{{ $challenge->deadline }}</td>
                            <td class=" text-left">{{ $challenge->gift }}</td>
                            <td><a href="{{url('challenge/participants')}}/{{$challenge->id}}">Check</a></td>
                            <td class=" text-left"><a href="{{ url('admin/challenge/'.$challenge->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                              <a href="javascript:;" onclick="copyToClipboard(this)" data-link="{{config('app.user_url').'/challenges/'.$challenge->id.'?single=true'}}" style="margin-left: 10px" ><img src="{{url('assets/img/icons8-copy-link-52.png')}}" alt="Copy Link" style="vertical-align: baseline; width:14px" /></a></td>
                           </td>
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
      function copyToClipboard(element) {
         var $temp = $("<input>");
         $("body").append($temp);
         $temp.val($(element).data('link')).select();
         document.execCommand("copy");
         $temp.remove();
         swal({
            type: 'success',
            title: 'Success!',
            html: 'Link copied to clipboard successfully! ('+$(element).data('link')+')',
         })
      }
    </script>
@endsection