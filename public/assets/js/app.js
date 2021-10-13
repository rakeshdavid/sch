$(function() {
   
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    
            
    getNotification();
});

function getNotification(){
    $.ajax({
        type: "GET",
        url: "/profile/get-notifications",
        success: function(data){
            var counter = 0;
            var ids = [];
            $.each( data, function( key, value ) {
                var notification_blk = $("#notification_blk");
                //var notification_tpl = $("#notification_tpl").clone();
                var button_text = value.video_url.indexOf("myreviews#") !== -1 ? "Go to video" : "Show review";

                var btnYT = '';
                if(value.status != 3) {
                    if (value.message.indexOf('Pending payment') == -1) {

                        btnYT = '<p><a class="review_link" href="' + value.video_url + '">\
                        <span class="label label-warning">' + button_text + '</span></a>\
                    </p>\
                    ';
                    }
                }
                var notification_tpl = '<div class="row bg-odd-color" id="notification_' + value.id + '">\
                                            <div class="col-xs-12 timeline timeline-warning">\
                                                <div class="p-10">\
                                                    <p class="notification_msg">' + value.message + '</p>\
                                                    '+btnYT+ '<p class="text-sm text-muted notification_date">' + value.created_at + '</p>\
                                                </div>\
                                            </div>\
                                        </div>';
                $(notification_tpl).appendTo(notification_blk);

                /*notification_tpl.removeAttr("hidden");
                notification_tpl.attr("id", "notification_" + value.id);
                notification_tpl.find(".notification_msg").html(value.message);
                notification_tpl.find(".notification_date").text(value.created_at);
                notification_tpl.find(".review_link").attr("href", "/review/" + value.video_id);*/
                counter++;
                ids.push(value.id);
            });
            $("#nitification_counter").text(counter);

            if(counter){
                $("#notification_tittle").attr("hidden", "hidden");
            }

            // class="right-sidebar-outer show-from-right"
            /*if(counter){
                $('#notification_btn').trigger('click');
            }*/
            
            $("#notification_btn").click(function(){
                $(this).toggleClass("viewed");
                $("#nitification_counter").text(0);
                $.ajax({
                    type: "GET",
                    url: "/profile/set-notifications",
                    data: {"ids" : ids},
                    dataType: "json"
                });
                if(!$(this).hasClass("viewed")){
                    $.each(ids, function(key, id){
                        $("#notification_" + id).remove(); 
                    });
                    $("#nitification_counter").text(0);

                        $("#notification_tittle").removeAttr("hidden");
                }
            });
        },
        dataType: "json"
    });
}

function approve_video() {

    $('#approve_video').on('click', function () {

        var that = $(this);
        //alert( that.attr('video_id') );
       $.ajax({
             type: "POST",
             url: "/approved-video",
             data: {video_id : that.attr('video_id')},
             beforeSend: function () {
                 $('#approve_video').off('click');
             },
             success: function(data){

                 if(data.error){
                     that.attr('disabled', true);
                     swal("Error!", data.msg, "error");
                 } else {
                     that.attr('disabled', true);
                     swal("Success!", data.msg, "success");
                 }
                 approve_video();

                 that.hide();

             },
             error: function () {

                 alert("Oops something happens.");
                 location.reload();
                 approve_video();

             },
             dataType: "json"
         });

    });
}

approve_video();


/*function approvedVideo(video_id) {
    alert(video_id);
}*/


