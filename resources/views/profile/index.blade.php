@if($user->role == 1)
  @include('profile.userprofile')
@else
  @include('profile.adminprofile')
@endif