@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/js/datatables/datatables.min.css">
@endsection
@section('content')
    <div class="row m-b-20">
        <div class="col-xs-12">
            <h3> Invites List </h3>
        </div>
    </div>

    <div class="row m-b-40">
        <div class="col-xs-12 col-lg-6">
            <div class="block">
                <h4 class="m-b-20">Create invite</h4>
                <p class="m-b-20">Please enter email address to send invite link for coach register</p>
                <form name="form" novalidate="" method="POST" action="{{ route('admin.coaches.createInvite') }}" id="inviteForm">
                    <div class="row">
                        <div class="col-xs-12 col-lg-6">
                            <div class="form-group floating-labels is-empty">
                                <label for="email">Email</label>
                                <input id="email" autocomplete="off" type="email" name="email">
                                <p class="error-block hidden"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <input type="submit" class="btn btn-lg btn-danger" value="Create invite">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row m-b-20">
        <div class="col-xs-12">
            <table id="coaches-table" class="table table-hover table-striped table-bordered" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>URL</th>
                    <th>Email</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/datatables/datatables.min.js"></script>
    <script src="/assets/marino/scripts/components/floating-labels.js"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.4/sweetalert2.min.js"></script>
    <script>
        var successMsg = "{{ session()->has('success') ? session('success') : '' }}",
            table;
        $(document).ready(function () {
            var email = $('#email');
            email.floatingLabels({
                errorBlock: 'Please enter your email',
                isEmailValid: 'Please enter a valid email'
            });

            if(successMsg){
                swal(successMsg);
            }
            table = $('#coaches-table').DataTable({
                "serverSide": true,
                "showInfo": true,
                "orderable": false,
                "bSort" : false,
                ajax: {
                    "url": "{!! route('admin.coaches.invitesData') !!}" + "?_token=" + '{{ csrf_token() }}'
                },

                "columns": [
                    // URL
                    {
                        "render": function (data) {
                            return data;
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    },
                    // email
                    {
                        "render": function (data) {
                            return data;
                        },
                        "targets": 0,
                        "className": "text-left",
                        "width": "10%"
                    },
                    // Created at
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
                            return '<a href="javascript:;" title="Удалить" onclick="deleteInvite('+data+')"' + '><i class="fa fa-trash fa-lg fa-fw" aria-hidden="true"></i></>'
                        },
                        "targets": 0,
                        "className": "text-center",
                        "width": "5%"
                    },
                ]
            });

            $("form#inviteForm").submit(function(e) {
                var form = $(this);
                form.find('div.form-control-error-list').remove();
                form.removeClass('has-danger');
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            swal({
                                title: 'Success!',
                                text: 'New invite link successfully created',
                                type: 'success',
                                confirmButtonText: 'Ok'
                            });
                            form.find("input[type=text], input[type=email]").val("");
                            table.ajax.reload(null, false);
                        } else {
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    form.find("input[name="+key+"]").parent().find('p.error-block').text(value).removeClass('hidden');
                                });
                            }
                        }
                    }
                });
                e.preventDefault();
            });

        });

        function deleteInvite(inviteId) {
            swal({
                title: 'Confirm your action',
                html: 'Delete invite?',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'confirm',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                preConfirm: function () {
                    return new Promise(function (resolve, reject) {
                        var formData = new FormData();
                        formData.append('inviteId', inviteId);
                        //formData.append("_token", csrfToken);
                        $.ajax({
                            data: formData,
                            url: '{!! route('admin.coaches.deleteInvite') !!}',
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
                                if (response.error) {
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
                    html: 'Invite link successfully deleted!'
                })
            });
        }

    </script>
@endsection