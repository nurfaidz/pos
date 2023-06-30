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
                            <a class="nav-link active" href="{{ url('/setting') }}">Pengaturan</a>
                        </li>
                    </ul>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card card-setting">
                            <div class="card-body">
                                <h4 class="card-title">Pengaturan</h4>
                                <p class="card-description">
                                    Pengaturan layout
                                </p>
                                <form class="forms-sample form-setting" action="{{ route('setting.update') }}"
                                    method="post" data-toggle="validator" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="company_name" class="col-sm-3 col-form-label">Nama Kafe</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="company_name" name="company_name"
                                                placeholder="Nama Kafe">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="phone" class="col-sm-3 col-form-label">Telepon</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="phone" name="phone"
                                                placeholder="Telepon">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="address" class="col-sm-3 col-form-label">Alamat</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="address" name="address"
                                                placeholder="Alamat">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="path_logo" class="col-sm-3 col-form-label">Logo Kafe</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="path_logo" class="form-control" id="path_logo"
                                                onchange="preview('.tampil-logo', this.files[0])">
                                            <span class="help-block with-errors"></span>
                                            <br>
                                            <div class="tampil-logo"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="member_card_path" class="col-sm-3 col-form-label">Kartu Member</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="member_card_path" class="form-control"
                                                id="member_card_path"
                                                onchange="preview('.tampil-kartu-member', this.files[0], 300)">
                                            <span class="help-block with-errors"></span>
                                            <br>
                                            <div class="tampil-kartu-member"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="note_type" class="col-sm-3 col-form-label">Tipe Nota</label>
                                        <div class="col-sm-9">
                                            <select name="note_type" class="form-control" id="note_type" required>
                                                <option disabled>Pilih Tipe Nota</option>
                                                <option value="1">Nota Kecil</option>
                                                <option value="2">Nota Besar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button class="btn btn-outline-primary me-2 btn-lg float-right">Simpan
                                        Perubahan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_script')
    <script>
        $(function() {
            showData();

            $('.form-setting').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.ajax({
                            url: $('.form-setting').attr('action'),
                            type: $('.form-setting').attr('method'),
                            data: new FormData($('.form-setting')[0]),
                            async: false,
                            processData: false,
                            contentType: false
                        })
                        .done(response => {
                            setTimeout(() => {
                                $('.card-setting').fadeIn('slow');
                            }, 3000);
                            swal({
                                type: 'success',
                                title: 'Berhasil!',
                                icon: 'success',
                                text: 'Berhasil menyimpan data',
                                confirmButtonClass: 'btn btn-success'
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
                }
            });
        });

        function showData() {
            $.get('{{ route('setting.show') }}')
                .done(response => {
                    $('[name=company_name]').val(response.company_name);
                    $('[name=phone]').val(response.phone);
                    $('[name=address]').val(response.address);
                    $('[name=note_type]').val(response.note_type);
                    $('title').text(response.company_name + ' | Pengaturan');

                    let words = response.company_name.split(' ');
                    let word = '';
                    words.forEach(w => {
                        word += w.charAt(0);
                    });
                    $('.logo-mini').text(word);
                    $('.logo-lg').text(response.company_name);

                    $('.tampil-logo').html(`<img src="{{ url('/') }}${response.path_logo}" width="200">`);
                    $('.tampil-kartu-member').html(
                        `<img src="{{ url('/') }}/${response.member_card_path}" width="300">`);
                    $('[rel=icon]').attr('href', `{{ url('/') }}/${response.path_logo}`);
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
                });
        }

        $("#note_type").select2({
            width: '100%',
        });
    </script>
@endsection
