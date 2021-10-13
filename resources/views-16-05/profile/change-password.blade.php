@extends('layouts.user')

@section('content')
	<div class="main-content">
	    <div class="container-fluid">
	        <div class="row justify-content-center">
	            <div class="col-xl-6 col-lg-10">
	                 <div class="change-password">
	                    <h2>Change Password</h2>
	                    @if(session()->has('success_password'))
	                        <div class="alert alert-success-outline animated fadeIn m-t-20" role="alert">
	                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
	                                        aria-hidden="true">×</span> <span class="sr-only">Close</span></button>
	                            <strong>Success!</strong> {{ session()->get('success_password') }}
	                        </div>
                    	@endif
	                    <form action="/profile/account_settings/new_password" method="POST">
	                        <div class="form-group">
	                        	@if($errors->has('password_old'))
	                        	<div class="text-center">
                                    <small class="text-danger">{{ $errors->first('password_old') }}</small>
                                </div>
                                @endif
	                            <input type="password" name="password_old" class="form-control" placeholder="Old Password">
	                        </div>
	                        <div class="form-group">
	                        	@if($errors->has('password'))
	                        	<div class="text-center">
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                </div>
                                @endif
	                            <input type="password" name="password" class="form-control" placeholder="New Password">
	                        </div>
	                        <div class="form-group">
	                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat New Password">
	                        </div>
	                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
	                        <div class="confirm-btn text-center">
	                        	<button type="submit" class="btn btn-outline-danger">
                                    Confirm
                                </button>
	                          
	                          <a href="#" class="cancel"> Cancel</a>
	                       </div>
	                    </form>
	              </div>
	            </div>      
	        </div>
	    </div>
	</div>
@endsection