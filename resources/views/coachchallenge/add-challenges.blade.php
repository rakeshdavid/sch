@extends('layouts.app')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<style>
  textarea{width: 100%;padding: 10px;height:100px;}
</style>
@endsection
@section('content')
<div class="main-content challgenges-wrap">
    <div class="container-fluid">
        <div class="row">
	        <div class="col-md-12">
	            <div class="coach-list mb-4">
	                <h3>Add New Challenge</h3>
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
        					    <div class="alert" style="background-color:#E5113E;color:#fff;">
        					        {{ session()->get('message') }}
        					    </div>
        					@endif
	            </div>
	            <form action="{{url('new-challenge')}}" method="post" enctype="multipart/form-data">
	            	{!! csrf_field() !!}
	            	<div class="form-row">
                        <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               <label class="font-weight-bold">Challenge Name</label>
                                 <input type="text" class="form-control" placeholder="" name="challenge-name" value="{{ old('challenge-name') }}" autocomplete="off">

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
                               <label class="font-weight-bold">Challenge Fee</label>
                                 <input type="text" class="form-control" placeholder="" name="challenge-fee" value="{{ old('challenge-fee') }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="right-box ">
                                <label class="font-weight-bold">Short Description</label><br/>
                                <input type="text" name="short-desc" class="form-control" autocomplete="off" value="{{ old('short-desc') }}" >
                            </div>       
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6 ">
                            <div class="right-box">
                               <label class="font-weight-bold">Deadline</label>
                                 <input type="text" class="form-control hasDatepicker" placeholder="" name="challenge-deadline" value="{{ old('deadline') }}" autocomplete="off">
                            </div>
                          	<div class="right-box p-t-1">
                               <label class="font-weight-bold">Challenge Prize</label>
                                 <input type="text" class="form-control " placeholder="" name="gift" value="{{ old('gift') }}" autocomplete="off">
                            </div>
                            <div class="right-box p-t-1">
                               <label class="font-weight-bold">Additional Prizes</label>
                                 <input type="text" class="form-control " placeholder="" name="additional-gift" value="{{ old('additional-gift') }}" autocomplete="off">
                            </div>
                            <div class="right-box p-t-1">
                                <label class="font-weight-bold">Header Image</label><br/>
                                <input type="file" name="header-image" class="form-control">
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               <label class="font-weight-bold">Challenge Description</label>
                                <textarea id="challenge-description" name="challenge-description">{{ old('challenge-description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="right-box ">
                                <label class="font-weight-bold">Challenge Requirements</label><br/>
                                <textarea id="challenge-requirement" name="challenge-requirement">{{ old('challenge-requirement') }}</textarea>
                            </div>       
                        </div> 
                    </div>
                    <div class="form-row">
                        <!-- <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               	<label class="font-weight-bold">Challenge Detail</label><br/>
                                <textarea id="challenge-detail" name="challenge-detail">{{ old('challenge-detail') }}</textarea>
                            </div>
                        </div> -->
                        <div class="form-group col-sm-6">
                            <!-- <div class="right-box ">
                                <label class="font-weight-bold">Logo</label><br/>
                                <input type="file" name="logo" class="form-control">
                            </div> -->
                                     
                        </div> 
                    </div>
                    <div class="form-row">
                    	<div class="form-group">
                    		<div class="col-md-12">
                    			<input type="submit" name="audtion" value="Add challenge" class="btn btn-danger" />
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
  		// $('#challenge-requirement,#challenge-description,#challenge-detail').summernote({
    //     placeholder: '',
    //     tabsize: 2,
    //     height: 120,
    //     toolbar: [
    //       ['style', ['style']],
    //       ['font', ['bold', 'underline', 'clear']],
    //       ['color', ['color']],
    //       ['para', ['ul', 'ol', 'paragraph']],
    //       ['table', ['table']],
    //       ['insert', ['link']],
    //       ['view', ['fullscreen', 'codeview', 'help']]
    //     ]
    //   });
  		var dateformat = 'yyyy-mm-dd';

        $('.hasDatepicker').datepicker({
          format: dateformat,
          autoclose: true
        });
   
   
	});
</script>

@endsection