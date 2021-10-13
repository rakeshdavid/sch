@extends('layouts.uploadvideo')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')
<div class="process-steps">
    <a href="{{url('auditions')}}" class="back-arrow"><img src="/platform/img/back-arrow.png" alt="" class="img-fluid"></a>
    <a href="{{url('auditions')}}" class="close-icon"><img src="/platform/img/close.png" alt="" class="img-fluid"></a>
    <ul class="process-menu">
        <li><a href="#">UPLOAD PARTICIPATION</a></li>
        <li><a href="#">PAY</a> </li>
    </ul>
</div>
<section class="video-download-wrap">
    <div class="video-content participation-content">
        <div class="container">
            <h2>Upload your Participation</h2>
            <div class="row justify-content-center">
            	@if ($errors->any())
				    <div class="alert alert-danger">
				        <ul>
				            @foreach ($errors->all() as $error)
				                <li>{{ $error }}</li>
				            @endforeach
				        </ul>
				    </div>
				@endif
                <div class="col-md-9">
                    <div class="youtube-link">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-lg-4 col-md-4 p-0">
                                <div class="left-box"><i class="fab fa-youtube"></i>YouTube Link:</div>
                            </div>
                            <div class="col-xl-9 col-lg-8 col-md-8">
                                <div class="right-box">
                                     <input type="url" name="" class="form-control" id="youtube-link" placeholder="Copy here the url …">
                                </div>
                                <div class="col-md-12 text-danger p-0" id="response">
                            	</div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="upload-status pl-4 d-none" style="color: #E7133E;font-weight: 700;">
                    	<ul>
                    		<li><span class="response-text"></span> <i class="fa fa-check ml-3"></i></li>
                    	</ul>
                    </div>
                    <div id="upload-video" class="drag-file">
                        <div class="dropzone-content">
                        	
                            <form action="{{url('auditions/participation')}}/{{$auditionid}}" method="post" class="dropzone my-awesome-dropzone" id="my-awesome-dropzone">
                            	<input type="hidden" name="youtube-link" id="copy-link">
                                <label for="file-upload" id="file-drag">
                                    <img src="/platform/img/video-icon.jpg" alt="" class="img-fluid video-icon">
                                    <img src="/platform/img/icons8-submit-resume-80.png" alt="" class="img-fluid resume-icon d-none">
                                    <h3 class="upload-file">Drag and Drop your Video</h3>
                                    <span class="or">OR</span>
                                    <span class="browse-btn">Browse</span>
                                </label> 
                            </form>
                        </div>
                        
                        <div class="skip-box">
                        	<input id="uploadvideo" type="submit" class="btn btn-outline-dark" value="Participate" name="participate" />
                            <!-- <a href="#" class="btn btn-outline-dark">Participate</a> -->
                            <a href="{{url('auditions')}}" class="skip">SKIP FOR NOW</a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
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
		$(function(){
		  // Dropzone.options.myAwesomeDropzone = {
		  //   maxFilesize: 209715200,
		  //   addRemoveLinks: true,
		  //   autoProcessQueue: false,
		  //   dictResponseError: 'Server not Configured',
		  //   acceptedFiles: "video/*,.pdf",
		  //   init:function(){
		  //     var self = this;
		  //     // config
		  //     self.options.addRemoveLinks = true;
		  //     self.options.dictRemoveFile = "Delete";
		  //     //New file added
		  //     self.on("addedfile", function (file) {
		  //       console.log('new file added ', file);
		  //       console.log("===============");
		  //       console.log(file.status);
		  //       console.log(file.name);
		        
		  //     });
		  //     // Send file starts
		  //     self.on("sending", function (file, xhr, formData) {
		  //     	formData.append("_token", CSRF_TOKEN);
		  //       console.log('upload started', file);
		  //       $('.meter').show();
		  //     });
		      
		  //     // File upload Progress
		  //     self.on("totaluploadprogress", function (progress) {
		  //       console.log("progress ", progress);
		  //       $('.roller').width(progress + '%');
		  //     });

		  //     self.on("queuecomplete", function (progress) {
		  //       $('.meter').delay(999).slideUp(999);
		  //     });
		      
		  //     // On removing file
		  //     self.on("removedfile", function (file) {
		  //       console.log(file);
		  //     });
		  //   }
		  // };
		  Dropzone.autoDiscover = false;

			var myDropzone = new Dropzone("#my-awesome-dropzone", { 
    		maxFilesize: 314572800,
		    addRemoveLinks: true,
		    autoProcessQueue: false,
		    dictResponseError: 'Server not Configured',
		    acceptedFiles: "video/*,.pdf",
		    init:function(){
				var self = this;
				// config
				self.options.addRemoveLinks = true;
				self.options.dictRemoveFile = "Delete";
				//New file added
				self.on("addedfile", function (file) {
				console.log('new file added ', file);
				console.log(file.type);
				// if (file.type == "application/pdf"){
				// 	myDropzone.removeAllFiles(true);
				// 	$('.upload-status ul').append('<li>Resume should be in pdf file</li>');
				// 	console.log('Resume should be in pdf file');
				// }
				$("#uploadvideo").addClass('active-button');
				});
		      	// Send file starts
					self.on("sending", function (file, xhr, formData) {
						formData.append("_token", CSRF_TOKEN);
						var link = $("#copy-link").val();
						formData.append("youtube-link",link);
						//console.log('upload started', file);
						$('.meter').show();
					});

					// File upload Progress
					self.on("totaluploadprogress", function (progress) {
					console.log("progress ", progress);
					$('.roller').width(progress + '%');
					});

					self.on("queuecomplete", function (progress) {
					$('.meter').delay(999).slideUp(999);
					$("#uploadvideo").removeClass('active-button');
					//console.log(progress);
					});

					// On removing file
					self.on("removedfile", function (file) {
					//console.log(file);
		      		});
					self.on("success", function(file, responseText) {
						var response = JSON.parse(file.xhr.responseText);
						console.log(response.message);
						if(response.status==422){
							$("#response").html('<p class="pt-2">'+response.message+'</p>');
						}
						if(response.status==201){
							$("#response").html('');
          					$("#copy-link").val('');
      						$('.response-text').html(response.message);
      						myDropzone.removeAllFiles(true)
      						$(".upload-file").html("Drag and Drop your resume<br>(pdf format only)");
      						$(".upload-status").removeClass('d-none');
      						$(".video-icon").addClass('d-none');
      						$(".resume-icon").removeClass('d-none');
      					}
      					if(response.status==200){
      						window.location.href = response.redirect;
      					}
        			});
		  		}
			});

		  	$('#uploadvideo').click(function(){
		  		console.log("click");
		  		if (myDropzone.getQueuedFiles().length === 0 && $("#copy-link").val() !="") {
				    var blob = new Blob();
				    var link = $("#copy-link").val();
					//formData.append("youtube-link",link);
				    blob.upload = { 'chunked': myDropzone.defaultOptions.chunking };
				    myDropzone.uploadFile(blob);
				    $('.meter').show();
				} else {
				    myDropzone.processQueue();
				}
			   
			});
		});
	</script>
@endsection