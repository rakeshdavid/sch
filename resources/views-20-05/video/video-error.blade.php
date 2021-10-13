@extends('layouts.uploadvideo')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')
    <section class="video-download-wrap video-error">
        <div class="container">
            <h2>Oh oh, something went wrong…</h2>
            <h3>Try to upload your video again.</h3>
            <div class="row justify-content-center ">
                <div class="col-lg-9">
                    <div class="youtube-link">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-lg-4 col-md-4 p-0">
                                <div class="left-box"><i class="fab fa-youtube"></i>YouTube Link:</div>
                            </div>
                            <div class="col-xl-9 col-lg-8 col-md-8">
                                <form action="{{url('uplaod-youtube')}}" method="post">
                                    <div class="right-box">
                                        {{ csrf_field() }}
                                        <input type="text" name="youtube-link" class="form-control" placeholder="Copy here the url …">
                                        <p>{{$error}}</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="drag-file">
                        <div class="dropzone-content">
                            <form id="my-awesome-dropzone" class="dropzone" action="{{ url('upload') }}" method="post" enctype= multipart/form-data>
                                <label for="file-upload" id="file-drag">
                                    <img src="/assets/img/video-icon.jpg" alt="" class="img-fluid">
                                    <h3>Drag and Drop your Video here</h3>
                                    <span class="or">OR</span>
                                    <span class="browse-btn">Browse</span>
                                </label>
                            </form>
                        </div>
                        <p><span class="error-text">The maximum video size is 300 mb. Valid formats avi, mpeg4, wmv, mp4, mov.</span></p>
                        <div class="skip-box">
                            <a href="#" class="skip">SKIP FOR NOW</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endesction
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
            maxFilesize: 209715200,
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
              });
              
              // File upload Progress
              self.on("totaluploadprogress", function (progress) {
                console.log("progress ", progress);
                $('.roller').width(progress + '%');
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