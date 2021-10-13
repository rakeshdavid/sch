@extends('layouts.uploadvideo')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')
	<section class="video-download-wrap upload-content">
         <div class="process-steps">
            <a href="{{url('video')}}" class="back-arrow"><img src="/platform/img/back-arrow.png" alt="" class="img-fluid"></a>
            <a href="{{url('video')}}" class="close-icon"><img src="/platform/img/close.png" alt="" class="img-fluid"></a>
            <ul class="process-menu">
                <li class="active"><a href="#">UPLOAD VIDEO</a></li>
                @if(Session::has('coachid'))
                    
                @else
                    <li><a href="#">SELECT COACH</a> </li>
                @endif
                
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
                                @if(strpos($result->url, 'youtube') != false)

                                            @php
                                            $query ="";
                                                $parts = parse_url($result->url);
                                                //print_r($parts);
                                                if(array_key_exists('query',$parts)){
                                                    parse_str($parts['query'], $query);
                                                    $videoID = $query['v'];
                                                    $youtubeurl = "https://www.youtube.com/embed/".$videoID; 
                                                }else{
                                                    $youtubeurl = $result->url; 
                                                }
                                                
                                                
                                            @endphp
                                            <iframe width="100%" height="300" src="{{$youtubeurl}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            @if($result->thumbnail !='')
                                                <img class="video-thumb" data-videoid="{{$result->id}}" src="{{$result->thumbnail}}" />
                                            @endif
                                        @else
                                       <video id="myVideo" data-videoid="{{$result->id}}" class="video-{{$result->id}}" width="100%" height="300" controls >
                                            <source src="{{url('/') . config('video.user_video_path') . $result->url}}">
                                            Your browser does not support HTML5 video.
                                        </video>
                                            @if($result->thumbnail !='')
                                                <img class="video-thumb" data-videoid="{{$result->id}}"  src="{{url('/')}}/user_videos/thumbnails/{{$result->thumbnail}}" />
                                            @endif
                                        @endif
                                
                            </div>
                          </div>
                        <div class="col-lg-6">
                            <div class="right-box">
                              <h4>Title | Description</h4>
                                <textarea id="video-title" class="form-control" rows="4" placeholder="Type here the name of your video performance …"></textarea>
                                <input type="hidden" id="video-id" value="{{$video_id}}" />
                                <div class="video-lenth">
                                    @if($result->url && $videotype =='file')
                                        <h4>LENGTH</h4>
                                        <div id="meta" class="duration">Getting...</div>
                                    @endif  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="coach-select text-center">
                        <a href="javascript:void(0)" id="select-coach" class="btn btn-outline-dark active-button">
                            @if(Session::has('coachid'))
                              @if(Session::has('package_id') == '2')
                                Questions
                              @else
                                 Payment
                              @endif
                            @else
                                Select a Coach
                            @endif
                        </a>
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
            var update_video_src= $("#myVideo").data('videoid');
            console.log(update_video_src);
            if(update_video_src){
                $.ajax({
                    type:'POST',
                    url:'/update-video-source',
                    data:{video_id:update_video_src},
                    success:function(data){
                        console.log(data);
                        console.log(data.video_src);
                        var newsrc = 'https://platform.showcasehub.com/user_videos/videos/'+data.video_src;
                        var $video = $('#myVideo'),
                        videoSrc = $('source', $video).attr('src', newsrc);
                        $video[0].load();
                        if(document.getElementById('myVideo')){
                            var myVideoPlayer = document.getElementById('myVideo'),
                            meta = document.getElementById('meta');

                            myVideoPlayer.addEventListener('loadedmetadata', function () {
                                var duration = myVideoPlayer.duration;
                                var seconds = duration.toFixed(2);
                                var m = Math.floor(seconds % 3600 / 60);
                                var s = Math.floor(seconds % 60);
                                if(s < 10){
                                    s = "0"+s;
                                }
                                meta.innerHTML = m +":"+s;
                            });
                        }
                    }
                });
            }
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
            
            $('.video-thumb').click(function(){
                var videoid = ".video-"+$(this).data('videoid');
                $(this).hide();
                $(videoid)[0].play();
            });
        });
    </script>
@endsection