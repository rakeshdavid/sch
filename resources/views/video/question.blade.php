<form method="post" action="" id="ask-question-">
	{!! csrf_field() !!}
	<div class="row">
    	<div class="col-lg-12">
    		<input type="hidden" name="video-id" value="" />
            <input type="text" name="question-title" class="form-control" placeholder="Ask your question..." />
        </div>
        <div class="col-lg-12">

			<button class="btn btn-outline-danger submit-question" data-videoid="">Ask Question</button>
        </div>
    </div>
</form>