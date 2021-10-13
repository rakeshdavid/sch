@extends('layouts.uploadvideo')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')
	<div class="process-steps">
        <a href="#" class="back-arrow"><img src="/assets/img/back-arrow.png" alt="" class="img-fluid"></a>
        <a href="#" class="close-icon"><img src="/assets/img/close.png" alt="" class="img-fluid"></a>
        <ul class="process-menu">
            <li class="active"><a href="#">UPLOAD VIDEO</a></li>
            <li class="active"><a href="#">SELECT COACH</a> </li>
            <li><a href="#">PAY</a> </li>
        </ul>
    </div>

    <section class="select-coach-wrap">
        <div class="container-fluid">
            <h2>Select a Coach</h2>
            <div class="row">
                <div class="col-xl-7 col-lg-6">
                    <div class="coach-list">
                        <h3>Coaches List </h3>
                        <input type="hidden" id="video-id" value="{{$video_id}}" />
                        <form class="form-horizontal" action="{{ url('select-coache') }}/{{$video_id}}" method="post">
                		{!! csrf_field() !!}
	                        <div class="top-box">
	                            <div class="row">
	                                <div class="col-xl-6">
	                                    <input type="search" name="" class="form-control" placeholder="Search by name…">
	                                </div>
	                                <div class="col-xl-6">
	                                    <div class="btn-group">
	                                        <div class="btn-group">
	                                            
	                                        	<select name="levels" id="levels" class="form-control">
	                                        		<option>Select Level</option>
	                                        		@foreach($levels as $level)
	                                        		<option value="{{$level->id}}">{{$level->name}}</option>
	                                        		@endforeach
	                                        	</select>
	                                            
	                                        </div>
	                                        <div class="btn-group">
	                                            
	                                            <select name="genres" id="genres" class="form-control">
	                                            	<option>Select Genres</option>
	                                        		@foreach($activity_types as $at)
	                                        		<option value="{{$at->id}}">{{$at->name}}</option>
	                                        		@endforeach
	                                        	</select>
	                                        </div>
	                                    </div>
	                                </div>
	                                <div class="col-xl-6">
	                                	<input type="submit" class="btn btn-danger" name="search" value="Filter" />
	                                </div>
	                            </div>
	                        </div>
                    	</form>
						@foreach($coaches as $key =>$coach)
	                        <div class="media ">
	                            <div class="img-box">  <img class="img-fluid rounded" src="/{{ $coach->avatar }}" alt="michelle-barber"></div>
	                            <div class="media-body">
	                                <div class="info-box">
	                                    <div class="designation">{{$coach->title}}</div>
	                                    <h3>{{$coach->first_name}} {{$coach->last_name}}</h3>
									@php
									$performance_level = $model->coach_performance_level($coach->id);
									
									@endphp
									<div class="experience">
										@foreach($performance_level as $a=> $pl)
		                                    {{$pl->name}}
		                                @endforeach
	                                </div>
	                                    <ul class="dance-style-list">
	                                    @php
										$activity_genres = $model->coach_genres($coach->id);
	                                    @endphp
	                                    @foreach($activity_genres as $b=> $ag)
		                                    
		                                    <li><a href="#" class="btn btn-danger">{{$ag->name}}</a></li>
		                                @endforeach
	                                        
	                                    </ul>
	                                </div>
	                                <div class="right-list">
	                                    <ul>
	                                        <li><a href="javasricpt:void(0)" class="select-coach" data-coachid="{{$coach->id}}" data-coachfee="{{$coach->price_summary}}"><span>$ {{$coach->price_summary}}</span>Audition Prep Entry  <div class="hover-text"><i class="fa fa-check"></i>Select</div></a></li>
	                                        <li><a href="javasricpt:void(0)" class="select-coach" data-coachid="{{$coach->id}}" data-coachfee="{{$coach->price_detailed}}"><span>$ {{$coach->price_detailed}}</span>Competion Prep Entry  <div class="hover-text"><i class="fa fa-check"></i>Select</div></a></li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-xl-5 col-lg-6">
                	@foreach($coaches as $key =>$coach)
                    <div class="coach-info">
                        <div class="media">
                              <img class="img-fluid rounded mr-4" src="/{{ $coach->avatar }}" alt="michelle-barber">
                            <div class="media-body">
                                    <ul class="social-icon">
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    </ul>
                                    <div class="designation">{{$coach->title}}</div>
                                    <h3>{{$coach->first_name}} {{$coach->last_name}}</h3>
                                    @php
									$performance_level = $model->coach_performance_level($coach->id);
									
									@endphp
									<div class="experience">
										@foreach($performance_level as $a=> $pl)
		                                    {{$pl->name}}
		                                @endforeach
	                                </div>
                                    <ul class="dance-style-list">
                                        @php
										$activity_genres = $model->coach_genres($coach->id);
	                                    @endphp
	                                    @foreach($activity_genres as $b=> $ag)
		                                    
		                                    <li><a href="#" class="btn btn-danger">{{$ag->name}}</a></li>
		                                @endforeach
                                    </ul>
                            </div>
                        </div>
                        <div class="discription">{!! $coach->about !!}</div>
                        <div class="row">
                            <div class="col-lg-6 pr-0">
                                <div class="entry-info">
                                    <div class="price"><sub>$</sub> {{$coach->price_summary}}</div>
                                    <h4>Audition Prep Entry</h4>
                                    <div class="package-title">This package includes:</div>
                                    <ul>
                                        <li>Verbal & written feedback</li>
                                        <li>Scorecard & comments</li>
                                        <li>Performance level placement </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6 pr-0">
                                <div class="entry-info">
                                    <div class="price"><sub>$</sub> {{$coach->price_detailed}}</div>
                                    <h4>Competition Prep Entry</h4>
                                    <div class="package-title">This package includes:</div>
                                    <ul>
                                        <li>Verbal & written feedback</li>
                                        <li>Scorecard & comments</li>
                                        <li> 3 Specific questions asked by dancer</li>
                                        <li>Performance level placement </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="accordion" class="accordion">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <a href="#" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Certifications </a>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                        {{$coach->certifications}}
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> Teaching Positions </a>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div class="card-body">
                                        <p>{{$coach->teaching_positions}}</p>
                                    </div>
                                </div>
                            </div>
                            @if($coach->performance_credits)
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> Performance Credits </a>

                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                    <div class="card-body">
                                        <p>{{$coach->performance_credits}}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <!-- <div class="gallery">
                            <h3>Gallery</h3>
                            <ul class="gallery-list">
                                <li><a href="#"><img src="img/michelle-barber.jpg" alt="" class="img-fluid img-fluid rounded"></a></li>
                                <li><a href="#"><img src="img/michelle-barber.jpg" alt="" class="img-fluid img-fluid rounded"></a></li>
                                <li><a href="#"><img src="img/michelle-barber.jpg" alt="" class="img-fluid img-fluid rounded"></a></li>
                            </ul>

                        </div> -->

                    </div>
	                    
					    @break
					    
                    @endforeach
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
            $( ".select-coach" ).click(function(e) {
			   console.log('click');
			   e.preventDefault();
			   var coachid = $(this).data('coachid');
			   var coachfee = $(this).data('coachfee');
			   console.log(coachid+'---'+coachfee);
			   if(coachid !="" && coachfee !=""){
			   		$('.select-coach').removeClass('selected-coach');
			   		$(this).addClass('selected-coach');
			   		var video_id = $("#video-id").val();
			   		console.log(video_id);
			   		$.ajax({
		               	type:'POST',
		               	url:'/update-coachof-video',
		               	data:{coachid:coachid, video_id:video_id,coachfee:coachfee},
						success:function(data){
							console.log(data);
							if(data.status == 200){
								window.location.replace(data.redirect);
							}
						}
		            });
			   }else{
					$("#video-title").css('border-color','red');
			   }
			});
        });
    </script>
@endsection