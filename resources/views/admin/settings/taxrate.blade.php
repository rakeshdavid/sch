@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Tax Rate</h3>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12 col-lg-6">
            
                <form action="{{ route('admin.taxrate.store') }}" method="POST" class="form-horizontal">
                    
                        <div class="form-group row">
                            <label class="col-sm-6 form-control-label" for="Video Tax Rate">Video Tax Rate %</label>
                            <div class="col-sm-6">
                            	<div class="animated-switch pull-left">
                                    <input type="text" name="video_tax" value="@if($video_tax) {{$video_tax->taxrate}}@endif" class="form-group form-control" required="required" />
                                </div>
                             </div>
                        </div>
                    	<div class="form-group row">
                            <label class="col-sm-6 form-control-label" for="Video Tax Rate">Audition Tax Rate %</label>
                            <div class="col-sm-6">
                            	<div class="animated-switch pull-left">
                                    <input type="text" name="audition_tax" value="@if($audition_tax) {{$audition_tax->taxrate}}@endif" class="form-group form-control" required="required" />
                                </div>
                             </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-6 form-control-label" for="Video Tax Rate">Challenge Tax Rate %</label>
                            <div class="col-sm-6">
                            	<div class="animated-switch pull-left">
                                    <input type="text" name="challenge_tax" value="@if($challenge_tax) {{$challenge_tax->taxrate}}@endif" class="form-group form-control" required="required" />
                                </div>
                             </div>
                        </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-warning m-r-10 m-b-10">
                                <i class="btn-icon fa fa-check"></i>Save
                            </button>
                        </div>
                    </div>
                </form>
            
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