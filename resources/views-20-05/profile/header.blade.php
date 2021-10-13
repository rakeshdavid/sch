<div class="user-profile" {{--style="background-image: url(/images/user_profile.jpeg)"--}}>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="media">
                    <a class="media-left">
                        <img class="media-object img-circle h-100 w-100" src="{{$user->avatar}}">
                    </a>
                    <div class="media-body">
                        <h5 class="color-white media-heading"> {{$user->first_name}} {{$user->last_name}} </h5>
                        <p class="text-muted m-b-5 color-white">
                            <span class="m-r-10 color-white">{{$user->email}}</span> </p>
                        </p>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>