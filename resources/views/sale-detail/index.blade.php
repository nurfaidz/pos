@extends('layouts.master')
@section('page_styles')
    <style>
        .tampil-bayar {
            font-size: 5em;
            text-align: center;
            height: 100px;
        }

        .tampil-terbilang {
            padding: 10px;
            background: #f0f0f0;
        }

        .table-penjualan tbody tr:last-child {
            display: none;
        }

        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 3em;
                height: 70px;
                padding-top: 5px;
            }
        }
    </style>
@endsection
@section('main')
    <div class="content-wrapper">
        <div class="row">
            <div class="home-tab">
                <div class="tab-content tab-content-basic">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Transaksi</h4>

                                <form class="form-produk">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="product_code" class="col-lg-2">Kode Produk</label>
                                        <div class="col-lg-5">
                                            <div class="input-group">
                                                <input type="hidden" name="sale_id" id="sale_id"
                                                    value="{{ $sale_id }}">
                                                <input type="hidden" name="product_id" id="product_id">
                                                <input type="text" class="form-control" name="product_code"
                                                    id="product_code">
                                                <span class="input-group-btn">
                                                    <button onclick="viewProduct()" class="btn btn-info btn-flat"
                                                        type="button"><i class="fa fa-arrow-right"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="card-datatable table-rounded-outline">
                                    <table id="dataTable" class="table table-borderless nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Produk</th>
                                                <th>Nama Produk</th>
                                                <th>Harga</th>
                                                <th>Jumlah</th>
                                                <th>Diskon</th>
                                                <th>Subtotal</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="tampil-bayar bg-primary"></div>
                                        <div class="tampil-terbilang"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <form action="{{ route('transaction.save') }}" class="sale-form" method="post">
                                            @csrf
                                            <input type="hidden" name="sale_id" value="{{ $sale_id }}">
                                            <input type="hidden" name="total" id="total">
                                            <input type="hidden" name="total_item" id="total_item">
                                            <input type="hidden" name="bayar" id="bayar">
                                            <input type="hidden" name="member_id" id="member_id"
                                                value="{{ $memberSelected->member_id }}">

                                            <div class="form-group row">
                                                <label for="totalrp" class="col-lg-3 control-label">Total</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="totalrp" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="member_code" class="col-lg-3 control-label">Member</label>
                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="member_code"
                                                            value="{{ $memberSelected->member_code }}">
                                                        <span class="input-group-btn">
                                                            <button onclick="viewMember()" class="btn btn-info btn-flat"
                                                                type="button"><i class="fa fa-arrow-right"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="discount" class="col-lg-3 control-label">Diskon</label>
                                                <div class="col-lg-8">
                                                    <input type="number" name="discount" id="discount"
                                                        class="form-control"
                                                        value="{{ !empty($memberSelected->member_id) ? $diskon : 0 }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="bayar" class="col-lg-3 control-label">Bayar</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="diterima" class="col-lg-3 control-label">Diterima</label>
                                                <div class="col-lg-8">
                                                    <input type="number" id="diterima" class="form-control"
                                                        name="diterima" value="{{ $penjualan->accepted ?? 0 }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="kembali" class="col-lg-3 control-label">Kembali</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="kembali" name="kembali"
                                                        class="form-control" value="0" readonly>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="box-footer">
                                <button type="submit"
                                    class="btn btn-outline-primary pull-right btn-flat btn-lg btn-save"><i
                                        class="fa fa-floppy-o"></i> Simpan Transaksi</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('sale-detail.product')
    @includeIf('sale-detail.member')
