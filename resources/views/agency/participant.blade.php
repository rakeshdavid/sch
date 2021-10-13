@extends('layouts.agency')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<style>
	.modal{
		height: auto !important;
	}
	.blocker{
		z-index: 9999;
	}
	.modal a.close-modal{
		top: -3px;
    	right: -2px;
	}
	td a,td a:hover{
		color: #e5113e
	}
	video{
		object-fit: fill;
	}
</style>
@endsection
@section('content')
<div class="main-content auditions-wrap">
    <div class="container-fluid">
        <div class="row">
	        <div class="col-md-12">
	            <div class="coach-list mb-4">
	                <h3 >Participants</h3>
	            </div>
	            <table class="table table-bordered table-striped">
	            	<thead>
	            		<th>Name</th>
	            		<th>Audition</th>
	            		<th>Payment Status</th>
	            		<th>Resume</th>
	            		<th>Video</th>
	            		<th>Action</th>
	            	</thead>
	            	@if(count($participants) > 0)
		            	@foreach($participants as $participant)
			            	<tr>
			            		<td>{{$participant->user->first_name}}</td>
			            		<td>@if($participant->audition){{$participant->audition->audition_name}}@endif</td>
			            		<td>
			            			@if($participant->payment_status == 1 && $participant->stripe_id !='NULL')
			            				Paid
			            			@else
			            				Pending
			            			@endif
			            		</td>
			            		<td>
			            			<!-- <a href="{{env('USER_PLATFORM_LINK')}}/uploads/auditions/{{$participant->resume}}" target="_black">Check Resume</a> -->
			            			<!-- Link to open the modal -->
									<a href="#ex-resume-{{$participant->id}}" rel="modal:open">Check Resume</a>
									<div id="ex-resume-{{$participant->id}}" class="modal">
									  	<p>Resume</p>
									  	<iframe src="{{asset('uploads/auditions')}}/{{$participant->resume}}" width="100%" height="600px"></iframe>
									  	<!-- <embed src="{{asset('uploads/auditions')}}/{{$participant->resume}}" frameborder="0" width="100%" height="600px"> -->
									  	<a href="#" rel="modal:close">Close</a>
									</div>
			            		</td>
			            		<td>
			            			<!-- <a href="{{env('USER_PLATFORM_LINK')}}/uploads/auditions/{{$participant->video_link}}" target="_black">See Video</a> -->
			            			<a href="#ex-video-{{$participant->id}}" rel="modal:open">See Video</a>
			            			<div id="ex-video-{{$participant->id}}" class="modal">
									  	<p>Video</p>
									  	<video id="audition-video-{{$participant->id}}" class="video-pause" width="100%" height="300" controls="">
											<source src="{{asset('uploads/auditions')}}/{{$participant->video_link}}">
											Your browser does not support HTML5 video.
										</video>
										<a href="#" rel="modal:close">Close</a>
									</div>
			            		</td>
			            		<td>
			            			
			            			@if(empty($participant->auditionreviewnew))
			            				<a href="{{url('audition-review')}}/{{$participant->id}}">Review</a>
			            			@else
			            				<a href="{{url('update-review')}}/{{$participant->id}}">Read Review</a>
			            			@endif
			            		</td>
			            	</tr>
		            	@endforeach
		            @else
		            <tr>
		            	<td colspan="6">No Participant found!</td>
		            </tr>
		            @endif
	            </table>
	            
	        </div>
	    </div>
    </div>
</div>



@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<script>
jQuery("body").on('click', function(){
		console.log('pausevideo');
	  $('.video-pause').each(function() {
	      $(this).get(0).pause();
	  });
	});
</script>
@endsection