@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Settings List</h3>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12 col-lg-4">
            @if(count($settings))
                <form action="{{ route('admin.settings.store') }}" method="POST" class="form-horizontal">
                    @foreach($settings as $setting)
                        <div class="form-group row">
                            <label class="col-sm-6 form-control-label" for="{{ $setting->key }}">{{ $setting->name }}</label>
                            <div class="col-sm-6">
                                @if($setting->type == 'checkbox')
                                    <div class="animated-switch pull-left">
                                        <input id="{{ $setting->key }}" name="{{ $setting->key }}" type="checkbox" value="1" {{ $setting->value ? 'checked' : '' }}>
                                        <label for="{{ $setting->key }}" class="label-success"></label>
                                    </div>
                                @endif

                                @if($setting->type == 'input')
                                    <input type="text" maxlength="254" class="form-control" name="{{ $setting->key }}" autocomplete="off" value="{{ $setting->value }}">
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-warning m-r-10 m-b-10">
                                <i class="btn-icon fa fa-check"></i>Save
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.js"></script>
    <script>
        var table,
            successMsg = "{{ session()->has('success') ? session('success') : '' }}",
            errorMsg = "{{ session()->has('error') ? session('error') : '' }}";
        $(document).ready(function () {
            if(successMsg) {
                swal({
                    type: 'success',
                    title: 'Success',
                    text: successMsg,
                })
            }

            if(errorMsg) {
                swal({
                    type: 'error',
                    title: 'Error',
                    text: errorMsg,
                })
            }
        });
    </script>
@endsection