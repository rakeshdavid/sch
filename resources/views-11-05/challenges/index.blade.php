@extends('layouts.user')
@section('content')
	<div class="main-content challenges-wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6">
                    <div class="coach-list">
                        <h3 class="page-title">List of Challenges</h3>
                        <div class="top-box mb-0">
                        	<form method="post" action="{{url('challenges')}}">
                            <div class="row">
                            	
                					{!! csrf_field() !!}
	                                <div class="col-lg-12">
	                                    <input type="search" name="challenge-name" class="form-control" placeholder="Search by name…" value="{{$filter['name']}}">
	                                </div>
	                              
                            </div>
                        </form>
                        </div>
						@if (count($challenges) > 0)
						@foreach($challenges as $challenge)
                        <div class="media">
                            <div class="left-box">
                            	@if($challenge->gift)
                                <span class="challenge-title">{{$challenge->gift}}</span>
                                <hr>
                                @endif
                                @if($challenge->additional_gift)
                                
                                <span class="challenge-title">{{$challenge->additional_gift}}</span>
                                @endif
                            </div>
                            <div class="media-body">
                                <div class="info-box">
                                    <h3>{{$challenge->challenges_name}}</h3>
                                    <div class="user-name">
                                    	<p>{{ $challenge->short_desc}}</p>
                                    </div>
                                </div>
                                <div class="right-list">
                                    <ul>
                                        <li><a href="#"><span>$ {{$challenge->challenges_fee}}</span>Entry Fee <div class="hover-text">See participation</div></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        	<h2>No Challenge found</h2>
                        @endif
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="audition-info">
                    	@if (count($challenges) > 0)
						@foreach($challenges as $challenge)
                        <div class="media">
                            <div class="img-box">
                            	@if($challenge->header_image)
                            	<img src="{{ asset('/uploads/challenge/') }}/{{$challenge->header_image}}" alt="" class="img-fluid">
                                
                                @endif
                            </div>
                            <div class="media-body">
                                <div class="top-content">
                                    <div class="designation">{{$challenge->title}}</div>
                                    <h3>{{$challenge->challenges_name}}</h3>
                                    <div class="deadline">Deadline: {{ date('j.m', strtotime($challenge->deadline)) }}</div>
                                </div>
                                <div class="audition">
                                    <div class="right-content">

                                        <ul class="info-list">
                                        	@if($challenge->gift)
                                            <li>
                                                <h4>{{$challenge->gift}}</h4>
                                            </li>
                                            @endif
                                            @if($challenge->additional_gift)
                                            <li>
                                                <h4>{{$challenge->additional_gift}}</h4>
                                            </li>
                                            @endif
                                        </ul>
                                        <h5>Prize</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="entry-info">
                            <div class="price"><sub>$</sub> {{$challenge->challenges_fee}}</div>
                            <h4>Entry Fee</h4>
                            <div class="package-title">This package includes:</div>
                            {!! $challenge->challenges_detail !!}
                            <a href="{{ url('challenge/participation') }}/{{$challenge->id}}" class="btn btn-danger">Participate</a>
                        </div>

                        <div id="accordion" class="accordion">
                        	@if($challenge->description)
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <a href="#" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Description </a>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                        <p>{!! $challenge->description !!}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($challenge->requirement)
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> Requirements</a>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div class="card-body">
                                        <p>{!! $challenge->requirement !!}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @break
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection