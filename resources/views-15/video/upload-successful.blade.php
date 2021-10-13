@extends('layouts.uploadvideo')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')
	<section class="video-download-wrap upload-content">
         <div class="process-steps">
            <a href="{{url('video')}}" class="back-arrow"><img src="/assets/img/back-arrow.png" alt="" class="img-fluid"></a>
            <a href="{{url('video')}}" class="close-icon"><img src="/assets/img/close.png" alt="" class="img-fluid"></a>
            <ul class="process-menu">
                <li class="active"><a href="#">UPLOAD VIDEO</a></li>
                <li><a href="#">SELECT COACH</a> </li>
                <li><a href="#">PAY</a> </li>
            </ul>
        </div>

        <div class="video-content ">
            <div class="container">
                <h2>Upload Successful!</h2>
               
                <div class="youtube-link">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="video">
                                @if($result->url && $videotype =='file')
                                <video width="100%" height="auto" controls >
                                    <source src="{{url('/') . config('video.user_video_path') . $result->url}}">
                                        Your browser does not support HTML5 video.
                                </video>
                                @else
                                    <iframe width="100%" height="436" src="{{$result->url}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @endif
                            </div>
                          </div>
                        <div class="col-lg-6">
                            <div class="right-box">
                              <h4>TITLE</h4>
                                <textarea id="video-title" class="form-control" rows="4" placeholder="Type here the name of your video performance …"></textarea>
                                <input type="hidden" id="video-id" value="{{$video_id}}" />
                                <div class="video-lenth">
                                   <h4>LENGTH</h4>
                                  <div class="duration">3:25</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="coach-select text-center">
                      <a href="javascript:void(0)" id="select-coach" class="btn btn-outline-dark">Select a Coach</a>
                      <a href="{{url('upload-new-video')}}" class="go-back">Go back and upload again</a>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
        	$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
            $( "#select-coach" ).click(function() {
			   console.log('click');
			   var video_title = $("#video-title").val();
			   if(video_title !=""){
			   		$("#video-title").css('border-color','inherit');
			   		var video_id = $("#video-id").val();
			   		$.ajax({
		               	type:'POST',
		               	url:'/update-title',
		               	data:{video_title:video_title, video_id:video_id},
						success:function(data){
							console.log(data);
                            //var response = JSON.parse(data);
                            window.location.href = data.redirect;
						}
		            });
			   }else{
					$("#video-title").css('border-color','red');
			   }
			});
        });
    </script>
@endsection