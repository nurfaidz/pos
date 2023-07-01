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
                            <a class="nav-link active" href="{{ url('/report') }}">Laporan</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Laporan</h4>
                                <a href="{{ route('report.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}"
                                    class="btn btn-outline-secondary btn-lg"><i class="fa fa-file-excel-o"></i>
                                    Export</a>
                                <button type="button" id="add-member" class="btn btn-outline-primary btn-lg"
                                    onclick="updatePeriod()"><i class="fa fa-pencil"></i>
                                    Ubah Periode</button>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-datatable table-rounded-outline">
                                            <form action="" method="POST" class="product-form">
                                                {{ csrf_field() }}
                                                <table class="table table-borderless nowrap" id="dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Tanggal</th>
                                                            <th>Penjualan</th>
                                                            <th>Pendapatan</th>
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
    @includeIf('reports.modal-form')
@endsection
@section('page_script')
    <script>
        $(document).ready(function() {
            const datatable = $('#dataTable');
            console.log(datatable.length);
            if (datatable.length) {
                const dt_table = datatable.dataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{{ route('report.data', [$tanggalAwal, $tanggalAkhir]) }}',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            sortable: false,
                        },
                        {
                            data: 'tanggal',
                            name: 'Tanggal',
                        },
                        {
                            data: 'penjualan',
                            name: 'Penjualan',
                        },
                        {
                            data: 'pendapatan',
                            name: 'Pendapatan',
                        },
                    ],
                    dom: 'Brt',
                    bSort: false,
                    bPaginate: false,
                });
            }

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });

        function updatePeriod() {
            $('#modal-form').modal('show');
        }
    </script>
@endsection
