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
    <h3>Add Challenge for coach </h3>
    <h4 class="m-b-20 m-t-20">Challenge detail</h4>
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
    <form action="{{url('admin/add-coach-challenge')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="form-group row {{ $errors->has('agency_id') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Coach Name*</label>
        <div class="col-sm-9">
          <select name="coach_id" class="form-control">
            @foreach($coaches as $coache) 
              <option value="{{$coache->id}}">{{$coache->first_name}} {{$coache->last_name}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-group row {{ $errors->has('first_name') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Challenge Name*</label>
        <div class="col-sm-9">
            <input type="text" maxlength="254" class="form-control {{ $errors->has('challenge-name') ? ' form-control-danger' : '' }}" name="challenge-name" autocomplete="off" value="{{ old('first_name') }}">
            @if($errors->has('challenge-name'))
                <small class="text-danger">{{ $errors->first('challenge-name') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('title') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Title*</label>
        <div class="col-sm-9">
            <input type="text" maxlength="254" class="form-control {{ $errors->has('title') ? ' form-control-danger' : '' }}" name="title" autocomplete="off" value="{{ old('title') }}">
            @if($errors->has('title'))
                <small class="text-danger">{{ $errors->first('title') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('title') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Gift*</label>
        <div class="col-sm-9">
          <input type="text" maxlength="254" class="form-control {{ $errors->has('gift') ? ' form-control-danger' : '' }}" name="gift" autocomplete="off" value="{{ old('gift') }}">
            @if($errors->has('gift'))
                <small class="text-danger">{{ $errors->first('gift') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('level') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Additional gift*</label>
        <div class="col-sm-9">
          <input type="text" maxlength="254" class="form-control {{ $errors->has('additional-gift') ? ' form-control-danger' : '' }}" name="additional-gift" autocomplete="off" value="{{ old('additional-gift') }}">
            @if($errors->has('additional-gift'))
                <small class="text-danger">{{ $errors->first('additional-gift') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('challenge-fee') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Challenge fee*</label>
        <div class="col-sm-9">
            <input type="text" maxlength="254" class="form-control {{ $errors->has('challenge-fee') ? ' form-control-danger' : '' }}" name="challenge-fee" autocomplete="off" value="{{ old('challenge-fee') }}">
            @if($errors->has('challenge-fee'))
                <small class="text-danger">{{ $errors->first('challenge-fee') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('short-desc') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Short Desc.*</label>
        <div class="col-sm-9">
            <input type="text" maxlength="254" class="form-control {{ $errors->has('short-desc') ? ' form-control-danger' : '' }}" name="short-desc" autocomplete="off" value="{{ old('short-desc') }}">
            @if($errors->has('short-desc'))
                <small class="text-danger">{{ $errors->first('short-desc') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('challenge-deadline') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Challenge Deadline*</label>
        <div class="col-sm-9">
            <input id="deadline" type="text" maxlength="254" class="form-control {{ $errors->has('challenge-deadline') ? ' form-control-danger' : '' }}" name="challenge-deadline" autocomplete="off" value="{{ old('challenge-deadline') }}">
            @if($errors->has('challenge-deadline'))
                <small class="text-danger">{{ $errors->first('challenge-deadline') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('challenge-description') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Challenge description*</label>
        <div class="col-sm-9">
            <textarea id="challenge-description" name="challenge-description">{{ old('challenge-description') }}</textarea>
            @if($errors->has('challenge-description'))
                <small class="text-danger">{{ $errors->first('challenge-description') }}</small>
            @endif
        </div>
      </div>
      <div class="form-group row {{ $errors->has('challenge-requirement') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Challenge Requirement*</label>
        <div class="col-sm-9">
            <textarea id="challenge-requirement" name="challenge-requirement">{{ old('challenge-requirement') }}</textarea>
            @if($errors->has('challenge-requirement'))
                <small class="text-danger">{{ $errors->first('challenge-requirement') }}</small>
            @endif
        </div>
      </div>
      <!-- <div class="form-group row {{ $errors->has('challenge-detail') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Package detail*</label>
        <div class="col-sm-9">
            <textarea id="challenge-detail" name="challenge-detail">{{ old('challenge-detail') }}</textarea>
            @if($errors->has('challenge-detail'))
                <small class="text-danger">{{ $errors->first('challenge-detail') }}</small>
            @endif
        </div>
      </div> -->
      <!-- <div class="form-group row {{ $errors->has('logo') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Logo*</label>
        <div class="col-sm-9">
            <input type="file" name="logo" class="form-control">
            @if($errors->has('logo'))
                <small class="text-danger">{{ $errors->first('logo') }}</small>
            @endif
        </div>
      </div> -->
      <div class="form-group row {{ $errors->has('header_image') ? ' has-danger' : '' }}">
        <label class="col-sm-3 form-control-label" for="first_name">Header Image</label>
        <div class="col-sm-9">
            <input type="file" name="header_image" class="form-control">
            @if($errors->has('header_image'))
                <small class="text-danger">{{ $errors->first('header_image') }}</small>
            @endif
        </div>
      </div>
      <div class="row">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-warning m-r-10 m-b-10">
                <i class="btn-icon fa fa-check"></i>Add challenge
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