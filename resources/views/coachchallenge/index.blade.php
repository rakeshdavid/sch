@extends('layouts.app')
@section('content')
<div class="main-content auditions-wrap">
    <div class="container-fluid">
        <div class="row">
	        <div class="col-md-12">
	            <div class="coach-list">
	                <h3 >List of Challenges</h3>
	            </div>
	            <table class="table table-bordered table-striped">
	            	<thead>
	            		<th>Challenge Name</th>
	            		<th>Title</th>
	            		<th>Fee</th>
	            		<th>Gift</th>
	            		<th>Additional Gift</th>
	            		<th>Action</th>
	            	</thead>
	            	@if(count($challenges) > 0)
		            	@foreach($challenges as $challenge)
			            	<tr>
			            		<td>{{$challenge->challenges_name}}</td>
			            		<td>{{$challenge->title}}</td>
			            		<td>${{$challenge->challenges_fee}}</td>
			            		<td>{{$challenge->gift}}</td>
			            		<td>{{$challenge->additional_gift}}</td>
			            		<td><a href="{{url('edit-challenge')}}/{{$challenge->id}}">Edit</a></td>
			            	</tr>
		            	@endforeach
		            @else
		            	<tr>
		            		<td colspan="6">You have not added any challenges!</td>
		            	</tr>
		            @endif
	            </table>
	        </div>
	    </div>
    </div>
</div>
@endsection