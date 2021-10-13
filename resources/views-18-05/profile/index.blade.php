@extends('layouts.user')

@section('content')
<div class="main-content profile-wrap">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-12">
              @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
              @endif
                <form action="/user-profile/{{$user->id}}" method="post" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="user-profile">
                    <div class="media">
                     <div class="img-box">
                        @if($user->avatar)
                        <img class="img-fluid rounded" src="{{$user->avatar}}" alt="michelle-barber">
                        @endif
                    </div>
                    <div class="media-body">
                        <div class="info-box">
                            <a href="javascript:void(0)" class="user-name">
                                <h3>{{$user->first_name}}</h3>
                                <input type="text" name="first_name" class="form-control" placeholder="{{$user->first_name}} @if($user->first_name){{$user->first_name}}@endif" value="{{$user->first_name}} ">
                                <div class="edit"><i class="fas fa-edit"></i></div></a>
                          
                            <div class="experience">
                            @php
                                $performance_levels = "";
                                if($user->performance_levels->count() > 0) {
                                    $performance_levels = implode("/ ", $user->performance_levels->lists("name")->all());
                                }
                            @endphp
                            {{ $performance_levels }}
                            </div> 
                             @php
                                $activity_genres = "";
                                if($user->activity_genres->count() > 0) {
                                    $activity_genres = implode(", ", $user->activity_genres->lists("name")->all());
                                }
                            @endphp
                            <div class="dance-style-list">
                               
                                <input type="text" name="activities" value="{{$user->activities}}" data-role="tagsinput" class="form-control" />
                            </div>
                        </div>
                    </div>
                 </div>
                    <a href="javascript:void(0)" class="description">
                         <p>{{ strip_tags($user->about) }}</p>
<textarea class="form-control" name="about" >{{ strip_tags($user->about) }}</textarea>
                        <div class="edit"><i class="fas fa-edit"></i></div>
                    </a>
                  <div class="user-details">
                    <div class="form-row">
                        <div class="form-group col-sm-6 ">
                          <div class="icon-box"><img src="/assets/img/Calendar.png" alt="" class="img-fluid"></div>
                            <div class="right-box">
                               <label>Birth date</label>
                                 <input type="text" class="form-control hasDatepicker" placeholder="{{$user->birthday}}" name="birthday" id="birthday" value="{{$user->birthday}}">
                            </div>
                          </div>
                        <div class="form-group col-sm-6 location">
                              <div class="icon-box"><img src="/assets/img/Place.png" alt="" class="img-fluid"></div>
                                <div class="right-box ">
                                    <label>Location</label><br/>
                                    <a href="javascript:void(0)" class="user-location">
                                        <h3>{{ $user->location }} </h3>
                                        <input type="text" name="location" class="form-control" autocomplete="off" value="{{ $user->location }}" >
                                        <div class="edit"><i class="fas fa-edit"></i></div>
                                    </a>
                                </div>       
                          </div> 
                     </div>
                     <div class="form-row">
                        <div class="form-group col-sm-6">
                           <div class="icon-box"><img src="/assets/img/Phone.png" alt="" class="img-fluid"></div>
                            <div class="right-box">
                               <label>Phone Number</label>
                                <input type="text" value="{{$user->phone}}" class="form-control" placeholder="Add Number…" name="phone" autocomplete="off" />
                            </div>
                          </div>
                        <div class="form-group col-sm-6">
                            <div class="icon-box"><img src="/assets/img/Web.png" alt="" class="img-fluid"></div>
                            <div class="right-box">
                               <label>Website</label>
                                <input type="url" name="wevsites" value="{{$user->wevsites}}" class="form-control"  placeholder="Add Website…">
                            </div>
                          </div> 
                     </div>
                 </div>
                    <div class="submit-btn text-center">
                        <input type="submit" name="profile-update" class="btn btn-outline-danger" value="Save" />
                        <a href="#" class="cancel">CANCEL</a>
                    </div>
                </div>
                </form>
               
            </div>
        </div>


    </div>
</div>
@endsection
@section('js')
    <script>


        // datepicker
    var dateformat = 'mm.dd.yyyy';

        $('.hasDatepicker').datepicker({
          format: dateformat,
          autoclose: true
        });
    function updateDateFormat(i,elem) {
        var d = $(elem).datepicker('getDate');
      
        $(elem).datepicker('destroy');  
        $(elem).datepicker({
            autoclose: true,
            format: dateformat
          });
        $(elem).datepicker('setDate', d);
        
    };
    </script>

@endsection