@extends('layouts.uploadvideo')
@section('content')
	 <section class="video-download-wrap">
        <div class="process-steps">
            <a href="{{ url('video') }}" class="back-arrow"><img src="/assets/img/back-arrow.png" alt="" class="img-fluid"></a>
            <a href="{{ url('video') }}" class="close-icon"><img src="/assets/img/close.png" alt="" class="img-fluid"></a>
            <ul class="process-menu">
                <li class=""><a href="#">UPLOAD VIDEO</a></li>
                <li class=""><a href="#">SELECT COACH</a> </li>
                <li><a href="#">PAY</a> </li>
            </ul>
    </div>

        <div class="video-content welcome-content">
            <div class="container">
                <h2>Welcome!</h2>
                <h3>Let’s start by uploading your first video performance. </h3>
                @if ($errors->any())
				    <div class="alert alert-danger">
				        <ul>
				            @foreach ($errors->all() as $error)
				                <li>{{ $error }}</li>
				            @endforeach
				        </ul>
				    </div>
				@endif
            <div class="row justify-content-center">
                 <div class="col-lg-9">
                <div class="youtube-link">
                    <div class="row align-items-center">
                      <div class="col-xl-3 col-lg-4 col-md-4 p-0">
                            <div class="left-box"><i class="fab fa-youtube"></i>YouTube Link:</div>
                        </div>
                        <div class="col-xl-9 col-lg-8 col-md-8">
                            <div class="right-box">
                                <input type="search" name="" class="form-control" placeholder="Copy here the url …">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="drag-file">
                    <div class="dropzone-content">
                    <form id="file-upload-form" action="{{ url('upload') }}" method="post" enctype= multipart/form-data>
                    	{!! csrf_field() !!}
                        <input id="file-upload" type="file" name="video" />
                        <label for="file-upload" id="file-drag">
                            <img src="/assets/img/video-icon.jpg" alt="" class="img-fluid">
                            <h3>Drag and Drop your Video here</h3>
                            <span class="or">OR</span>
                            <span id="file-upload-btn" class="browse-btn">Browse</span>
                            <output for="file-upload" id="messages"></output>
                        </label>
                    <p>The maximum video size is 300 mb. Valid formats avi, mpeg4, wmv, mp4, mov.</p>
                    <button type="submit" class="btn btn-danger">Upload</button>
                    </form>
                </div>
                    <div class="skip-box">
                      <a href="{{url('video')}}" class="skip">SKIP FOR NOW</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </section>
@endsection