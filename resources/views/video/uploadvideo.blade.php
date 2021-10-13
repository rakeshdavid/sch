@extends('layouts.uploadvideo')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')
	 <section class="video-download-wrap">
        <div class="process-steps">
            <a href="{{ url('video') }}" class="back-arrow"><img src="/platform/img/back-arrow.png" alt="" class="img-fluid"></a>
            <a href="{{ url('video') }}" class="close-icon"><img src="/platform/img/close.png" alt="" class="img-fluid"></a>
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
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-md-4 p-0">
                                <div class="left-box"><i class="fab fa-youtube"></i>YouTube Link:</div>
                            </div>
                            <div class="col-xl-9 col-lg-8 col-md-8">
                                <div class="right-box">
                                    <form action="{{url('uplaod-youtube')}}" method="post">
                                      {{ csrf_field() }}
                                        <input type="text" name="youtube-link" class="form-control" placeholder="Copy here the url …">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="drag-file">
                        <div class="dropzone-content">
                        <form id="my-awesome-dropzone" class="dropzone" action="{{ url('upload') }}" method="post" enctype= multipart/form-data>
                        	
                            <!-- <input id="file-upload" type="file" name="video" /> -->
                            <label for="file-upload" id="file-drag">
                                <img src="/platform/img/video-icon.jpg" alt="" class="img-fluid">
                                <h3>Drag and Drop your Video here</h3>
                                <span class="or">OR</span>
                                <span id="file-upload-btn" class="browse-btn">Browse</span>
                                <output for="file-upload" id="messages"></output>
                            </label>
    					
                        
                        <p>The maximum video size is 300 mb. Valid formats avi, mpeg4, wmv, mp4, mov.</p>
                        
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
    <div class="uploading-video">
      <img src="/platform/img/loader1.gif" class="loader-img">
      <h4>Uploading...  <span class="upload-progress"></span> %</h4>
    </div>
</section>

@endsection

@section('js')
    <script>
        // file upload
        var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        $(document).ready(function() {
            $('#youtube-link').on('change', function() {
               $('#copy-link').val($(this).val());
            });
        });
        // file upload
        $(function(){
          Dropzone.options.myAwesomeDropzone = {
            maxFilesize: 314572800,
            addRemoveLinks: true,
            dictResponseError: 'Server not Configured',
            acceptedFiles: "video/*",
            init:function(){
              var self = this;
              // config
              self.options.addRemoveLinks = true;
              self.options.dictRemoveFile = "Delete";
              //New file added
              self.on("addedfile", function (file) {
                console.log('new file added ', file);
              });
              // Send file starts
              self.on("sending", function (file, xhr, formData) {
                formData.append("_token", CSRF_TOKEN);
                console.log('upload started', file);
                $('.meter').show();
                $('.uploading-video').show();
                
              });
              
              // File upload Progress
              self.on("totaluploadprogress", function (progress) {
                console.log("progress ", progress);

                $('.roller').width(progress + '%');
                $(".upload-progress").html(progress.toFixed(2));
              });

              self.on("queuecomplete", function (progress) {
                $('.meter').delay(999).slideUp(999);
              });
              
              // On removing file
              self.on("removedfile", function (file) {
                console.log(file);
              });
              self.on("success", function(file, responseText) {
                 var response = JSON.parse(file.xhr.responseText);
                console.log(response.message);
                console.log(responseText);
                // $("#response").html("<h2>"+response.message+"</h2>");
                if(response.status==400){
                    window.location.href = response.redirect;
                }
                if(response.status==200){
                    window.location.href = response.redirect;
                }
                });
            }
          };
        });
    </script>
@endsection