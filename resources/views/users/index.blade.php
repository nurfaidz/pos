@extends('layouts.master')
@section('main')
    <div class="content-wrapper">
        <div class="row">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url('/user') }}">User</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="card-title">User</h4>
                                    <button type="button" id="add-user" class="btn btn-outline-primary btn-lg"
                                        data-toggle="modal" data-target="#modal-form"><i class="fa fa-pencil"></i>
                                        Tambah User</button>
                                    {{-- <button type="button" id="cetak" class="btn btn-outline-secondary btn-lg"><i
                                        class="fa fa-barcode"></i>
                                    Cetak Barcode</button> --}}
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-datatable table-rounded-outline">
                                            {{ csrf_field() }}
                                            <table class="table table-borderless nowrap" id="dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th>Email</th>
                                                        <th>Role</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('users.modal-form')
@endsection
@section('page_script')
    <script>
        $("#modal-form").on("hidden.bs.modal", function(e) {
            const reset_form = $('#form_data')[0];
            const reset_form_edit = $('#form_edit_data')[0];

            $(reset_form).removeClass('was-validated');
            $(reset_form_edit).removeClass('was-validated');

            $('#name').removeClass('was-validated');
            $('#name').removeClass('is-invalid');
            $('#name').removeClass('invalid-more');
        });

        $(document).ready(function() {
            const datatable = $('#dataTable');
            console.log(datatable.length);
            if (datatable.length) {
                const dt_table = datatable.dataTable({
                    responsive: true,
                    processing: true,
                    autoWidth: false,
                    aLengthMenu: [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    ajax: "{{ url('/user') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            sortable: false,
                        },
                        {
                            data: 'name',
                            name: 'Nama',
                        },
                        {
                            data: 'email',
                            name: 'Email',
                        },
                        {
                            data: 'role',
                            name: 'Role',
                        },
                        {
                            data: 'action',
                            name: 'Actions',
                            searchable: false,
                            sortable: false,
                        },
                    ],
                    columnDefs: [{
                        targets: 4,
                        className: 'text-center',
                    }]
                })
            }

        });

        $('select:not(#modal-form)').each(function() {
            $("#role").select2({
                width: '100%',
                dropdownParent: $('#role').parent()
            });
            $('#role').on('change', function(e) {
                let data = $('#role').select('val');
            });
        });

        $(document).on('click', '#add-user', function() {
            $('#modal-form').modal('show');
            $('#modal-title').text('Tambah User');
            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const id = document.querySelector('#id');
            const name = document.querySelector('#name');
            const email = document.querySelector('#email');
            const password = document.querySelector('#password');
            const role = document.querySelector('#role');
            id.value = '';
            name.value = '';
            email.value = '';
            password.value = '';
            role.value = '';
            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-form').modal('show');
            $('#modal-title').text('Edit User');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const user_id = document.querySelector('#id');
            const name = document.querySelector('#name');
            const email = document.querySelector('#email');
            const password = document.querySelector('#password');
            const role = document.querySelector('#role');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');

            await fetch(`/user/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    console.log(response, response.data.name);
                    user_id.value = id;
                    name.value = response.data.name;
                    email.value = response.data.email;
                    password.value = response.data.password;
                    let checkExist_interval = setInterval(() => {
                        if ($('#role option').length > 1) {
                            $('#role').val(response.data.role).trigger('change');
                            clearInterval(checkExist_interval);
                        }
                    }, 1000);
                });
            submitEdit();
        });

        // Function Edit and Update
        function submitEdit() {
            Array.prototype.filter.call($('#form_edit_data'), function(form) {
                $('#btnEdit').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }
                    form.classList.add('was-validated');
                    event.preventDefault();
                    const formEditData = document.querySelector('#form_edit_data');
                    if (formEditData) {
                        const request = new FormData(formEditData);

                        const data = {
                            _token: request.get('_token'),
                            name: request.get('name'),
                            email: request.get('email'),
                            password: request.get('password'),
                            role: request.get('role'),
                        };

                        const id = $('#id').val();

                        fetch(`/user/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    $.each(data.error, (prefix, val) => {
                                        $('div.' + prefix + '_error').text(val[0]);
                                    });

                                    const error = data.error.name ? true : false

                                    if (error) {
                                        $('#name').removeClass('was-validated');
                                        $('#name').addClass('is-invalid');
                                        $('#name').addClass('invalid-more');
                                    } else {
                                        $('#name').removeClass('is-invalid');
                                        $('#name').removeClass('invalid-more');
                                    }
                                } else {
                                    setTimeout(() => {
                                        $('#dataTable').DataTable().ajax.reload();
                                    }, 0);

                                    swal({
                                        type: 'success',
                                        title: 'Berhasil!',
                                        icon: 'success',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });
                                    $('#modal-form').modal('hide');
                                }
                            });
                    } else {
                        submit();
                    }
                });
            });
        }
        // End Function Edit and Update

        // Function Create
        function submit() {
            Array.prototype.filter.call($('#form_data'), function(form) {
                $('#submit').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();
                    const formData = document.querySelector('#form_data');
                    if (formData) {
                        const request = new FormData(formData);

                        const data = {
                            _token: request.get('_token'),
                            name: request.get('name'),
                            email: request.get('email'),
                            password: request.get('password'),
                            role: request.get('role'),
                        };

                        fetch('/user', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    $.each(data.error, (prefix, val) => {
                                        $('div.' + prefix + '_error').text(val[0]);
                                    });

                                    const error = data.error.id ? true : false

                                    if (error) {
                                        $('#name').removeClass('was-validated');
                                        $('#name').addClass('is-invalid');
                                        $('#name').addClass('invalid-more');
                                    } else {
                                        $('#name').removeClass('is-invalid');
                                        $('#name').removeClass('invalid-more');
                                    }
                                } else {
                                    setTimeout(() => {
                                        $('#dataTable').DataTable().ajax.reload();
                                    }, 0);

                                    swal({
                                        type: 'success',
                                        title: 'Berhasil!',
                                        icon: 'success',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-form').modal('hide');
                                }
                            });
                    } else {
                        submitEdit();
                    }
                });
            });
        }
        // End  Function Create

        // Function Delete
        function sweetConfirm(id) {
            event.preventDefault(); // prevent form submit
            const form = event.target.form; // storing the form
            swal({
                    title: "Apa kamu yakin?",
                    text: "Kamu tidak akan bisa mengembalikan data ini!",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "Batal",
                            value: null,
                            visible: true,
                            className: "btn btn-danger",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Yakin",
                            value: true,
                            visible: true,
                            className: "btn btn-primary",
                            closeModal: true
                        }
                    }
                })
                .then((result) => {
                    console.log(result)
                    if (result) {
                        const request = new FormData(document.getElementById('form_delete_data'));
                        const data = {
                            _token: request.get('_token'),
                        };

                        fetch(`/user/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                setTimeout(function() {
                                    $('#dataTable').DataTable().ajax.reload();
                                }, 0);

                                swal({
                                    type: 'success',
                                    title: 'Berhasil!',
                                    icon: 'success',
                                    text: data.message,
                                    confirmButtonClass: 'btn btn-success',
                                });

                                $('#modal-form').modal('hide');
                            })
                            .catch((error) => {
                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    icon: 'error',
                                    text: error.message,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    }
                });
        }
        // End Function Delete
    </script>
@endsection
