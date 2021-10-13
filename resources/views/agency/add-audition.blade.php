@extends('layouts.agency')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="main-content auditions-wrap">
    <div class="container-fluid">
        <div class="row">
	        <div class="col-md-12">
	            <div class="coach-list mb-4">
	                <h3>Add New Audition</h3>
	                @if ($errors->any())
					    <div class="alert alert-danger">
					        <ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
					@endif
					@if(session()->has('message'))
					    <div class="alert alert-success">
					        {{ session()->get('message') }}
					    </div>
					@endif
	            </div>
	            <form action="{{url('new-audition')}}" method="post" enctype="multipart/form-data">
	            	{!! csrf_field() !!}
	            	<div class="form-row">
                        <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               <label class="font-weight-bold">Audition Name</label>
                                 <input type="text" class="form-control" placeholder="" name="audition-name" value="{{ old('audition-name') }}" autocomplete="off">

                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="right-box ">
                                <label class="font-weight-bold">Title</label><br/>
                                <input type="text" name="title" class="form-control" autocomplete="off" value="{{ old('title') }}" >
                            </div>       
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               	<label class="font-weight-bold">Audition Genres</label>
								<select class="form-control" name="audition-genres">
									@foreach($genres as $gnr)
										<option value="{{$gnr->id}}">{{$gnr->name}}</option>
									@endforeach
								</select>
                                 
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="right-box ">
                                <label class="font-weight-bold">Level</label><br/>
                                <select class="form-control" name="audition-level">
									@foreach($levels as $level)
										<option value="{{$level->id}}">{{$level->name}}</option>
									@endforeach
								</select>
                            </div>       
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               <label class="font-weight-bold">Audition Fee</label>
                                 <input type="text" class="form-control" placeholder="" name="audition-fee" value="{{ old('audition-fee') }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="right-box ">
                                <label class="font-weight-bold">Audition Location</label><br/>
                                <input type="text" name="audition-location" class="form-control" autocomplete="off" value="{{ old('audition-location') }}" >
                            </div>       
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               <label class="font-weight-bold">Audition Deadline</label>
                                 <input type="text" class="form-control hasDatepicker" placeholder="" name="audition-deadline" value="{{ old('audition-deadline') }}" autocomplete="off">
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               <label class="font-weight-bold">Audition Description</label>
                                <textarea id="audition-description" name="audition-description">{{ old('audition-description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="right-box ">
                                <label class="font-weight-bold">Audition Requirement</label><br/>
                                <textarea id="audition-requirement" name="audition-requirement">{{ old('audition-requirement') }}</textarea>
                            </div>       
                        </div> 
                    </div>
                    <div class="form-row">
                        <!-- <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               	<label class="font-weight-bold">Audition Detail</label><br/>
                                <textarea id="audition-detail" name="audition-detail">{{ old('audition-detail') }}</textarea>
                            </div>
                        </div> -->
                        <div class="form-group col-sm-6">
                            <div class="right-box ">
                                <label class="font-weight-bold">Agency Logo</label><br/>
                                <input type="file" name="logo" class="form-control">
                            </div>
                            <div class="right-box ">
                                <label class="font-weight-bold">Header Image</label><br/>
                                <input type="file" name="header-image" class="form-control">
                            </div>         
                        </div> 
                    </div>
                    <div class="form-row">
                    	<div class="form-group">
                    		<div class="col-md-12">
                    			<input type="submit" name="audtion" value="Add Audition" class="btn btn-danger" />
                    		</div>
                    	</div>
                    </div>
	            </form>
	        </div>
	    </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
  		$('#audition-requirement,#audition-description,#audition-detail').summernote({
        placeholder: '',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
  		var dateformat = 'yyyy-mm-dd';

        $('.hasDatepicker').datepicker({
          format: dateformat,
          autoclose: true
        });
   
   
	});
</script>

@endsection