@endsection
@section('page_script')
    <script>
        let table, table2;

        $(document).ready(function() {
            $('body').addClass('sidebar-collapse sidebar-icon-only');

            const table = $('#dataTable').dataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('transaction.data', $sale_id) }}',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            sortable: false,
                        },
                        {
                            data: 'product_code',
                            name: 'Kode Product',
                        },
                        {
                            data: 'product_name',
                            name: 'Nama Produk',
                        },
                        {
                            data: 'selling_price',
                            name: 'Harga Jual',
                        },
                        {
                            data: 'amount',
                            name: 'Jumlah',
                        },
                        {
                            data: 'discount',
                            name: 'Diskon',
                        },
                        {
                            data: 'subtotal',
                            name: 'Subtotal',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            searchable: false,
                            sortable: false,
                        },
                    ],
                    dom: 'Brt',
                    bSort: false,
                    paginate: false,
                    columnDefs: [{
                        targets: 7,
                        className: 'text-center',
                    }]
                })
                .on('draw.dt', function() {
                    loadForm($('#discount').val());
                    setTimeout(() => {
                        $('#diterima').trigger('input');
                    }, 300);
                });

            table2 = $('.table-produk').DataTable();
            table_member = $('.table-member').DataTable();
            $(document).on('input', '.quantity', function() {
                let id = $(this).data('id');
                let amount = parseInt($(this).val());

                if (amount < 1) {
                    $(this).val(1);
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        icon: 'error',
                        text: 'Jumlah tidak boleh kurang dari 1',
                        confirmButtonClass: 'btn btn-success',
                    });
                    return;
                }

                if (amount > 10000) {
                    $(this).val(10000);
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        icon: 'error',
                        text: 'Jumlah tidak boleh lebih dari 10000',
                        confirmButtonClass: 'btn btn-success',
                    });
                    return;
                }

                $.post(`{{ url('/transaction') }}/${id}`, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'put',
                        'amount': amount,
                    })
                    .done(response => {
                        $(this).on('mouseout', function() {
                            $('#dataTable').DataTable().ajax.reload(() => loadForm($(
                                '#discount').val()));
                        });
                    })
                    .fail(errors => {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            icon: 'error',
                            text: 'Tidak dapat menyimpan data',
                            confirmButtonClass: 'btn btn-success',
                        });
                        return;
                    });
            });

            $(document).on('input', '#discount', function() {
                if ($(this).val() == '') {
                    $(this).val(0).select();
                }

                loadForm($(this).val());
            });

            $('#diterima').on('input', function() {
                if ($(this).val() == '') {
                    $(this).val(0).select();
                }

                loadForm($('#discount').val(), $(this).val());
            }).focus(function() {
                $(this).select();
            });

            $('.btn-save').on('click', function() {
                $('.sale-form').submit();
            })

        });

        function viewProduct() {
            $('#modal-product').modal('show');
        }

        function hideProduct() {
            $('#modal-product').modal('hide');
        }

        function productSelect(id, kode) {
            $('#product_id').val(id);
            $('#product_code').val(kode);
            hideProduct();
            addProduct();
        }

        function addProduct() {
            $.post('{{ route('transaction.store') }}', $('.form-produk').serialize())
                .done((response) => {
                    $('#product_code').focus();
                    $('#dataTable').DataTable().ajax.reload(() => loadForm($('#discount').val()));
                })
                .fail((errors) => {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        icon: 'error',
                        text: 'Tidak dapat menyimpan data',
                        confirmButtonClass: 'btn btn-success',
                    });
                    return;
                })
        }

        function viewMember() {
            $('#modal-member').modal('show');
        }

        function selectMember(id, kode, diskon) {
            $('#member_id').val(id);
            $('#member_code').val(kode);
            $('#discount').val(diskon);
            loadForm($('#discount').val());
            $('#diterima').val(0).focus().select();
            hideMember();
        }

        function hideMember() {
            $('#modal-member').modal('hide');
        }

        function sweetConfirm(id) {
            event.preventDefault(); // prevent form submit
            const form = event.target.form; // storing the form
            swal({
                    title: "Apa kamu yakin?",
                    text: "Kamu tidak memilih menu ini?",
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

                        fetch(`/transaction/${id}`, {
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

        function loadForm(diskon = 0, diterima = 0) {
            $('#total').val($('.total').text());
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('/transaction/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('#bayarrp').val('Rp. ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Bayar: Rp. ' + response.bayarrp);
                    $('.tampil-terbilang').text(response.terbilang);

                    $('#kembali').val('Rp.' + response.kembalirp);
                    if ($('#diterima').val() != 0) {
                        $('.tampil-bayar').text('Kembali: Rp. ' + response.kembalirp);
                        $('.tampil-terbilang').text(response.kembali_terbilang);
                    }
                })
                .fail(errors => {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        icon: 'error',
                        text: 'Tidak dapat menampilkan data',
                        confirmButtonClass: 'btn btn-success',
                    });
                    return;
                })
        }
    </script>
@endsection
