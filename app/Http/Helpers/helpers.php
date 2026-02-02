<?php

function format_uang($angka) {
    return number_format($angka, 0, ',', '.');
}

function eja($angka) {
    // Ensure the input is a number, if it's a string, try converting it to an integer
    if (!is_numeric($angka)) {
        throw new InvalidArgumentException('Input must be a numeric value.');
    }

    $angka = intval(abs($angka));
    $baca = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
    $eja = '';

    if ($angka < 12) { // 1 s/d 11
        $eja = ' ' . $baca[$angka];
    } elseif ($angka < 20) { // 12 s/d 19
        $eja = eja($angka - 10) . ' belas';
    } elseif ($angka < 100) { // 20 s/d 99
        $eja = eja(intval($angka / 10)) . ' puluh' . eja($angka % 10);
    } elseif ($angka < 200) { // 100 s/d 199
        $eja = ' seratus' . eja($angka - 100);
    } elseif ($angka < 1000) { // 200 s/d 999
        $eja = eja(intval($angka / 100)) . ' ratus' . eja($angka % 100);
    } elseif ($angka < 2000) { // 1000 s/d 1999
        $eja = ' seribu' . eja($angka - 1000);
    } elseif ($angka < 1000000) { // 2000 s/d 999999 (1jt -1)
        $eja = eja(intval($angka / 1000)) . ' ribu' . eja($angka % 1000);
    } elseif ($angka < 1000000000) { // 1000000(1jt) s/d 999999999 (1milyar -1)
        $eja = eja(intval($angka / 1000000)) . ' juta' . eja($angka % 1000000);
    }

    return $eja;
}

function tgl_indo($tgl, $tampilkan_hari= true){
    $nama_hari = array(
        'Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'
    );
    $nama_bulan = array(1 => 
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );

    // 2024-05-30
    $tahun = substr($tgl, 0, 4);
    $bulan = $nama_bulan[(int) substr($tgl, 5, 2)];
    $tanggal = substr($tgl, 8, 2);
    $text = '';

    if ($tampilkan_hari){
        $urutan_hari = date('w', mktime(0,0,0, substr($tgl, 5,2), $tanggal, $tahun));
        $hari = $nama_hari[$urutan_hari];
        $text .= "$hari, $tanggal $bulan $tahun";
    } else {
        $text .= "$tanggal $bulan $tahun";
    }

    return $text;
}

function tambah_nol_didepan($value, $threshold = null) {
    return sprintf("%0". $threshold . "s", $value);

}