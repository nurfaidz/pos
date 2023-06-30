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
                            <a class="nav-link active" href="{{ url('/sale') }}">Penjualan</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Daftar Penjualan</h4>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-datatable table-rounded-outline">
                                            <table id="dataTable" class="table table-borderless nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tanggal</th>
                                                        <th>Kode Member</th>
                                                        <th>Total Item</th>
                                                        <th>Total Harga</th>
                                                        <th>Diskon</th>
                                                        <th>Total Bayar</th>
                                                        <th>Kasir</th>
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
    @includeIf('sales.detail')
@endsection
@section('page_script')
    <script>
        let table, table2;

        $(document).ready(function() {

            const table = $('#dataTable').dataTable({
                responsive: true,
                processing: true,
                autoWidth: false,
                aLengthMenu: [
                    [5, 10, 15, -1],
                    [5, 10, 15, "All"]
                ],
                ajax: "{{ route('sale.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        sortable: false,
                    },
                    {
                        data: 'date',
                        name: 'Tanggal',
                    },
                    {
                        data: 'member_code',
                        name: 'Kode Member',
                    },
                    {
                        data: 'total_items',
                        name: 'Total Item',
                    },
                    {
                        data: 'total_price',
                        name: 'Total Harga',
                    },
                    {
                        data: 'discount',
                        name: 'Diskon',
                    },
                    {
                        data: 'pay',
                        name: 'Bayar',
                    },
                    {
                        data: 'kasir',
                        name: 'Kasir',
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

            table2 = $('#dataTable-detail').DataTable({
                processing: true,
                bSort: false,
                dom: 'Brt',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        sortable: false,
                    },
                    {
                        data: 'product_code',
                        name: 'Kode Produk',
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
                        data: 'subtotal',
                        name: 'Subtotal',
                    },
                ],
            })

        });

        function showDetail(url) {
            $('#modal-detail').modal('show');
            table2.ajax.url(url);
            $('#dataTable-detail').DataTable().ajax.reload();
        }

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

                        fetch(`/sale/${id}`, {
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
