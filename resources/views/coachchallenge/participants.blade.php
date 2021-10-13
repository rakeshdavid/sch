@extends('layouts.app')
@section('css')
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
	            		<th>Challenge name</th>
	            		<th>Payment Status</th>
	            		
	            		<th>Video</th>
	            		<th>Action</th>
	            	</thead>
					@if(count($participants) > 0)
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
		            			<!-- <a href="{{env('USER_PLATFORM_LINK')}}/uploads/challenge/{{$participant->video_link}}" target="_black">See Video</a> -->
		            			<a href="#ex-video-{{$participant->id}}" rel="modal:open">See Video</a>
		            			<div id="ex-video-{{$participant->id}}" class="modal">
								  	<p>Video</p>
								  	<video class="video-pause" id="challenge-video-{{$participant->id}}" width="100%" height="300" controls="">
										<source src="/uploads/challenge/{{$participant->video_link}}">
										Your browser does not support HTML5 video.
									</video>
									<a href="#" rel="modal:close" class="pause-video" data-id="ex-video-{{$participant->id}}">Close</a>
								</div>
		            		</td>
		            		<td>
		            			
		            			@if(empty($participant->review))
		            				<a href="{{url('challenge-review')}}/{{$participant->id}}">Review</a>
		            			@else
		            				<a href="{{url('challenge-review-edit')}}/{{$participant->id}}">Read Review</a>
		            			@endif
		            		</td>
		            	</tr>
	            	@endforeach
	            	@else
	            	<tr><td colspan="5">No participant found!!</td></tr>
	            	@endif
	            </table>
	        </div>
	    </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<script type="text/javascript">
	$(".close-modal,.blocker,.pause-video").on('click', function() {
		console.log('pausevideo');
	  $('.video-pause').each(function() {
	      $(this).get(0).pause();
	  });
	});
	jQuery("body").on('click', function(){
		console.log('pausevideo');
	  $('.video-pause').each(function() {
	      $(this).get(0).pause();
	  });
	});
</script>
@endsection