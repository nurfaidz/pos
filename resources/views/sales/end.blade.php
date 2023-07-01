@extends('layouts.master')
@section('main')
    <div class="content-wrapper">
        <div class="row">
            <div class="home-tab">
                <div class="tab-content tab-content-basic">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Cetak Nota</h4>
                                <div class="alert alert-success alert-dismissible">
                                    <i class="fa fa-check icon"></i>
                                    Data Transaksi telah selesai.
                                </div>
                            </div>
                            <div class="card-footer">
                                @if ($setting->note_type == 1)
                                    <button class="btn btn-outline-warning btn-lg"
                                        onclick="notaKecil('{{ route('transaction.small-note') }}', 'Nota Kecil')">Cetak
                                        Ulang
                                        Nota</button>
                                @else
                                    <button class="btn btn-outline-warning btn-lg"
                                        onclick="notaBesar('{{ route('transaction.big-note') }}', 'Nota PDF')">Cetak Ulang
                                        Nota</button>
                                @endif
                                <a href="{{ route('transaction.new') }}" class="btn btn-outline-primary btn-lg">Transaksi
                                    Baru</a>
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
        // tambahkan untuk delete cookie innerHeight terlebih dahulu
        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

        function notaKecil(url, title) {
            popupCenter(url, title, 625, 500);
        }

        function notaBesar(url, title) {
            popupCenter(url, title, 900, 675);
        }

        function popupCenter(url, title, w, h) {
            const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
            const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

            const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document
                .documentElement.clientWidth : screen.width;
            const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document
                .documentElement.clientHeight : screen.height;

            const systemZoom = width / window.screen.availWidth;
            const left = (width - w) / 2 / systemZoom + dualScreenLeft
            const top = (height - h) / 2 / systemZoom + dualScreenTop
            const newWindow = window.open(url, title,
                `
            scrollbars=yes,
            width  = ${w / systemZoom},
            height = ${h / systemZoom},
            top    = ${top},
            left   = ${left}
        `
            );

            if (window.focus) newWindow.focus();
        }
    </script>
@endsection
