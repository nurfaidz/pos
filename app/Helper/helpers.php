<?php

function addNullAtForward($value, $threshold = null)
{
    return sprintf("%0" . $threshold . "s", $value);
}

function moneyFormat($angka)
{
    return number_format($angka, 0, '', '.');
}

function indonesianDate($tgl, $tampil_hari = true)
{
    $dayName  = array(
        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'
    );
    $monthName = array(
        1 =>
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );

    $year   = substr($tgl, 0, 4);
    $month   = $monthName[(int) substr($tgl, 5, 2)];
    $date = substr($tgl, 8, 2);
    $text    = '';

    if ($tampil_hari) {
        $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $date, $year));
        $day        = $dayName[$urutan_hari];
        $text       .= "$day, $date $month $year";
    } else {
        $text       .= "$date $month $year";
    }

    return $text;
}

function counted($angka)
{
    $angka = abs($angka);
    $baca  = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
    $counted = '';

    if ($angka < 12) { // 0 - 11
        $counted = ' ' . $baca[$angka];
    } elseif ($angka < 20) { // 12 - 19
        $counted = counted($angka - 10) . ' belas';
    } elseif ($angka < 100) { // 20 - 99
        $counted = counted($angka / 10) . ' puluh' . counted($angka % 10);
    } elseif ($angka < 200) { // 100 - 199
        $counted = ' seratus' . counted($angka - 100);
    } elseif ($angka < 1000) { // 200 - 999
        $counted = counted($angka / 100) . ' ratus' . counted($angka % 100);
    } elseif ($angka < 2000) { // 1.000 - 1.999
        $counted = ' seribu' . counted($angka - 1000);
    } elseif ($angka < 1000000) { // 2.000 - 999.999
        $counted = counted($angka / 1000) . ' ribu' . counted($angka % 1000);
    } elseif ($angka < 1000000000) { // 1000000 - 999.999.990
        $counted = counted($angka / 1000000) . ' juta' . counted($angka % 1000000);
    }

    return $counted;
}
