@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Active Users List</h3>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12">
            <table id="users-table" class="table table-hover table-striped table-bordered" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>User id</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
                    <th>Coach emails</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
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
                    "url": "{!! route('admin.analytics.active-users-table') !!}" + "?_token=" + '{{ csrf_token() }}'
                },

                "columns": [
                    // User id
                    {
                        "render": function (data) {
                            return data;
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%",
                        "visible": false
                    },
                    // First name
                    {
                        "render": function (data) {
                            return data;
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    },
                    // Last name
                    {
                        "render": function (data) {
                            return data;
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    },
                    // Email
                    {
                        "render": function (data) {
                            return data;
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    },
                    // Coach emails
                    {
                        "render": function (data) {
                            var coachEmail = '<p class="text-danger">The coach was deleted!</a>';
                            if (Object.keys(data).length > 0) {
                                coachEmail = '<a href="{{ url('/profile') }}/' + data[0].coach_id + '" title="View this coach" >' + data[0].email + '</a>';
                            }
                            return coachEmail;
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    },
                    // Action
                    {
                        "render": function (data, type, row, meta) {
                            return '<a href="{{ url('users/edit') }}/'+row[0]+'"><i class="fa fa-edit"></i></a>';
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    }
                ]
            });
        });

        function deleteUser(userId) {
            swal({
                title: 'Confirm your action',
                html: 'Delete this user?',
                showCancelButton: true,
                confirmButtonText: 'Ok',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'confirm',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                preConfirm: function () {
                    return new Promise(function (resolve, reject) {
                        var formData = new FormData();
                        formData.append('userId', userId);
                        $.ajax({
                            data: formData,
                            url: '{{ url('users/delete') }}/' + userId,
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            success: function (response) {
                                if (response.success) {
                                    resolve();
                                    table.ajax.reload(null, false);
                                } else {
                                    if (response.error) {
                                        reject('Oops! Something went wrong. Please, reload the page.');
                                    }
                                }
                            },
                            error: function (response) {
                                if(response.error) {
                                    reject(response.responseJSON.error);
                                }
                            }
                        });
                    });
                }
            }).then(function () {
                swal({
                    type: 'success',
                    title: 'Success!',
                    html: 'User was deleted!',
                    onClose: function() {

                    }
                })
            });
        }

    </script>
@endsection