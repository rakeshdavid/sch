@extends('layouts.app')

@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3> Edit Agency Detail </h3>
            <h4 class="m-b-20 m-t-20">Agency Data</h4>
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
            <form action="{{ url('agency')}}/{{$agency->id}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="form-group row {{ $errors->has('first_name') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="first_name">First name*</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('first_name') ? ' form-control-danger' : '' }}" name="first_name" autocomplete="off" value="{{ $agency->first_name }}">
                        @if($errors->has('first_name'))
                            <small class="text-danger">{{ $errors->first('first_name') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('last_name') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="last_name">Last name*</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('last_name') ? ' form-control-danger' : '' }}" name="last_name" autocomplete="off" value="{{ $agency->last_name }}">
                        @if($errors->has('last_name'))
                            <small class="text-danger">{{ $errors->first('last_name') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('title') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="title">Title</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('title') ? ' form-control-danger' : '' }}" name="title" autocomplete="off" value="{{ $agency->title }}">
                        @if($errors->has('title'))
                            <small class="text-danger">{{ $errors->first('title') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('email') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="email">Email*</label>
                    <div class="col-sm-9">
                        <input type="email" maxlength="254" class="form-control {{ $errors->has('email') ? ' form-control-danger' : '' }}" name="email" autocomplete="off" value="{{ $agency->email }}">
                        @if($errors->has('email'))
                            <small class="text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('location') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="email">Location</label>
                    <div class="col-sm-9">
                        <input type="test" maxlength="254" class="form-control {{ $errors->has('location') ? ' form-control-danger' : '' }}" name="location" autocomplete="off" value="{{ $agency->location }}">
                        @if($errors->has('location'))
                            <small class="text-danger">{{ $errors->first('location') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('location_state') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="location_state">Location state*</label>
                    <div class="col-sm-9">
                        <input type="location_state" maxlength="254" class="form-control {{ $errors->has('location_state') ? ' form-control-danger' : '' }}" name="location_state" autocomplete="off" value="{{ $agency->location_state }}">
                        @if($errors->has('location_state'))
                            <small class="text-danger">{{ $errors->first('location_state') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-warning m-r-10 m-b-10">
                            <i class="btn-icon fa fa-check"></i>updates
                        </button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
@endsection