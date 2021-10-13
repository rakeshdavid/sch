@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
<div class="row m-b-20">
        <div class="col-xs-12">
            <h3> Audition List </h3>
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
                    <th>Action</th>
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
                            <td class=" text-left"><a href="{{ url('audition/'.$audition->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                              <a href="javascript:;" onclick="copyToClipboard(this)" data-link="{{config('app.user_url').'/auditions/'.$audition->id.'?single=true'}}" style="margin-left: 10px" ><img src="{{url('assets/img/icons8-copy-link-52.png')}}" alt="Copy Link" style="width: 14px; vertical-align:baseline" /></a></td>
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