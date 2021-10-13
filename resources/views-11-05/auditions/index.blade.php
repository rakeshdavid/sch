@extends('layouts.user')
@section('content')
<div class="main-content auditions-wrap">
    <div class="container-fluid">
        <div class="row">
        <div class="col-xl-6">
            <div class="coach-list">
                <h3 >List of Auditions</h3>
                <div class="top-box mb-0">
                	<form method="post" action="{{url('filter-auditions')}}">
                		{!! csrf_field() !!}
	                    <div class="row">
	                    	<div class="col-lg-6">
	                            <input type="search" name="audition-name" class="form-control" placeholder="Search by name…" value="{{$filter['name']}}">
	                        </div>
	                        <div class="col-lg-6">
	                            <div class="btn-group">
	                                <div class="btn-group">
	                                    <select name="sortby" class="form-control">
	                                    	<option value="asc" @if($filter['sortby'] == 'asc') selected="selected" @endif>Ascending</option>
	                                    	<option value="desc" @if($filter['sortby'] == 'desc') selected="selected" @endif>Descending</option>
	                                    	
	                                    </select>
	                                </div>
	                                <div class="btn-group">
	                                    <select name="talent" class="form-control">
	                                    	<option value="">Talent</option>
	                                    	@foreach($activity_genres as $talent)
	                                    		<option value="{{$talent->id}}" @if($filter['talent'] == $talent->id) selected="selected" @endif>{{$talent->name}}</option>
	                                    	@endforeach
	                                    </select>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-lg-12">
	                        	<input type="submit" name="filter" value="filter" class="btn btn-danger" />
	                        </div>
	                    </div>
                    </form>
                </div>
				@if(count($auditions) > 0)
				@foreach($auditions as $audition)
                <div class="media ">
                    <div class="date-box">
                        <div class="deadline">Deadline</div>
                        <div class="date">{{ date('j', strtotime($audition->deadline)) }}</div>
                        <div class="month">{{ date('F', strtotime($audition->deadline)) }}</div>
                    </div>
                    <div class="media-body">
                        <div class="info-box">
                            <div class="designation">{{$audition->title}}</div>
                            <h3>{{$audition->audition_name}}</h3>
                            <div class="loaction">{{$audition->location}}</div>
                        </div>
                        <div class="right-list">
                            <ul>
                                <li>
                                	@if(in_array($audition->id,$participated_audition))
                                		<a href="#" data-toggle="modal" data-target="#modal-right"><span>$ {{$audition->audition_fee}}</span>Entry Fee <div class="hover-text">See participation</div></a>
                                	@else
                                		<a href="{{url('auditions')}}/{{$audition->id}}"><span>$ {{$audition->audition_fee}}</span>Entry Fee <div class="hover-text">Read More</div></a>
                                	@endif
                                	
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <h3>No Audition Found</h3>
                @endif
                
                </div>          
            </div>

        <div class="col-xl-6">
            <div class="audition-info">
            	@if($audition_detail)
					
		                <div class="media">
		                    <div class="img-box">
		                    	@if( $audition_detail->header_image !="")
		                        	<img src="{{ asset('/uploads/auditions/') }}/{{ $audition_detail->header_image}}" alt="" class="img-fluid">
		                        @endif
		                    </div>
		                    <div class="media-body">
		                        <div class="aerial-logo">
		                        	@if( $audition_detail->logo !="")
		                        		<img src="{{ asset('/uploads/auditions/') }}/{{ $audition_detail->logo}}" alt="" class="img-fluid">
		                        	@endif
		                        </div>
		                        <div class="right-content">
		                            <div class="designation">{{ $audition_detail->title}}</div>
		                            <h3>{{ $audition_detail->audition_name}}</h3>
		                            <ul class="info-list">
		                                <li>
		                                    <h4>{{ date('j.m', strtotime( $audition_detail->deadline)) }}</h4>
		                                     <h5>Deadline</h5>
		                                </li>
		                                 <li>
		                                    <h4>{{ $audition_detail->location}}</h4>
		                                   <h5>Location</h5>
		                                </li>
		                            </ul>
		                        </div>
		                    </div>
		                </div>
		    
		                <div class="entry-info">
		                    <div class="price"><sub>$</sub> {{ $audition_detail->audition_fee}}</div>
		                    <h4>Entry Fee</h4>
		                    @if( $audition_detail->audition_detail)
			                    <div class="package-title">This package includes:</div>
								{!!  $audition_detail->audition_detail !!}
		                    @endif
		                    @if(in_array($audition_detail->id,$participated_audition))
								<a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal-right-{{$audition_detail->id}}">See participation</a>
		                    @else
		                    	<a href="{{url('auditions/participation')}}/{{ $audition_detail->id}}" class="btn btn-danger">Participate</a>
		                    @endif
		                    
		                </div>
		                   
		                <div id="accordion" class="accordion">
		                	@if( $audition_detail->description)
			                    <div class="card">
			                        <div class="card-header" id="headingOne">
			                            <a href="#" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Description </a>
			                        </div>

			                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
			                            <div class="card-body">
			                                <p>{!!  $audition_detail->description !!}</p>
			                            </div>
			                        </div>
			                    </div>
		                    @endif
		                    @if( $audition_detail->requirement)
		                    <div class="card">
		                        <div class="card-header" id="headingTwo">
		                            <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> Requirements</a>
		                        </div>
		                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
		                            <div class="card-body">
		                                <p>{!! $audition_detail->requirement!!}</p>
		                            </div>
		                        </div>
		                    </div>
		                    @endif
		                </div>
		                	
		            @endif
            </div>
        </div>
     </div>

    </div>
