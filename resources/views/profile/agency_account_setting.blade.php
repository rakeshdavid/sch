@extends('layouts.agency')
@section('css')
    <link type="text/css" rel="stylesheet" href="/assets/js/sweetalert/sweet-alert.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/toastr/toastr.min.css">
@endsection
@section('content')

   
 <div class="main-content profile-wrap1">
    <div class="container-fluid">
    <div class="row m-b-20">
        <div class="col-xs-12 col-lg-10">
            <div class="bs-nav-tabs nav-tabs-warning">
                <h4 class="border-bottom pb-3">Account Setting</h4>
                <div class="tab-content pb-3 pt-3">
                    @if(session()->has('success_password'))
                        <div class="alert alert-success-outline animated fadeIn m-t-20" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span> <span class="sr-only">Close</span></button>
                            <strong>Success!</strong> {{ session()->get('success_password') }}
                        </div>
                    @endif
                    <div role="tabpanel" class="tab-pane in active" id="nav-tabs-0-3">
                        <div class="p-t-20">

                            @if($user->isAgency($user->id))
                                {{--<h4 class="m-b-20">Payment Details</h4>--}}
                                <h4 class="m-b-20">Stripe</h4>
                                @if( isset($user->stripe_connection->id) )
                                    <div class="pb-3 pt-3">
                                        <h3 class="f-s-15 uppercase color-danger m-t-15">Successfully added!</h3>
                                        <p>
                                            You can disconnect from Showcase platform by going to Stripe control panel.
                                            <br>
                                            Disconnecting from the platform may take several minutes.
                                            <br>
                                            <a target="_blank" href="https://dashboard.stripe.com/account/applications"><strong>Stripe control panel</strong></a>
                                        </p>
                                    </div>
                                @else
                                    <div class="pb-3 pt-3">
                                        <a class="btn btn-danger" href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id={{env('STRIPE_CLIENT_ID')}}&scope=read_write&redirect_uri=https://agency.showcasehub.com/stripeConnectCallback">
                                            Connect to showcase-hub stripe platform
                                        </a>
                                    </div>
                                @endif
                            @endif

                                <form action="/profile/account_settings/new_password" method="POST"
                                      class="form-horizontal">
                                    <h4 class="mb-3">Password/Security Options</h4>
                                    <h4 class="mb-3">Change Password</h4>
                                    <div class="form-group row">
                                        
                                        <div class="col-sm-9">
                                            @if($errors->has('password_old'))
                                                <small class="text-danger">{{ $errors->first('password_old') }}</small>
                                            @endif
                                            <input type="password" placeholder="Old password" class="form-control border mb-3"
                                                   name="password_old" autocomplete="off">
                                            @if($errors->has('password'))
                                                <small class="text-danger">{{ $errors->first('password') }}</small>
                                            @endif
                                            <input type="password" maxlength="254" class="form-control border mb-3" name="password" autocomplete="off" placeholder="New password">
                                            <input type="password" maxlength="254" class="form-control border mb-3"  name="password_confirmation" autocomplete="off" placeholder="Password confirmation">
                                        </div>
                                    </div>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                        <div class="col-sm-offset-3 col-sm-9">
                                            <button type="submit" class="btn btn-danger m-r-10 m-b-10">
                                                <i class="btn-icon fa fa-check"></i>  Save
                                            </button>
                                        </div>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="success_connection" data-value="{{Session::get('success_connection')}}">
    <input type="hidden" id="error_connection" data-value="{{Session::get('error_connection')}}">
</div>
</div>
@endsection
@section('js')
    <script type="text/javascript" src="/assets/js/toastr/toastr.min.js"></script>
    <script type="text/javascript" src="/assets/js/sweetalert/sweet-alert.min.js"></script>
    <script>
        $(document).ready(function () {
            var success_payment = $('#success_connection').attr('data-value');
            var error_connection = $('#error_connection').attr('data-value');
            if(success_payment){
                swal("Success!", "Successfully added!", "success");
            }
            if(error_connection){
                swal("Error!!", "Oops, something happened. Try later.", "error");
            }
        });

    </script>
@endsection
