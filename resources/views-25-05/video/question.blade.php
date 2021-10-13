<form method="post" action="" id="ask-question">
	{!! csrf_field() !!}
	<div class="row">
    	<div class="col-lg-12">
            <input type="text" name="question-title" class="form-control" placeholder="Ask your question..." value="{{$filter['name']}}">
        </div>
        <div class="col-lg-12">

        </div>
    </div>
</form>