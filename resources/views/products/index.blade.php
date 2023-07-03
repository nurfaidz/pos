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
                            <a class="nav-link active" href="{{ url('/product') }}">Product</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Produk</h4>
                                <button type="button" id="add-product" class="btn btn-outline-primary btn-lg"
                                    data-toggle="modal" data-target="#modal-form"><i class="fa fa-pencil"></i>
                                    Tambah Produk</button>
                                <button onclick="checkBoxDelete('{{ route('product.delete_selected') }}')" type="button"
                                    id="delete-product" class="btn btn-outline-danger btn-lg"><i class="fa fa-trash"></i>
                                    Hapus Produk</button>
                                <div class="form-group col-4">
                                    <form action="" method="post">
                                        @method('get')
                                        @csrf
                                        <select name="status" id="status" class="select2 form-control"
                                            onchange="selectFunction()">
                                            <option hidden disabled selected value>Pilih Kategori</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->category_id }}"
                                                    {{ request('status') == $item->category_id ? 'selected' : '' }}>
                                                    {{ $item->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-datatable table-rounded-outline">
                                            <form action="" method="POST" class="product-form">
                                                @csrf
                                                <table id="dataTable" class="table table-borderless table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type="checkbox" name="select_all" id="select_all">
                                                            </th>
                                                            <th>No</th>
                                                            <th>Kode</th>
                                                            <th>Nama</th>
                                                            <th>Kategori</th>
                                                            <th>Harga Jual</th>
                                                            <th>Merk</th>
                                                            <th>Stok</th>
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
    @includeIf('products.modal-form')
@endsection
@section('page_script')
    <script>
        $("#modal-form").on("hidden.bs.modal", function(e) {
            const reset_form = $('#form_data')[0];
            const reset_form_edit = $('#form_edit_data')[0];

            $(reset_form).removeClass('was-validated');
            $(reset_form_edit).removeClass('was-validated');

            $('#product_name').removeClass('was-validated');
            $('#product_name').removeClass('is-invalid');
            $('#product_name').removeClass('invalid-more');
        });

        let status = 0;

        const dataTable_Ajax = (status) => {
            const datatable = $('#dataTable');
            console.log(datatable.length);
            if (datatable.length) {
                const dt_table = datatable.dataTable({
                    processing: true,
                    aLengthMenu: [
                        [5, 10, 15, -1],
                        [5, 10, 15, "All"]
                    ],
                    ajax: `/product/list-product?status=${status}`,
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
                            data: 'product_code',
                            name: 'Kode',
                        },
                        {
                            data: 'product_name',
                            name: 'Nama',
                        },
                        {
                            data: 'category_name',
                            name: 'Kategori',
                        },
                        {
                            data: 'price',
                            name: 'Harga Jual',
                        },
                        {
                            data: 'merk',
                            name: 'Merk',
                        },
                        {
                            data: 'stock',
                            name: 'Stok',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            searchable: false,
                            sortable: false,
                        },
                    ],
                    columnDefs: [{
                        targets: 8,
                        className: 'text-center',
                    }]
                });
            }

            $('[name=select_all]').on('click', function() {
                $(':checkbox').prop('checked', this.checked);
            });
        }

        dataTable_Ajax(status);

        $.ajax({
            url: "{{ url('product/select') }}",
            method: "GET",
            dataType: "json",
            success: function(result) {

                if ($('#category_id').data('select2')) {
                    $("#category_id").val("");
                    $("#category_id").trigger("change");
                    $('#category_id').empty().trigger("change");

                }

                $('select:not(#modal-form)').each(function() {
                    $("#category_id").select2({
                        data: result.data,
                        width: '100%',
                        dropdownParent: $('#category_id').parent()
                    });
                });

            }
        });

        /**
         *
         * function filter with select by position and division.
         *
         */

        const selectFunction = () => {
            status = $('#status').val();
            (status === null) ? status = 0: status;
            $("#dataTable").DataTable().destroy();
            dataTable_Ajax(status);
        };

        $(document).on('click', '#add-product', function() {
            $('#modal-form').modal('show');
            $('#modal-title').text('Tambah Produk');
            const idForm = $('form#form_edit_data').attr('id', 'form_data');
            const id = document.querySelector('#id');
            const category_id = document.querySelector('#category_id');
            const product_name = document.querySelector('#product_name');
            const merk = document.querySelector('#merk');
            const price = document.querySelector('#price');
            const stock = document.querySelector('#stock');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');
            id.value = '';
            product_name.value = '';
            category_id.value = '';
            merk.value = '';
            price.value = '';
            stock.value = '';
            submit();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-form').modal('show');
            $('#modal-title').text('Edit Produk');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const product_id = document.querySelector('#id');
            const category_id = document.querySelector('#category_id');
            const product_name = document.querySelector('#product_name');
            const merk = document.querySelector('#merk');
            const price = document.querySelector('#price');
            const stock = document.querySelector('#stock');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');

            await fetch(`/product/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    console.log(response);
                    product_id.value = id;
                    product_name.value = response.data.product_name;
                    merk.value = response.data.merk;
                    price.value = response.data.price;
                    stock.value = response.data.stock;
                    let checkExist_interval = setInterval(() => {
                        if ($('#category_id option').length > 1) {
                            $('#category_id').val(response.data.category_id).trigger('change');
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
                            category_id: request.get('category_id'),
                            product_name: request.get('product_name'),
                            merk: request.get('merk'),
                            price: request.get('price'),
                            stock: request.get('stock'),
                        };

                        const id = $('#id').val();

                        fetch(`/product/${id}`, {
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
                                        $('#product_name').removeClass('was-validated');
                                        $('#product_name').addClass('is-invalid');
                                        $('#product_name').addClass('invalid-more');
                                    } else {
                                        $('#product_name').removeClass('is-invalid');
                                        $('#product_name').removeClass('invalid-more');
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
                            category_id: request.get('category_id'),
                            product_name: request.get('product_name'),
                            merk: request.get('merk'),
                            price: request.get('price'),
                            stock: request.get('stock'),
                        };

                        fetch('/product', {
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
                                        $('#product_name').removeClass('was-validated');
                                        $('#product_name').addClass('is-invalid');
                                        $('#product_name').addClass('invalid-more');
                                    } else {
                                        $('#product_name').removeClass('is-invalid');
                                        $('#product_name').removeClass('invalid-more');
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

                        fetch(`/product/${id}`, {
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

        // Function Delete Check Box
        function checkBoxDelete(url) {
            if ($('input:checked').length > 1) {
                if (swal({
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
                    })) {

                    $.post(url, $('.product-form').serialize())
                        .done((response) => {
                            swal({
                                type: 'success',
                                title: 'Berhasil!',
                                icon: 'success',
                                text: 'Berhasil hapus kategori!',
                                confirmButtonClass: 'btn btn-success',
                            });
                            $('#dataTable').DataTable().ajax.reload();
                        }, 0)
                        .fail((errors) => {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                icon: 'error',
                                text: error.message,
                                confirmButtonClass: 'btn btn-success',
                            });
                            return;
                        });
                }
            } else {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    icon: 'error',
                    text: 'Pilih data yang akan dihapus!',
                    confirmButtonClass: 'btn btn-success',
                });
                return;
            }
        }

        $(document).ready(function() {
            $('#category_id').change(function() {
                let kategori = $('#category_id option:selected').text();
                if (kategori == "Barang") {
                    document.getElementById('stock').setAttribute('type', 'number');
                    document.getElementById('label-stock').style.display = "block";
                    document.getElementById('merk').setAttribute('type', 'text');
                    document.getElementById('label-merk').style.display = "block";
                } else {
                    document.getElementById('stock').setAttribute('type', 'hidden');
                    document.getElementById('label-stock').style.display = "none";
                    document.getElementById('merk').setAttribute('type', 'hidden');
                    document.getElementById('label-merk').style.display = "none";
                }
            });
        });

        $(document).ready(() => {
            $('#status').select2();
        });
    </script>
@endsection
