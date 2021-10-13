@extends('layouts.app')
@section('css')
<style>
  textarea{width: 100%;height: 100%;padding: 10px;}
  .alert.alert-success {
    background-color: #E5113E;
    border-color: #E5113E;
    color: #fff;
}
  
</style>
@endsection
@section('content')
<div class="row m-b-20">
  <div class="col-xs-12">
    <h3>Edit Audition for an Agency </h3>
    <h4 class="m-b-20 m-t-20">Audition detail</h4>
     @if (Session::has('success'))
        <div class="alert alert-success text-center">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <p>{{ Session::get('success') }}</p>
        </div>
      @endif
      @if (Session::has('error'))
          <div class="alert 'alert-danger text-center">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
              <p>{{ Session::get('error') }}</p>
          </div>
      @endif
    <form action="{{url('admin/edit-agency-audition')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
      {{ csrf_field() }}
      <input type="hidden" name="audition_id" value="{{$audition->id}}" />
      <div class="form-group row {{ $errors->has('agency_id') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Agency Name*</label>
        <div class="col-sm-9">
          <select name="agency_id" class="form-control">
            @foreach($agency as $agenci) 
              <option value="{{$agenci->id}}" @if($agenci->id == $audition->agency_id) selected @endif>{{$agenci->first_name}} {{$agenci->last_name}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group row {{ $errors->has('first_name') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Auditon Name*</label>
        <div class="col-sm-9">
            <input type="text" maxlength="254" class="form-control {{ $errors->has('audition-name') ? ' form-control-danger' : '' }}" name="audition-name" autocomplete="off" value="{{ $audition->audition_name }}">
            @if($errors->has('audition-name'))
                <small class="text-danger">{{ $errors->first('audition-name') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('title') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Title*</label>
        <div class="col-sm-9">
            <input type="text" maxlength="254" class="form-control {{ $errors->has('title') ? ' form-control-danger' : '' }}" name="title" autocomplete="off" value="{{ $audition->title }}">
            @if($errors->has('title'))
                <small class="text-danger">{{ $errors->first('title') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('title') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Audition Genres*</label>
        <div class="col-sm-9">
            <select name="audition-genres" class="form-control">
            @foreach($activity_types as $activity_type) 
              <option value="{{$activity_type->id}}" @if($activity_type->id == $audition->talent) selected @endif>{{$activity_type->name}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group row {{ $errors->has('level') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Level*</label>
        <div class="col-sm-9">
            <select name="level" class="form-control">
            @foreach($performance_levels as $performance_level) 
              <option value="{{$performance_level->id}}" @if($performance_level->id == $audition->level) selected @endif>{{$performance_level->name}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group row {{ $errors->has('audition-fee') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Audition fee*</label>
        <div class="col-sm-9">
            <input type="text" maxlength="254" class="form-control {{ $errors->has('audition-fee') ? ' form-control-danger' : '' }}" name="audition-fee" autocomplete="off" value="{{ $audition->audition_fee }}">
            @if($errors->has('audition-fee'))
                <small class="text-danger">{{ $errors->first('audition-fee') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('audition-location') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Audtion Location*</label>
        <div class="col-sm-9">
            <input type="text" maxlength="254" class="form-control {{ $errors->has('audition-location') ? ' form-control-danger' : '' }}" name="audition-location" autocomplete="off" value="{{ $audition->location }}">
            @if($errors->has('audition-location'))
                <small class="text-danger">{{ $errors->first('audition-location') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('audition-deadline') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Audition Deadline*</label>
        <div class="col-sm-9">
            <input id="deadline" type="text" maxlength="254" class="form-control {{ $errors->has('audition-deadline') ? ' form-control-danger' : '' }}" name="audition-deadline" autocomplete="off" value="{{ $audition->deadline }}">
            @if($errors->has('audition-deadline'))
                <small class="text-danger">{{ $errors->first('audition-deadline') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('audition-description') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Audition description*</label>
        <div class="col-sm-9">
            <textarea id="audition-description" name="audition-description">{{ $audition->description }}</textarea>
            @if($errors->has('audition-description'))
                <small class="text-danger">{{ $errors->first('audition-description') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('audition-requirement') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Audition Requirement*</label>
        <div class="col-sm-9">
            <textarea id="audition-requirement" name="audition-requirement">{{ $audition->requirement }}</textarea>
            @if($errors->has('audition-requirement'))
                <small class="text-danger">{{ $errors->first('audition-requirement') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('logo') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Logo*</label>
        <div class="col-sm-9">
            <input type="file" name="logo" class="form-control">
            @if($errors->has('logo'))
                <small class="text-danger">{{ $errors->first('logo') }}</small>
            @endif
            @if($audition->logo)
              <img src="{{asset('uploads/auditions')}}/{{$audition->logo}}" width="100px" />
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('header_image') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Header Image</label>
        <div class="col-sm-9">
            <input type="file" name="header_image" class="form-control">
            @if($errors->has('header_image'))
                <small class="text-danger">{{ $errors->first('header_image') }}</small>
            @endif
            @if($audition->header_image)
              <img src="{{asset('uploads/auditions')}}/{{$audition->header_image}}" width="100px" />
            @endif
        </div>
      </div>
      <div class="row">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-warning m-r-10 m-b-10">
                <i class="btn-icon fa fa-check"></i>Update Audition
            </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
  $(document).ready(function () {
      $('#deadline').datepicker({
          format: 'yyyy-mm-dd',
      }).on('changeDate', function (e) {
          $('#vacation_end').datepicker('setStartDate', e.date);
      });
  });
</script>
@endsection