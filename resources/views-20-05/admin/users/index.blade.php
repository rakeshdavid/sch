@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Users List</h3>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12">
            <table id="users-table" class="table table-hover table-striped table-bordered" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Email</th>
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

            setTimeout(function () {
                table = $('#users-table').DataTable({
                    "serverSide": true,
                    "showInfo": true,
                    "orderable": false,
                    "bSort" : false,
                    ajax: {
                        "url": "{!! route('admin.users.data') !!}" + "?_token=" + '{{ csrf_token() }}'
                    },

                    "columns": [
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
                            "render": function (data, type, row, meta) {
                                var isTest = row[3],
                                    viewData = '';

                                if (isTest == 1) {
                                    viewData = row[2] + ' <span class="text-danger">Test user!</span>';
                                } else {
                                    viewData = row[2];
                                }

                                return viewData;
                            },
                            "targets": 0,
                            "className": "text-left",
                            "width": "10%"
                        },
                        // Action
                        {
                            "render": function (data, type, row, meta) {
                                var editButton = '<a href="{{ url('users/edit')}}/'+row[4]+'"><i class="fa fa-edit"></i></a>',
                                    deleteButton = '<a class="m-l-10 delete-user" href="javascript:;" title="Delete this user" onclick="deleteUser(' + row[4] + ')"><i class="fa fa-trash-o"></i></a>',
                                    testUserButton = '',
                                    loginButton = '<a class="m-l-10 login-as" title="Login as this user" href="{{ url('login-as/') }}/'+row[4]+'"><i class="fa fa-sign-in"></i></a>';

                                if(row[3] == 1) {
                                    testUserButton = '<a class="m-l-10" title="Test coach. Make real" href="javascript:;" onclick="changeTestUser('+row[4]+', '+row[3]+')">Test</a>';
                                } else {
                                    testUserButton = '<a class="m-l-10" title="Real coach. Make test" href="javascript:;" onclick="changeTestUser('+row[4]+', '+row[3]+')">Real</a>';
                                }

                                return editButton   + deleteButton + testUserButton;//+ loginButton + hideButton
                            },
                            "targets": 0,
                            "className": "text-left",
                            "width": "10%"
                        }
                    ]
                });
            },1000)

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

        function changeTestUser(userId, testOption) {
            swal({
                title: 'Confirm your action',
                html: (testOption == 0) ? 'Make this user test?' : 'Make this user real?',
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
                            url: '{!! route('admin.changeTestUser') !!}',
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
                    html: 'User status successfully updated!',
                    onClose: function() {

                    }
                })
            });
        }

    </script>
@endsection