@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if(count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{session('status')}}
                    </div>
                @endif
                <form action="/proposal" method="POST">
                    <div class="form-group">
                        <label for="description">Review</label>
                        <textarea class="form-control" rows="3" name="description" placeholder="Review">{{old('description', '')}}</textarea>
                    </div>
                    <input type="hidden" name="video_id" value="{{ $video_id }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-default">Add</button>
                </form>
            </div>
        </div>
    </div>
@endsection