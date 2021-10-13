@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Code Red</h3>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12">
            <h4 class="m-b-20">You can Reassign a different coach or refund the users</h4>
            <table id="users-table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Coach</th>
                    <th>Remedy action</th>
                    <th>Refund</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal HTML -->
    <div id="coachesModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5 class="modal-title">Not active coaches list</h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    {{--<button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/datatables/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.js"></script>
    <script>
        var table,
            successMsg = "{{ session()->has('success') ? session('success') : '' }}";
        $(document).ready(function () {
            if(successMsg){
                swal(successMsg);
            }

            table = $('#users-table').DataTable({
                "serverSide": true,
                "showInfo": true,
                "orderable": false,
                "bSort" : false,
                ajax: {
                    "url": "{!! route('admin.analytics.code-red-stats') !!}" + "?_token=" + '{{ csrf_token() }}'
                },

                "columns": [
                    // User data
                    {
                        "render": function (data) {
                            if(data.length > 0) {
                                var userData = data[0]['email'] + ' <a href="{{ url('users/edit')}}/' + data[0]['id'] + '"><i class="fa fa-edit"></i></a>';
                            }
                            return userData;
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    },
                    // Coach data
                    {
                        "render": function (data) {
                            var coachData = 'The coach whom has been assigned to this video does not exist. You can reassign another coach from the list.';
                            if(Object.keys(data).length > 0) {
                                coachData = data[0]['first_name'] + ' ' + data[0]['last_name'] + ' <a href="{{ url('coaches/edit')}}/' + data[0]['id'] + '"><i class="fa fa-edit"></i></a>';
                            }
                            return coachData;
                        },
                        "targets": 1,
                        "className": "text-left",
                        "width": "10%"
                    },
                    // Remedy
                    {
                        "render": function (data) {
                            var reassign = '<a href="#coachesModal" role="button" title="View available coaches" class="back-img" data-title="' + data['video_id'] + '" data-toggle="modal"><img src="/images/available-updates.png" alt="View available coaches"></a>';
                            return reassign;
                        },
                        "targets": 2,
                        "className": "text-center",
                        "width": "10%"
                    },
                    // Refund
                    {
                        "render": function (data) {
                            var refundButton = '<a href="javascript:;" class="refund-btn back-img" onclick="refundAction(' + data + ');" data-video_id="' + data + '"><img src="/images/transaction.png" alt="Refund"></a>';
                            return refundButton;
                        },
                        "targets": 3,
                        "className": "text-center",
                        "width": "10%"
                    }
                ]
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#coachesModal").on('show.bs.modal', function(event){
                // Get button that triggered the modal
                var button = $(event.relatedTarget);
                // Extract value from data-* attributes
                var titleData = button.data('title');

                $.ajax({
                    type: 'get',
                    url: '{{ route("admin.analytics.remedy-coaches") }}',
                    dataType: 'json',
                    beforeSend: function () {
                        $("#free-coach-table").remove();
                        $('.modal-body').append('<div class="spinner" style="text-align: center"><i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i></div>');
                    },
                    success: function(data){
                        $('.spinner').remove();

                        $('.modal-body').append('<table id="free-coach-table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">' +
                            '                <thead>' +
                            '                <tr>' +
                            '                    <th>Coach Name</th>' +
                            '                    <th>Coach Email</th>' +
                            '                    <th>Action</th>' +
                            '                </tr>' +
                            '                </thead>' +
                            '                <tbody></tbody>' +
                            '            </table>');
                        $.each(data, function (item, val) {
                            $('#free-coach-table tbody').append('<tr>' +
                                    '<th>' + val.first_name + ' ' + val.last_name + '</th>' +
                                    '<th>' + val.email + '</th>' +
                                    '<th>' +
                                        '<i role="button"  title="Pick this coach" class="fa fa-user-plus change-coach" data-coach_id="' + val.id + '" data-video_id="' + titleData + '"></i>' +
                                    '</th>' +
                                '</tr>');
                        });

                        $('.change-coach').on('click', function () {
                            //console.log($(this).data());
                            $.ajax({
                                type: 'post',
                                data: $(this).data(),
                                url: '{{ route("admin.analytics.remedy-coaches") }}',
                                beforeSend: function () {
                                    //$('.modal-body').append('<div class="spinner" style="text-align: center"><i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i></div>');
                                },
                                success: function (message) {
                                    $('#coachesModal .modal-body').html(message);
                                },
                                error: function () {
                                    alert("Can't find data... Page will be reloaded");
                                    //location.reload();
                                }
                            });
                        });
                    },
                    error: function () {
                        // alert("Oops something went wrong.");
                        // location.reload();
                    }
                });
            });
        });

        function refundAction(data) {
            var table = $('#users-table').DataTable();
            $.ajax({
                type: 'post',
                url: '{{ route("admin.analytics.refund") }}',
                data: '&video_id=' + data,
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (json) {
                    // console.log(json);
                    if(json.error){
                        swal("Error!", json.error, "error");
                    }

                    if(json.success){
                        swal("Success!", "The video was successfully submitted for refund!", "success");
                        table.row($(this).parents('tr')).remove().draw();
                    }
                },
                error: function (obj, err, message) {
                    console.log('obj: ' + obj);
                    console.log('err: ' + err);
                    console.log('message: ' + message);
                    alert(message);
                    location.reload();
                }
            });
        };
    </script>
@endsection