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
                            <a class="nav-link active" href="{{ url('/member') }}">Member</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Member</h4>
                                <button type="button" id="add-member" class="btn btn-outline-primary btn-lg"
                                    data-toggle="modal" data-target="#modal-form"><i class="fa fa-pencil"></i>
                                    Tambah Member</button>
                                <button type="button" id="cetak" class="btn btn-outline-secondary btn-lg"
                                    onclick="cetakMember('{{ route('member.cetak-member') }}')"><i
                                        class="fa fa-barcode"></i>
                                    Cetak Kartu Member</button>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-datatable table-rounded-outline">
                                            <form action="" method="POST" class="product-form">
                                                {{ csrf_field() }}
                                                <table class="table table-borderless nowrap" id="dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type="checkbox" name="select_all" id="select_all">
                                                            </th>
                                                            <th>No</th>
                                                            <th>Kode Member</th>
                                                            <th>Nama</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </form>
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
    @includeIf('members.modal-form')
@endsection
@section('page_script')
    <script>
        $("#modal-form").on("hidden.bs.modal", function(e) {
            const reset_form = $('#form_data')[0];
            const reset_form_edit = $('#form_edit_data')[0];

            $(reset_form).removeClass('was-validated');
            $(reset_form_edit).removeClass('was-validated');

            $('#member_name').removeClass('was-validated');
            $('#member_name').removeClass('is-invalid');
            $('#member_name').removeClass('invalid-more');
        });

        $(document).ready(function() {
            $('select:not(#modal-form)').each(function() {
                $(this).select2({
                    width: '100%',
                    dropdownParent: $(this).parent()
                });
            });
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
                    ajax: "{{ url('/member') }}",
                    columns: [{
                            data: 'select_all',
                            searchable: false,
                            sortable: false,
                        },
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            sortable: false,
                        },
                        {
                            data: 'member_code',
                            name: 'Kode member',
                        },
                        {
                            data: 'member_name',
                            name: 'Nama',
                        },
                        {
                            data: (members) => {
                                if (members.status == 'Member') {
                                    return '<span class="badge badge-pill badge-success">Member</span>';
                                } else {
                                    return '<span class="badge badge-pill badge-warning">Unmember</span>';
                                }
                            },
                            name: 'Status'
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

            $('[name=select_all]').on('click', function() {
                $(':checkbox').prop('checked', this.checked);
            });
        });

        $(document).on('click', '#add-member', function() {
            $('#modal-form').modal('show');
            $('#modal-title').text('Tambah Member');
            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const id = document.querySelector('#id');
            const member_name = document.querySelector('#member_name');
            const phone = document.querySelector('#phone');
            const email = document.querySelector('#email');
            const status = document.querySelector('#status');
            id.value = '';
            member_name.value = '';
            phone.value = '';
            email.value = '';
            status.value = '';
            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-form').modal('show');
            $('#modal-title').text('Edit Member');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const member_id = document.querySelector('#id');
            const member_name = document.querySelector('#member_name');
            const phone = document.querySelector('#phone');
            const email = document.querySelector('#email');
            const status = document.querySelector('#status');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');

            await fetch(`/member/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    console.log(response);
                    member_id.value = id;
                    member_name.value = response.data.member_name;
                    phone.value = response.data.phone;
                    email.value = response.data.email;
                    status.value = response.data.status;
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
                            member_name: request.get('member_name'),
                            phone: request.get('phone'),
                            email: request.get('email'),
                            status: request.get('status'),
                        };

                        const id = $('#id').val();

                        fetch(`/member/${id}`, {
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

                                    const error = data.error.product_name ? true : false

                                    if (error) {
                                        $('#member_name').removeClass('was-validated');
                                        $('#member_name').addClass('is-invalid');
                                        $('#member_name').addClass('invalid-more');
                                    } else {
                                        $('#member_name').removeClass('is-invalid');
                                        $('#member_name').removeClass('invalid-more');
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
                            member_name: request.get('member_name'),
                            phone: request.get('phone'),
                            email: request.get('email'),
                            status: request.get('status'),
                        };

                        fetch('/member', {
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

                                    const error = data.error.product_code ? true : false

                                    if (error) {
                                        $('#member_name').removeClass('was-validated');
                                        $('#member_name').addClass('is-invalid');
                                        $('#member_name').addClass('invalid-more');
                                    } else {
                                        $('#member_name').removeClass('is-invalid');
                                        $('#member_name').removeClass('invalid-more');
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

                        fetch(`/member/${id}`, {
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

        function cetakMember(url) {
            if ($('input:checked').length < 1) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    icon: 'error',
                    text: 'Pilih data yang akan dicetak!',
                    confirmButtonClass: 'btn btn-success',
                });
                return;
            } else {
                $('.product-form')
                    .attr('target', '_blank')
                    .attr('action', url)
                    .submit();
            }
        }
    </script>
@endsection
