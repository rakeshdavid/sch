@extends('layouts.uploadvideo')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')
	<div class="process-steps">
        <a href="/select-coache/{{$video_id}}" class="back-arrow"><img src="/platform/img/back-arrow.png" alt="" class="img-fluid"></a>
        <a href="{{url('video')}}" class="close-icon"><img src="/platform/img/close.png" alt="" class="img-fluid"></a>
        <ul class="process-menu">
            <li class="active"><a href="#">UPLOAD VIDEO</a></li>
            <li class="active"><a href="#">SELECT COACH</a> </li>
            <li><a href="#">PAY</a> </li>
        </ul>
    </div>

    <section class="select-coach-wrap select-question">
        <div class="container-fluid">
            <h2 class="question-title">What would you like to ask the coach?</h2>
            <h3>You get 3 questions </h3>

        </div>
        <div class="container mt-70">
            <form method="post" id="ask-question">
                <input type="hidden" name="video-id" value="{{$video_id}}">
                 {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-4">
                        <h3 class="text-lg-right pt-3">Question 1:</h3>
                    </div>
                    <div class="col-md-6">
                        <textarea name="question-1" class="form-control border" rows="3" placeholder="What's your first question?"></textarea>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-4">
                        <h3 class="text-lg-right pt-3">Question 2:</h3>
                    </div>
                    <div class="col-md-6">
                        <textarea name="question-2" class="form-control border" rows="3" placeholder="What's your second question?"></textarea>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-4">
                        <h3 class="text-lg-right pt-3">Question 3:</h3>
                    </div>
                    <div class="col-md-6">
                        <textarea name="question-3" class="form-control border" rows="3" placeholder="What's your third question?"></textarea>
                    </div>
                </div>
                <div class="row text-center mt-5">
                    <div class="col-12 text-center">
                        <div class="response"></div>
                        <div class="skip-now ">
                            <button id="submit-question" class="btn-danger">Ask Question</button>
                            <a class="cancel" href="{{url('/')}}/payment/{{$video_id}}">Skip for Now</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#submit-question').click(function(e){
                e.preventDefault();
                var formid = "#ask-question";
                if( $(formid + ' textarea[name="question-1"]').val() !="" || $(formid + ' textarea[name="question-2"]').val() !="" || $(formid + ' textarea[name="question-3"]').val() !=""){
                    var formdata = $(formid).serialize(); // here $(this) refere to the form its submitting
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/') }}/ask-question-coach",
                        data: formdata, // here $(this) refers to the ajax object not form
                        success: function (data) {
                           console.log(data);
                           $('.response').html('');
                           $(formid + ' .response').html('<h4>Question has been posted.</h4>');
                           $(formid + ' textarea[name="question-1"]').val('');
                           $(formid + ' textarea[name="question-2"]').val('');
                           $(formid + ' textarea[name="question-3"]').val('');
                           if(data.status == 200){
                                window.location.replace(data.redirect);
                            }
                        },
                    });
                }else{
                    $('.response').html('');
                    $('.response').html('<p>Please submit questions!</p>');
                }
            });
        });
    </script>
@endsection