</div>
@endsection
@section('modal-right')
@if(count($p_audtion_data) > 0)
	@foreach($p_audtion_data as $auditiondata)
	<div class="modal fade firs-modal auditions-model right" id="modal-right-{{$auditiondata->audition_id}}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><img src="/assets/img/close.png" class="img-fluid"></a>
                <div class="firs-snow-content">
                    <div class="videos-box">
                        <iframe width="100%" height="301" src="https://www.youtube.com/embed/rd4UgCQRzJ8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <h3>Firs Snow</h3>
                    <div class="level">Level: Intermediate</div>
                    <div class="updated">Uploaded in 10.01.2020 at 9:35am</div>
                    <ul class="nav" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="technique-tab" data-toggle="tab" href="#technique" role="tab" aria-controls="technique" aria-selected="false">Score</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                            <div class="rating overal-rating">
                                <div class="left-text">4.1</div>
                                <div class="right-star"><i class="fas fa-star"></i> <i class="fas fa-star"></i><i class="fas fa-star"></i> <i class="fas fa-star"></i><span class="grey-star"><i class="fas fa-star"></i></span>
                                    <span class="overal">Overal Rating</span>
                                </div>
                            </div>
                            <div class="content-list">
                                <div class="left-box">
                                    <h4>Feedback Summary</h4>
                                    <p>Feedback text sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                </div>
                            </div>
    
                        </div>
                        <div class="tab-pane fade" id="technique" role="tabpanel" aria-labelledby="technique-tab">
                            <ul class="tab-list">
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Feedback Summary</h4>
                                            <p>Feedback text sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">4.1</div>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Footwork</h4>
                                            <p>Short comment doloremque, totam.</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">4.1</div>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Alignement</h4>
                                            <p>Long comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">4.1</div>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Balanced</h4>
                                            <p>Timing comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis.</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">4.1</div>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Focus</h4>
                                            <p>Short comment doloremque, totam.</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">4.1</div>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="content-list">
                                        <div class="left-box">
                                            <h4>Precision</h4>
                                            <p>Long comment doloremque, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.</p>
                                        </div>
                                        <div class="right-box">
                                            <div class="rating">
                                                <div class="left-text">4.1</div>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                                <span class="grey-star"><i class="fas fa-star"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection