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
	                <h3>Update Challenge</h3>
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
	            <form action="{{url('edit-challenge')}}/{{$challenge->id}}" method="post" enctype="multipart/form-data">
	            	{!! csrf_field() !!}
	            	<div class="form-row">
                        <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               <label class="font-weight-bold">Challenge Name</label>
                                 <input type="text" class="form-control" placeholder="" name="challenge-name" value="{{$challenge->challenges_name}}" autocomplete="off">

                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="right-box ">
                                <label class="font-weight-bold">Title</label><br/>
                                <input type="text" name="title" class="form-control" autocomplete="off" value="{{$challenge->title}}" >
                            </div>       
                        </div> 
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               <label class="font-weight-bold">Challenge Fee</label>
                                 <input type="text" class="form-control" placeholder="" name="challenge-fee" value="{{$challenge->challenges_fee}}" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="right-box ">
                                <label class="font-weight-bold">Short Description</label><br/>
                                <input type="text" name="short-desc" class="form-control" autocomplete="off" value="{{$challenge->short_desc}}" >
                            </div>       
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6 ">
                            <div class="right-box">
                               <label class="font-weight-bold">Deadline</label>
                                 <input type="text" class="form-control hasDatepicker" placeholder="" name="challenge-deadline" value="{{$challenge->deadline}}" autocomplete="off">
                            </div>
                          	<div class="right-box">
                               <label class="font-weight-bold">Challenge Prize</label>
                                 <input type="text" class="form-control " placeholder="" name="gift" value="{{$challenge->gift}}" autocomplete="off">
                            </div>
                            <div class="right-box">
                               <label class="font-weight-bold">Additional Prizes</label>
                                 <input type="text" class="form-control " placeholder="" name="additional-gift" value="{{$challenge->additional_gift}}" autocomplete="off">
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               <label class="font-weight-bold">Challenge Description</label>
                                <textarea id="challenge-description" name="challenge-description">{!! $challenge->description !!}</textarea>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="right-box ">
                                <label class="font-weight-bold">Challenge Requirements</label><br/>
                                <textarea id="challenge-requirement" name="challenge-requirement">{!! $challenge->requirement !!}</textarea>
                            </div>       
                        </div> 
                    </div>
                    <div class="form-row">
                       <!--  <div class="form-group col-sm-6 ">
                          	<div class="right-box">
                               	<label class="font-weight-bold">Challenge Details</label><br/>
                                <textarea id="challenge-detail" name="challenge-detail">{!! $challenge->challenges_detail !!}</textarea>
                            </div>
                        </div> -->
                        <div class="form-group col-sm-6">
                            <!-- <div class="right-box ">
                                <label class="font-weight-bold">Logo</label><br/>
                                <input type="file" name="logo" class="form-control">
                                @if($challenge->logo)
                                    <img src="{{asset('uploads/challenge')}}/{{$challenge->logo}}" width="100px" /> 
                                @endif
                            </div> -->
                            <div class="right-box ">
                                <label class="font-weight-bold">Header Image</label><br/>
                                <input type="file" name="header-image" class="form-control">
                                @if($challenge->header_image)
                                    <img src="{{asset('uploads/challenge')}}/{{$challenge->header_image}}" width="100px" >
                                @endif
                            </div>         
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