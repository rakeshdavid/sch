@extends('layouts.auth.main')
@section('title')
    Login
@endsection
@section('meta')
    <meta name="description" content="Showcasehub, sign in">
@endsection
@section('css')

@endsection

@section('content')
  <section class="login-wrapper">
        <div class="container-fluid p-0">
            <div class="row signupform-with-slider align-items-top m-0" >
                <div class="col-lg-6 p-0">
                    <div class="findout-wrap" >
                       <!--  <div class="img-box d-block d-lg-none w-100" >
                        <img src="img/login-bg.png" alt="" class="img-fluid">
                    </div> -->
                        <div class="left-content">
                            <div class="logo"><a href="#"><img src="/assets/img/logo.png" alt="" class="img-fluid"></a></div>
                               <a href="#" class="find-arrow">
                                   <img src="/assets/img/down-arrow.png" alt="" class="img-fluid"><span>Find out more</span>
                               </a>
                        </div>
                    </div>

                    <!-- Full Page Image Carousel -->
                    <div class="slider-wrapper" style="display: none;">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active" style="background: url(/assets/img/black-man.jpg)center top/cover;" >
                                    <div class="carousel-caption feedback-caption">
                                        <div class="logo"><a href="#"><img src="/assets/img/logo.png" alt="" class="img-fluid"></a></div>
                                        <h1>Feedback <br>on demand in <span class="text-red">3 easy steps</span></h1> 
                                        <p>Welcome to the platform that <br>connects dancers of all levels with seasoned professionals.</p>
                                    </div>
                                </div>
                                <div class="carousel-item" style="background: url(/assets/img/slide1.jpg)center top/cover;" >
                                    <div class="carousel-caption record-caption">
                                        <div class="logo"><a href="#"><img src="/assets/img/logo.png" alt="" class="img-fluid"></a></div>
                                         <div class="no-box">01</div>
                                         <h1>Record & upload <span class="text-red">your performance</span></h1>
                                        <p>Submit your dance reel, rehearsal video or studio tape.</p>
                                    </div>
                                </div>
                                <div class="carousel-item" style="background: url(/assets/img/slide2.jpg)center top/cover;" >
                                    <div class="carousel-caption category-caption">
                                        <div class="logo"><a href="#"><img src="/assets/img/logo.png" alt="" class="img-fluid"></a></div>
                                        <div class="no-box">02</div>
                                          <h1>Select one <span class="text-red">category</span></h1>

                                          <ul class="category-list">
                                              <li>
                                                  <h3>Coaching</h3>
                                                  <p>Feedback and guidance provided by the dance professional YOU select.</p>
                                              </li>
                                              <li>
                                                  <h3>Auditions</h3>
                                                  <p>Job opportunities posted by professionals looking for dancers.</p>
                                              </li>
                                              <li>
                                                  <h3>Challenges</h3>
                                                  <p> Dance contests created by professionals and studios offering a variety of giveaways and scholarships. </p>
                                              </li>
                                          </ul>
                                    </div>
                                </div>
                                <div class="carousel-item" style="background: url(/assets/img/slide3.jpg)center top/cover;" >
                                    <div class="carousel-caption professional-caption">
                                        <div class="logo"><a href="#"><img src="/assets/img/logo.png" alt="" class="img-fluid"></a></div>
                                        <div class="no-box">03</div>
                                        <h1>Receive <span class="text-red">professional<br> feedback</span></h1>
                                        <p>All performances submitted will receive personalized verbal and written feedback!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 p-0">
                    <div class="right-content-wrap">
                        <div class="outer-box">
                            <div class="top-box">
                                <h2><span>Welcome To</span> Showcase Hub</h2>
                                <h3>Feedback on Demand</h3>
                                <p>Where we Supply you with today's professionals in the performing arts.</p>
                            </div>
                            <div class="inner-form">
                                    <form class="login-form" id="login-form" method="POST" action="{{ url('/login') }}">
                                      {!! csrf_field() !!}
                                        <h3>LOG IN</h3>
                                        <div class="facebook-btn"><a href="#"><span><i class="fab fa-facebook-square"></i></span>Continue With Facebook</a></div>
                                        <div class="or-box">OR</div>
                                        <div class="form-group {{ $errors->has('email') ? ' has-error' :
                                         old('email') ? ' has-focus' : ''}}">
                                            <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                            <p class="error-block">{{ $errors->has('email') ? $errors->first('email') : '' }}</p>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" placeholder="Password" name="password">
                                        </div>
                                        <a href="#" class="forgot-password">Forgot password?</a>
                                        <!-- <a href="#" class="btn btn-light">Log In</a> -->
                                        <input type="submit" class="btn btn-light" value="Log In">
                                        <div class="register">New here? <a href="javascript:void(0)"><strong>Register Now</strong></a></div>
                                    </form>
                                    <form class="register-form" name="form" id="register-form" method="POST" action="{{ url('/register') }}">
                                      {!! csrf_field() !!}
                                        <h3>Register</h3>
                                       @if($errors->any())
    {{ implode('', $errors->all('<div>:message</div>')) }}
@endif
                                        <div class="form-group {{old('first_name') ? ' has-focus' : ''}}
                                    {{$errors->has('first_name') ? ' has-error' : '' }}">
                                            <input id="full-name" type="text" name="first_name" class="form-control" placeholder="Full Name" value="{{ old('first_name') }}">
                                            <p class="error-block"></p>
                                        </div>
                                        <div class="form-group">
                                            <input id="email" name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                            <p class="error-block">{{ $errors->has('email') ? $errors->first('email') : '' }}</p>
                                        </div>
                                        <div class="form-group">
                                            <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                                            <p class="error-block"></p>
                                        </div>
                                        <div class="form-group">
                                            <input id="password" name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password">
                                            <p class="error-block"></p>
                                        </div>
                                        <input id="submit_create_account" type="submit" class="btn btn-light" value="Creat Account">
                                        
                                        <p>By creating your account you agree to our <a href="#">Terms & Conditions.</a></p>
                                        <div class="login">I have an account! <a href="javascript:void(0)"> <strong>Log In </strong></a></div>
                                    </form>
                                <form class="reset-password" id="password-form" method="POST" action="{{ url('/password/email') }}">
                                  {{ csrf_field() }}
                                    @if (session('status'))
                                        <div class="alert alert-success-outline animated fadeIn" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span> <span class="sr-only">Close</span>
                                            </button> <strong>Success!</strong> {{ session('status') }}
                                        </div>
                                    @endif
                                    <h3>Reset password</h3>
                                    <p>Please enter the email address associated with your account.</p>
                                    <p class="mb-5">You will receive an email to reset your password.</p>
                                    <div class="form-group">
                                        <input autocomplete="off" type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                        <p class="error-block"></p>
                                    </div>
                                    <input type="submit" class="btn btn-light" value="Reset Password">
                                    
                                    <div class="cancel"><a href="#">Cancel</a></div>
                                </form>
                            </div>

                            <div class="bottom-menu-wrap">
                                <a href="#" class="app-icon"><img src="/assets/img/app-download.png" class="img-fluid"></a>
                                  <ul class="bottom-menus">
                                      <li><a href="#">About Us</a></li>
                                      <li><a href="#">Support</a></li>
                                      <li><a href="#">Legal</a></li>
                                  </ul>
                               </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('js')
   
@endsection