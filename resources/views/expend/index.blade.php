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
                            <a class="nav-link active" href="{{ url('/expend') }}">Pengeluaran</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="card-title">Pengeluaran</h4>
                                    <button type="button" id="add-expend" class="btn btn-outline-primary btn-lg"
                                        data-toggle="modal" data-target="#modal-add-expend"><i class="fa fa-pencil"></i>
                                        Tambah Pengeluaran</button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="dataTable" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Deskripsi</th>
                                                        <th>Nominal</th>
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
    @includeIf('expend.modal-form')
@endsection
@section('page_script')
    <script>
        $("#modal-add-expend").on("hidden.bs.modal", function(e) {
            const reset_form = $('#form-data')[0];
            const reset_form_edit = $('#form_edit_data')[0];

            $(reset_form).removeClass('was-validated');
            $(reset_form_edit).removeClass('was-validated');

            $('#description').removeClass('was-validated');
            $('#description').removeClass('is-invalid');
            $('#description').removeClass('invalid-more');
        });

        $(document).ready(function() {
            const datatable = $('#dataTable');
            console.log(datatable.length);
            if (datatable.length) {
                const dt_table = datatable.dataTable({
                    responsive: true,
                    processing: true,
                    autoWidth: false,
                    ajax: "{{ url('/expend') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                        },
                        {
                            data: 'created_at',
                            name: 'Tanggal',
                        },
                        {
                            data: 'description',
                            name: 'Deskripsi',
                        },
                        {
                            data: 'nominal',
                            name: 'Nominal',
                        },
                        {
                            data: 'action',
                            name: 'action',
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
        })

        $(document).on('click', '#add-expend', function() {
            $('#modal-add-expend').modal('show');
            $('#modal-title').text('Tambah Pengeluaran');
            const idForm = $('form#form_edit_data').attr('id', 'form-data');
            const id = document.querySelector('#id');
            const description = document.querySelector('#description');
            const nominal = document.querySelector('#nominal');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');
            id.value = '';
            description.value = '';
            nominal.value = '';
            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-add-expend').modal('show');
            $('#modal-title').text('Edit Pengeluaran');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const expend_id = document.querySelector('#id');
            const description = document.querySelector('#description');
            const nominal = document.querySelector('#nominal');
            const idForm = $('form#form-data').attr('id', 'form_edit_data');

            await fetch(`/expend/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    console.log(response, response.data.description);
                    expend_id.value = id;
                    description.value = response.data.description;
                    nominal.value = response.data.nominal;
                });
            submitEdit();
        });

        $(document).on('click', '#archive', async function(event) {
            $('#modal-archive-expend').modal('show');
            const btnArchive = $('#submit-archive').attr('id', 'btnArchive');
            const id = $(this).data('id');
            const expend_id = document.querySelector('#id');
            const idForm = $('form#form-data-archive').attr('id', 'form_archive_data');

            await fetch(`/expend/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    console.log(response);
                    expend_id.value = id;
                });
            submitArchive();
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
                            description: request.get('description'),
                            nominal: request.get('nominal'),
                        };

                        const id = $('#id').val();

                        fetch(`/expend/${id}`, {
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

                                    const error = data.error.description ? true : false

                                    if (error) {
                                        $('#description').removeClass('was-validated');
                                        $('#description').addClass('is-invalid');
                                        $('#description').addClass('invalid-more');
                                    } else {
                                        $('#description').removeClass('is-invalid');
                                        $('#description').removeClass('invalid-more');
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
                                    $('#modal-add-expend').modal('hide');
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
                            description: request.get('description'),
                            nominal: request.get('nominal'),
                        };

                        fetch('/expend', {
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

                                    const error = data.error.description ? true : false

                                    if (error) {
                                        $('#description').removeClass('was-validated');
                                        $('#description').addClass('is-invalid');
                                        $('#description').addClass('invalid-more');
                                    } else {
                                        $('#description').removeClass('is-invalid');
                                        $('#description').removeClass('invalid-more');
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

                                    $('#modal-add-expend').modal('hide');
                                }
                            });
                    } else {
                        submitEdit();
                    }
                });
            });
        }
        // End  Function Create

        // Function Archive
        function submitArchive() {
            Array.prototype.filter.call($('#form_archive_data'), function(form) {
                $('#btnArchive').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }
                    form.classList.add('was-validated');
                    event.preventDefault();
                    const formArchiveData = document.querySelector('#form_archive_data');
                    if (formArchiveData) {
                        const request = new FormData(formArchiveData);

                        const data = {
                            _token: request.get('_token'),
                            note: request.get('note'),
                        };

                        const id = $('#id').val();

                        fetch(`/expend/archive/${id}`, {
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

                                    const error = data.error.note ? true : false

                                    if (error) {
                                        $('#note').removeClass('was-validated');
                                        $('#note').addClass('is-invalid');
                                        $('#note').addClass('invalid-more');
                                    } else {
                                        $('#note').removeClass('is-invalid');
                                        $('#note').removeClass('invalid-more');
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
                                    $('#modal-archive-expend').modal('hide');
                                }
                            });
                    } else {
                        submit();
                    }
                });
            });
        }
        // End Function Edit and Update
    </script>
@endsection
