@extends('layouts.agency')
@section('content')
<div class="main-content auditions-wrap">
    <div class="container-fluid">
        <div class="row">
	        <div class="col-md-12">
	            <div class="coach-list">
	                <h3 >List of Auditions</h3>
	            </div>
	            <table class="table table-bordered table-striped">
	            	<thead>
	            		<th>Audition Name</th>
	            		<th>Title</th>
	            		<th>Fee</th>
	            		<th>Dead Line</th>
	            		<th>Location</th>
	            		<th>Action</th>
	            	</thead>
	            	@if(count($auditions) > 0)
		            	@foreach($auditions as $audition)
			            	<tr>
			            		<td>{{$audition->audition_name}}</td>
			            		<td>{{$audition->title}}</td>
			            		<td>${{$audition->audition_fee}}</td>
			            		<td>{{$audition->deadline}}</td>
			            		<td>{{$audition->location}}</td>
			            		<td><a href="{{url('edit-audition')}}/{{$audition->id}}">Edit</a></td>
			            	</tr>
		            	@endforeach
		            @else
		            <tr>
		            	<td colspan="6">No Audition found!</td>
		            </tr>
		            @endif
	            </table>
	        </div>
	    </div>
    </div>
</div>
@endsection