@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/{{$video->url}}" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
@endsection