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
                            <a class="nav-link active" href="{{ route('expend-archive') }}">Archive Pengeluaran</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content tab-content-basic">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="card-title">Archive Pengeluaran</h4>
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

    <div class="modal fade" id="modal-archive-expend" tabindex="-1" role="dialog" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Catatan</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="catatan">Keterangan<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatan" rows="100" readonly></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    autoWidth: false,
                    ajax: "{{ url('/archive-expend') }}",
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

        $(document).on('click', '#note', async function(event) {
            $('#modal-archive-expend').modal('show');
            const id = $(this).data('id');
            const expend_id = document.querySelector('#id');
            const catatan = document.querySelector('#catatan');
            const idForm = $('form#form-data-archive').attr('id', 'form_archive_data');

            await fetch(`/expend/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    console.log(response);
                    expend_id.value = id;
                    catatan.value = response.data.note;
                });
        });

        // Function Pulih
        function sweetConfirm(id) {
            event.preventDefault(); // prevent form submit
            const form = event.target.form; // storing the form
            swal({
                    title: "Apa kamu yakin?",
                    text: "Data ini akan dipulihkan!",
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
                        const request = new FormData(document.getElementById('form_pulih_data'));
                        const data = {
                            _token: request.get('_token'),
                        };

                        fetch(`/archive-expend/pulih/${id}`, {
                                method: 'PUT',
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
        // End Function Pulihkan
    </script>
@endsection
