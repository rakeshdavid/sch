@extends('layouts.user')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="/platform/css/jquery.modal.min.css">
<link rel="stylesheet" href="/platform/source/jquery.fancybox.css">
@endsection
@section('content')
<div class="main-content coaches-content-wrap">
            <div class="container-fluid">
    <section class="select-coach-wrap">
        <div class="container-fluid">
            
            <div class="row">
               @if(!request()->exists('single'))
                <div class="col-xl-6">
                    <div class="coach-list">
                        <form class="form-horizontal" action="{{ url('coaches') }}" >
                		{!! csrf_field() !!}
	                        <div class="top-box">
                                <h3>Coaches List </h3>
	                            <div class="row">
	                                <div class="col-xl-6">
	                                   <input type="text" name="coach-name" class="form-control" placeholder="Search by name…" value="{{$name}}">
	                                </div>
	                                <div class="col-xl-6">
	                                    <div class="btn-group">
	                                        <div class="btn-group">
	                                            
	                                        	<select name="levels" id="levels" class="form-control">
	                                        		<option value="">Select Level</option>
	                                        		@foreach($levels as $level)
	                                        		<option value="{{$level->id}}" @if($level->id ==$levels_id) selected="selected" @endif>{{$level->name}}</option>
	                                        		@endforeach
	                                        	</select>
	                                            
	                                        </div>
	                                        <div class="btn-group">
	                                            
	                                            <select name="genres" id="genres" class="form-control">
	                                            	<option value="">Select Genres</option>
	                                        		@foreach($activity_types as $at)
	                                        		<option value="{{$at->id}}" @if($at->id ==$genres_id) selected="selected" @endif>{{$at->name}}</option>
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
                        @if(count($coaches) > 0)
                           @foreach($coaches as $coach)
                             @if($coach->vacation_start <=  Carbon\Carbon::today() && $coach->vacation_end >=  Carbon\Carbon::today()) 
                                <style>
                                    .coach-{{$coach->id}}.not_available:before{
                                        content: "NOT AVAILABLE UNTIL {{ date('m.d.Y', strtotime( $coach->vacation_end)) }}";
                                    }
                                </style> 
                             @endif
	                        <div class="media coach-{{$coach->id}} @if($coach->id == $coach_detail->id) active @endif @if($coach->vacation_start <=  Carbon\Carbon::today() && $coach->vacation_end >=  Carbon\Carbon::today()) not_available @endif">
	                            <div class="img-box">  
                                    @if($coach->avatar !='')
                                        @if(Str::startsWith($coach->avatar,'http'))
                                            <img class="img-fluid rounded" src="{{ $coach->avatar }}" alt="{{$coach->first_name}}">
                                        @else
                                            <img class="img-fluid rounded" src="@if( ! Str::startsWith($coach->avatar,'/'))/@endif{{ $coach->avatar }}" alt="{{$coach->first_name}}">
                                        @endif
                                    @else
                                        <img class="img-fluid rounded" src="/images/default_avatar_new.png">
                                    @endif
                                </div>
	                            <div class="media-body">
	                                <div class="info-box">
	                                    <div class="designation">{{$coach->title}}</div>
	                                    <h3>
                                            <a href="{{url('coaches')}}/{{$coach->id}}?page={{$page_no}}" class="coach-link">
                                                {{$coach->first_name}} {{$coach->last_name}}
                                            </a>
                                        </h3>
									@php
									$performance_level = $model->coach_performance_level($coach->id);
									
									@endphp
									<div class="experience">
										@foreach($performance_level as $a=> $pl)
		                                    {{$pl->name}}
		                                @endforeach
	                                </div>
	                                    <ul id="coach-{{$coach->id}}" class="dance-style-list">
	                                    @php
										$activity_genres = $model->coach_genres($coach->id);
	                                    @endphp
	                                    @foreach($activity_genres as $key => $ag)
		                                    <li class="dance-type"><a href="#" class="btn btn-danger">{{$ag->name}}</a></li>
                                            @if($key == 2)
                                                <li class="show"><a href="#" class="btn btn-danger view-more-dancetype" data-id="{{$coach->id}}">View More</a></li>
                                            @endif
		                                @endforeach
                                        @if(count($activity_genres) > 2)
	                                        <li class="hide"><a href="#" class="btn btn-danger view-less-dancetype hide" data-id="{{$coach->id}}">View Less</a></li>
                                        @endif
	                                    </ul>
	                                </div>
	                                <div class="right-list">
	                                    <ul>
	                                        <li><a href="javasricpt:void(0)" class="select-coach" data-coachid="{{$coach->id}}" data-coachfee="{{$coach->price_summary}}" data-packageid="1"><span>$ {{$coach->price_summary}}</span>Audition Prep Entry  <div class="hover-text"><i class="fa fa-check"></i>Select</div></a></li>
	                                        <li><a href="javasricpt:void(0)" class="select-coach" data-coachid="{{$coach->id}}" data-coachfee="{{$coach->price_detailed}}" data-packageid="2"><span>$ {{$coach->price_detailed}}</span>Competion Prep Entry  <div class="hover-text"><i class="fa fa-check"></i>Select</div></a></li>
	                                    </ul>
	                                </div>
	                            </div>
	                        </div>
                        @endforeach
                        @else
                        <h2>No Result Found</h2>
                        @endif
                        <div class="row">
                            <div class="col-md-12 justify-content-center">
                                @if(!empty($coaches))  {{ $coaches->appends(['coach-name'=>$name, 'genres'=>$genres_id, 'levels'=>$levels_id])->links() }} @endif
                            </div>
                        </div>
                    </div>
                </div>
               @endif
                <div class="@if(!request()->exists('single')) col-xl-6 @else col-xl-8 @endif">
                    <div class="coach-info-scrolleable">
                        {{-- @if(count($coaches) > 0) --}}
                        	@if($coach_detail)
                            <div class="coach-info">
                                <div class="media">
                                    @if($coach_detail->avatar !='')
                                        <!-- <div class="coach-image rounded mr-4" style="background-image:url('@if( ! Str::startsWith($coach_detail->avatar,'/'))/@endif{{ $coach_detail->avatar }}');width:180px;height:180px;background-position: center center;"></div> -->
                                       <!--  <img class="img-fluid rounded mr-4 img-width" src="@if( ! Str::startsWith($coach_detail->avatar,'/'))/@endif{{ $coach_detail->avatar }}" alt="{{$coach_detail->first_name}}"> -->
                                    @endif
                                    @if($coach_detail->avatar !='')
                                        @if(Str::startsWith($coach_detail->avatar,'http'))
                                           <img class="img-fluid rounded" src="{{ $coach_detail->avatar }}')">
                                        @else
                                            <div class="img-box"><img class="img-fluid rounded" src="@if( ! Str::startsWith($coach_detail->avatar,'/'))/@endif{{ $coach_detail->avatar }}"></div>
                                        @endif
                                    @else
                                        <div class="coach-image rounded mr-4" style="background-image:url('/images/default_avatar_new.png');width:180px;height:180px;background-position: center center;background-repeat: no-repeat;"></div>
                                        
                                    @endif
                                    <div class="media-body">
                                            <ul class="social-icon">
                                                @if($coach_detail->facebook_link !="")
                                                    <li><a href="{{$coach_detail->facebook_link}}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                                @endif
                                                @if($coach_detail->instagram_link !="")
                                                    <li><a href="{{$coach_detail->instagram_link}}" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                                @endif
                                            </ul>
                                            <div class="designation">{{$coach_detail->title}}</div>
                                            <h3>{{$coach_detail->first_name}} {{$coach_detail->last_name}}</h3>
                                            @php
        									$performance_level = $model->coach_performance_level($coach_detail->id);
        									
        									@endphp
        									<div class="experience">
        										@foreach($performance_level as $a=> $pl)
        		                                    {{$pl->name}}
        		                                @endforeach
        	                                </div>
                                            <ul class="dance-style-list">
                                                @php
        										$activity_genres = $model->coach_genres($coach_detail->id);
        	                                    @endphp
        	                                    @foreach($activity_genres as $b=> $ag)
        		                                    
        		                                    <li><a href="#" class="btn btn-danger">{{$ag->name}}</a></li>
        		                                @endforeach
                                            </ul>
                                    </div>
                                </div>
                                <div class="discription">
                                    <div class="lesscotent"> 
                                        {!! Str::words($coach_detail->about,20,'') !!}
                                        @if($coach_detail->about)
                                            <a href="#coach-detail-{{$coach_detail->id}}" rel="modal:open" class="read-more-1">...Read More</a>
                                        @endif
                                    </div>
                                    <div id="coach-detail-{{$coach_detail->id}}" class="modal scrollbar">
                                      <p>{!! Str::words($coach_detail->about,-1,'') !!}</p>
                                      <a href="#" rel="modal:close">Close</a>
                                    </div>
                                    <!-- <div class="morecontent">
                                        {!! Str::words($coach_detail->about,-1,'') !!}<span style="display: inline;"><a href="javascript:void(0)" class="read-less">..Read Less</a></span>
                                    </div> -->
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 pr-0">
                                        <div class="entry-info equal-height">
                                            <a href="javasricpt:void(0)" class="select-coach" data-coachid="{{$coach_detail->id}}" data-coachfee="{{$coach_detail->price_summary}}" data-packageid="1">
                                                <div class="price"><sub>$</sub> {{$coach_detail->price_summary}}</div>
                                                <h4>Audition Prep Entry</h4>
                                                <div class="package-title">This package includes:</div>
                                                    <ul>
                                                        <li>Verbal & written feedback</li>
                                                        <li>Scorecard & comments</li>
                                                        <li>Performance level placement </li>
                                                    </ul>
                                                
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pr-0">
                                        <a href="javasricpt:void(0)" class="select-coach" data-coachid="{{$coach_detail->id}}" data-coachfee="{{$coach_detail->price_detailed}}" data-packageid="2">
                                            <div class="entry-info equal-height">
                                                <div class="price"><sub>$</sub> {{$coach_detail->price_detailed}}</div>
                                                <h4>Competition Prep Entry</h4>
                                                <div class="package-title">This package includes:</div>
                                                    <ul>
                                                        <li>Verbal & written feedback</li>
                                                        <li>Scorecard & comments</li>
                                                        <li> 3 Specific questions asked by dancer</li>
                                                        <li>Performance level placement </li>
                                                    </ul>
                                                
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                            @if($coach_detail)
                            <div class="coach-scrolleable coach-info">
                                <div id="accordion" class="accordion">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <a href="#" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Certifications </a>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                            <div class="card-body">
                                                {{$coach_detail->certifications}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> Teaching Positions </a>
                                        </div>
                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                            <div class="card-body">
                                                <p>{{$coach_detail->teaching_positions}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($coach_detail->performance_credits)
                                    <div class="card">
                                        <div class="card-header" id="headingThree">
                                            <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> Performance Credits </a>

                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                            <div class="card-body">
                                                <p>{{$coach_detail->performance_credits}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @if(count($coach_detail->gallery) > 0)
                                    <div class="gallery">
                                        <h3>Gallery</h3>
                                        <ul class="gallery-list">
                                            @foreach($coach_detail->gallery as $gallery)
                                                @if($gallery->type == 'image')
                                                    <li><a class="fancybox-buttons" href="{{asset('gallery')}}/{{$gallery->path}}" data-fancybox-group="button"><img src="{{asset('gallery')}}/{{$gallery->path}}" alt="" class="img-fluid img-fluid rounded"></a></li>
                                                @else
                                                    <li>
                                                        <iframe allowfullscreen="allowfullscreen" mozallowfullscreen="mozallowfullscreen" msallowfullscreen="msallowfullscreen" oallowfullscreen="oallowfullscreen" webkitallowfullscreen="webkitallowfullscreen"
                                                    style="max-width: 160px;" src="https://www.youtube.com/embed/{{ $gallery->path }}">
                                                    </iframe>
                                                    </li>
                                                @endif
                                            
                                            @endforeach
                                        </ul>

                                    </div>
                                @endif
                            </div>
                            @endif    	                
        					@endif
                        {{-- @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
        </div>
    </section>
    @include('competition-form')
    @include('audition-form')
@endsection
@section('js')
<script src="/platform/js/jquery.modal.min.js"></script>
<script src="/platform/source/jquery.fancybox.pack.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.fancybox-buttons').fancybox({
            openEffect  : 'none',
            closeEffect : 'none',

            prevEffect : 'none',
            nextEffect : 'none',

            closeBtn  : true,

            helpers : {
                title : {
                    type : 'inside'
                },
                buttons : {}
            },

            afterLoad : function() {
                this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
            }
        });
    });
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
           var package_id =  $(this).data('packageid');
           console.log(coachid+'---'+coachfee);
           if(coachid !="" && coachfee !=""){
                $('.select-coach').removeClass('selected-coach');
                $(this).addClass('selected-coach');
                
                $.ajax({
                    type:'POST',
                    url:'/coachdata-in-session',
                    data:{coachid:coachid, coachfee:coachfee,package_id:package_id},
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
    $(document).ready(function(){
        $('.read-more').click(function(e){
            e.preventDefault();
            $('.lesscotent').hide();
            $('.morecontent').show();

        });
        $('.read-less').click(function(e){
            e.preventDefault();
            $('.lesscotent').show();
            $('.morecontent').hide();

        });
        $('.view-more-dancetype').click(function(e){
            e.preventDefault();
            console.log($(this).data('id'));
            
            var divid = "#coach-"+$(this).data('id');
            $(divid + ' li').addClass('show');
            $(this).parent().removeClass('show');
        });
        $('.view-less-dancetype').click(function(e){
            e.preventDefault();
            console.log($(this).data('id'));
            $(this).parent().removeClass('show');
            var divid = "#coach-"+$(this).data('id');
            $(divid + ' li').removeClass('show');
            $(divid + ' .view-more-dancetype').parent().addClass('show');
            $(divid +' .view-less-dancetype').parent().removeClass('show');
        });
    });
    
</script>
@endsection