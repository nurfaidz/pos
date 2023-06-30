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
                            <a class="nav-link active" href="{{ url('/category') }}">Category</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="card-title">Kategori</h4>
                                    <button type="button" id="add-category" class="btn btn-outline-primary btn-lg"
                                        data-toggle="modal" data-target="#modal-add-category"><i class="fa fa-pencil"></i>
                                        Tambah Kategori</button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="dataTable" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Kategori</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
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

    <div class="modal fade" id="modal-add-category" tabindex="-1" role="dialog" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Tambah Kategori</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-data" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="category_name">Kategori<span class="text-danger">*</span></label>
                            <input type="text" name="category_name" id="category_name" class="form-control" required>
                            <div class="invalid-feedback category_name_error"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_script')
    <script>
        $("#modal-add-category").on("hidden.bs.modal", function(e) {
            const reset_form = $('#form-data')[0];
            const reset_form_edit = $('#form_edit_data')[0];

            $(reset_form).removeClass('was-validated');
            $(reset_form_edit).removeClass('was-validated');

            $('#category_name').removeClass('was-validated');
            $('#category_name').removeClass('is-invalid');
            $('#category_name').removeClass('invalid-more');
        });

        $(document).ready(function() {
            const datatable = $('#dataTable');
            console.log(datatable.length);
            if (datatable.length) {
                const dt_table = datatable.dataTable({
                    responsive: true,
                    processing: true,
                    autoWidth: false,
                    ajax: "{{ url('/category') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                        },
                        {
                            data: 'category_name',
                            name: 'category_name',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            searchable: false,
                            sortable: false,
                        },
                    ],
                    columnDefs: [{
                        targets: 2,
                        className: 'text-center',
                    }]
                })
            }
        })

        $(document).on('click', '#add-category', function() {
            $('#modal-add-category').modal('show');
            $('#modal-title').text('Tambah Kategori');
            const idForm = $('form#form_edit_data').attr('id', 'form-data');
            const id = document.querySelector('#id');
            const category_name = document.querySelector('#category_name');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');
            id.value = '';
            category_name.value = '';
            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-add-category').modal('show');
            $('#modal-title').text('Edit Kategori');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const categorie_id = document.querySelector('#id');
            const category_name = document.querySelector('#category_name');
            const idForm = $('form#form-data').attr('id', 'form_edit_data');

            await fetch(`/category/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    console.log(response, response.data.category_name);
                    categorie_id.value = id;
                    category_name.value = response.data.category_name;
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
                            category_name: request.get('category_name'),
                        };

                        const id = $('#id').val();

                        fetch(`/category/${id}`, {
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

                                    const error = data.error.category_name ? true : false

                                    if (error) {
                                        $('#category_name').removeClass('was-validated');
                                        $('#category_name').addClass('is-invalid');
                                        $('#category_name').addClass('invalid-more');
                                    } else {
                                        $('#category_name').removeClass('is-invalid');
                                        $('#category_name').removeClass('invalid-more');
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
                                    $('#modal-add-category').modal('hide');
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
            Array.prototype.filter.call($('#form-data'), function(form) {
                $('#submit').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();
                    const formData = document.querySelector('#form-data');
                    if (formData) {
                        const request = new FormData(formData);

                        const data = {
                            _token: request.get('_token'),
                            category_name: request.get('category_name'),
                        };

                        fetch('/category', {
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

                                    const error = data.error.category_name ? true : false

                                    if (error) {
                                        $('#category_name').removeClass('was-validated');
                                        $('#category_name').addClass('is-invalid');
                                        $('#category_name').addClass('invalid-more');
                                    } else {
                                        $('#category_name').removeClass('is-invalid');
                                        $('#category_name').removeClass('invalid-more');
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

                                    $('#modal-add-category').modal('hide');
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

                        fetch(`/category/${id}`, {
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

                                $('#modal-add-category').modal('hide');
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
