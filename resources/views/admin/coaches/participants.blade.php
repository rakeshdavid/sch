@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <style>
        .modal a.close-modal{
            top: -3px;
            right: -2px;
        }
        .blocker{
            z-index: 9999;
        }
    </style>
@endsection
@section('content')
<div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Participants List </h3>
        </div>
        <div class="col-xs-12">
            <h3>Coach Name: {{$agency->first_name}} {{$agency->last_name}} </h3>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12">
            <table id="coaches-table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                
                    <thead>
                        <th>Name</th>
                        <th>Audition</th>
                        <th>Payment Status</th>
                        
                        <th>Video</th>
                        <th>Action</th>
                    </thead>

                    @foreach($participants as $participant)
                        <tr>
                            <td>{{$participant->user->first_name}}</td>
                            <td>@if($participant->challenges){{$participant->challenges->challenges_name}}@endif</td>
                            <td>
                                @if($participant->payment_status == 1 && $participant->stripe_id !='NULL')
                                    Paid
                                @else
                                    Pending
                                @endif
                            </td>
                            
                            <td>
                                <a href="#ex-video-{{$participant->id}}" rel="modal:open">See Video</a>
                                <div id="ex-video-{{$participant->id}}" class="modal">
                                    <p>Video</p>
                                    <video class="video-pause" id="challenge-video-{{$participant->id}}" width="100%" height="300" controls="">
                                        <source src="/uploads/challenge/{{$participant->video_link}}">
                                        Your browser does not support HTML5 video.
                                    </video>
                                    <a href="#" rel="modal:close">Close</a>
                                </div>
                            </td>
                            <td>
                                
                                @if($participant->review == '')
                                   <span class="alert-danger"> Waiting for Review</span>
                                @else
                                    <span class="alert-success">Reviewed</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                
            </table>
        </div>
    </div>
@endsection
@section('js')
    <script src="/assets/js/datatables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
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
     <script type="text/javascript">
    
    jQuery("body").on('click', function(){
        console.log('pausevideo');
      $('.video-pause').each(function() {
          $(this).get(0).pause();
      });
    });
</script>
@endsection