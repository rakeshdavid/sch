@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3>Active Coaches List</h3>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12">
            <table id="coaches-table" class="table table-hover table-striped table-bordered" cellspacing="0"
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
            successMsg = "{{ session()->has('success') ? session('success') : '' }}",
            errorLoginUser = "{{ session()->has('user') ? session('user') : '' }}";
        $(document).ready(function () {
            if(successMsg){
                swal(successMsg);
            }

            if(errorLoginUser){
                swal(
                    'Error!',
                    errorLoginUser,
                    'error'
                )
            }

            table = $('#coaches-table').DataTable({
                "serverSide": true,
                "showInfo": true,
                "orderable": false,
                "bSort" : false,
                ajax: {
                    "url": "{!! route('admin.analytics.active-coaches-table') !!}" + "?_token=" + '{{ csrf_token() }}'
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
                        "render": function (data) {
                            return data;
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    },
                    // Action
                    {
                        "render": function (data) {
                            var splitted = data.toString().split("::"),
                                hideButton = "",
                                loginButton = "",
                                editButton = '<a href="{{ url('coaches/edit')}}/'+splitted[0]+'" title="Edit this coach"><i class="fa fa-edit"></i></a>',
                                deleteButton = '<a class="m-l-10 delete-user" href="javascript:;" title="Delete this coach" onclick="deleteUser(' + splitted[0] + ')"><i class="fa fa-trash-o"></i></a>';

                            if(splitted[1] == 1) {
                                hideButton = '<a class="m-l-10" title="Hidden coach. Make visible" href="javascript:;" onclick="changeHiddenUser('+splitted[0]+', '+splitted[1]+')"><i class="fa fa-eye-slash"></i></a>';
                            } else {
                                hideButton = '<a class="m-l-10" title="Visible coach. Make hidden" href="javascript:;" onclick="changeHiddenUser('+splitted[0]+', '+splitted[1]+')"><i class="fa fa-eye"></i></a>';
                            }

                            loginButton = '<a class="m-l-10 login-as" title="Login as this user" href="{{ url('login-as/') }}/'+splitted[0]+'"><i class="fa fa-sign-in"></i></a>';

                            return editButton + hideButton  + deleteButton;//+ loginButton
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    }
                ]
            });

            $('#coaches-table tbody').on('click', 'a.login-as', function (e) {
                e.preventDefault();
                var link = $(this).attr('href');
                swal({
                    title: 'Confirm your action',
                    text: "Login as this user?",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Login'
                }).then(function () {
                    window.location.href = link;
                });
            });

        });

        function changeHiddenUser(userId, hiddenOption) {
            swal({
                title: 'Confirm your action',
                html: (hiddenOption == 0) ? 'Make hidden?' : 'Make visible?',
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
                            url: '{!! route('admin.changeHiddenUser') !!}',
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

        function deleteUser(userId) {
            swal({
                title: 'Confirm your action',
                html: 'Delete this coach?',
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
                            url: '{{ url('coaches/delete') }}/' + userId,
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
                    html: 'Coach was deleted!',
                    onClose: function() {

                    }
                })
            });
        }

    </script>
@endsection