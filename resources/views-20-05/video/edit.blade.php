@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Url</label>
                        <input type="text" class="form-control" name="url" placeholder="Url">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Description</label>
                        <textarea class="form-control" rows="3" name="description" placeholder="Description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-default">Add</button>
                </form>
            </div>
        </div>
    </div>
@endsection