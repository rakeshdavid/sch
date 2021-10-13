@extends('layouts.agency')

@section('content')
<div class="main-content profile-wrap1">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-12">
              @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <p class="m-0 p-0">{{ Session::get('success') }}</p>
                </div>
              @endif
                <form action="{{ url('agency')}}/{{$user->id}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="form-group row {{ $errors->has('first_name') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="first_name">First name*</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('first_name') ? ' form-control-danger' : '' }}" name="first_name" autocomplete="off" value="{{$user->first_name}}">
                        @if($errors->has('first_name'))
                            <small class="text-danger">{{ $errors->first('first_name') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('last_name') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="last_name">Last name*</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('last_name') ? ' form-control-danger' : '' }}" name="last_name" autocomplete="off" value="{{$user->last_name}}">
                        @if($errors->has('last_name'))
                            <small class="text-danger">{{ $errors->first('last_name') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('title') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="title">Title</label>
                    <div class="col-sm-9">
                        <input type="text" maxlength="254" class="form-control {{ $errors->has('title') ? ' form-control-danger' : '' }}" name="title" autocomplete="off" value="{{$user->title}}">
                        @if($errors->has('title'))
                            <small class="text-danger">{{ $errors->first('title') }}</small>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('email') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="email">Email*</label>
                    <div class="col-sm-9">
                        <input type="email" maxlength="254" class="form-control {{ $errors->has('email') ? ' form-control-danger' : '' }}" name="email" autocomplete="off" value="{{$user->email}}">
                        @if($errors->has('email'))
                            <small class="text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('location') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="email">Location*</label>
                    <div class="col-sm-9">
                        <input type="test" maxlength="254" class="form-control {{ $errors->has('location') ? ' form-control-danger' : '' }}" name="location" autocomplete="off" value="{{$user->location}}">
                        @if($errors->has('location'))
                            <small class="text-danger">{{ $errors->first('location') }}</small>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('location_state') ? ' has-danger' : '' }}">
                    <label class="col-sm-3 form-control-label" for="location_state">Location state*</label>
                    <div class="col-sm-9">
                        <input type="location_state" maxlength="254" class="form-control {{ $errors->has('location_state') ? ' form-control-danger' : '' }}" name="location_state" autocomplete="off" value="{{$user->location_state}}">
                        @if($errors->has('location_state'))
                            <small class="text-danger">{{ $errors->first('location_state') }}</small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-danger m-r-10 m-b-10">
                            <i class="btn-icon fa fa-check"></i>Update
                        </button>
                    </div>
                </div>
                
            </form>
               
            </div>
        </div>


    </div>
</div>
@endsection
@section('js')
    <script>


        // datepicker
    var dateformat = 'mm.dd.yyyy';

        $('.hasDatepicker').datepicker({
          format: dateformat,
          autoclose: true
        });
    function updateDateFormat(i,elem) {
        var d = $(elem).datepicker('getDate');
      
        $(elem).datepicker('destroy');  
        $(elem).datepicker({
            autoclose: true,
            format: dateformat
          });
        $(elem).datepicker('setDate', d);
        
    };
    </script>

@endsection