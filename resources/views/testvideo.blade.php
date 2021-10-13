<form role="form" action="{{ url('thumbvideo') }}" method="post" id="payment-form" enctype= multipart/form-data>
{!! csrf_field() !!}
<input type="file" name="video_file" />
<input type="submit" name="submit" value="upload" />
</form>