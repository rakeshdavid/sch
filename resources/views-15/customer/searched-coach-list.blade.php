@extends('layouts.app')

@section('content')
	<div class="row">
		@foreach($coaches as $coach)
			<div class="col-xs-12 col-lg-4">
				<div class="user-widget-10">
					<div class="row">
						<div class="col-xs-12">
							<a class="media-left media-middle">
								<img src="{{ $coach->avatar }}" class="media-object img-circle h-100 w-100">
							</a>
							<div class="media-body">
								<div class="p-10 m-t-5">
									<h5 class="text-bold color-white">{{ $coach->first_name }} {{ $coach->last_name }}</h5>
									<p class="m-b-0">
										<span class="color-white"></span>
									</p>
								</div>
								<div class="p-10 p-t-15">
									<p>
										<span>Genre: </span>
										<span class="color-dark">{{ $coach->genre }}</span>
									</p>
									<p>
										<span>Level: </span>
										<span class="color-dark">{{ $coach->level }}</span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row m-0 p-0 p-b-20 bg-white">
					<div class="col-xs-12">
						<div class="centered">
							<a href="/video/create" class="m-r-5 m-l-5 btn btn-success btn-rounded">Select this coach</a>
							<a href="/profile/{{ $coach->id }}" class="m-r-5 m-l-5 btn btn-danger btn-rounded">Profile</a>
						</div>
					</div>
				</div>
			</div>
		@endforeach

		{!! $coaches->links() !!}
	</div>
@endsection