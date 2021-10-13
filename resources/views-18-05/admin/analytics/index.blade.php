@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Admin dashboard</h3>
        </div>
    </div>

    <div class="row m-b-40">
        <div class="col-xs-12 col-lg-3 text-center" data-palette="palette-7">
            <div class="text-widget-1">
                <a href="{{ route('admin.users.index') }}">
                    <h5>Total SCH Users</h5>
                    <div>{{ $totalUsers }}</div>
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-lg-3 text-center" data-palette="palette-8">
            <div class="text-widget-1">
                <a href="{{ route('admin.coaches.index') }}">
                    <h5>Total SCH Coaches</h5>
                    <div>{{ $totalCoaches }}</div>
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-lg-3 text-center" data-palette="palette-9">
            <div class="text-widget-1">
                <a href="{{ route('admin.analytics.active-users') }}">
                    <h5>Active Users</h5>
                    <div>{{ $activeUsers }}</div>
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-lg-3 text-center" data-palette="palette-10">
            <div class="text-widget-1">
                <a href="{{ route('admin.analytics.active-coaches') }}">
                    <h5>Active Coaches</h5>
                    <div>{{ $activeCoaches }}</div>
                </a>
            </div>
        </div>
    </div>

    <div class="row m-b-40">
        <div class="col-xs-12 text-center">
            <h4>Code red status</h4>
        </div>

        <div class="col-xs-12 col-lg-4 text-center" data-palette="palette-16">
            <div class="text-widget-7 bg-warning-600 color-white">
                <div>In 3 days</div>
                <div>(On Track)</div>
                <div>{{ $in3Days - $timeIsUp }}</div>
            </div>
        </div>
        <div class="col-xs-12 col-lg-4 text-center" data-palette="palette-19">
            <div class="text-widget-7 bg-warning-900 color-white">
                <div>In 1 day</div>
                <div>(Risk of Code Red)</div>
                <div>{{ $in1Day - $timeIsUp }}</div>
            </div>
        </div>
        <div class="col-xs-12 col-lg-4 text-center" data-palette="palette-18">
            <div class="text-widget-7 bg-danger color-white">
                <a href="{{ route('admin.analytics.code-red') }}">
                    <div>Code Red</div>
                    <div>(Remedy or Refund)</div>
                    <div>{{ $timeIsUp }}</div>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/datatables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.js"></script>
@endsection