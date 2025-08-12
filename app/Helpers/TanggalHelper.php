<?php

use Carbon\Carbon;

if (!function_exists('tanggalIndo')) {
    function tanggalIndo($tanggal)
    {
        Carbon::setLocale('id');
        return Carbon::parse($tanggal)->translatedFormat('d F Y');
    }
}

if (!function_exists('terbilang')) {
    function terbilang($angka)
    {
        $angka = abs($angka);
        $bilangan = [
            '', 'satu', 'dua', 'tiga', 'empat', 'lima',
            'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'
        ];

        if ($angka < 12) {
            return $bilangan[$angka];
        } elseif ($angka < 20) {
            return terbilang($angka - 10) . ' belas';
        } elseif ($angka < 100) {
            return terbilang(floor($angka / 10)) . ' puluh ' . terbilang($angka % 10);
        } elseif ($angka < 200) {
            return 'seratus ' . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return terbilang(floor($angka / 100)) . ' ratus ' . terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return 'seribu ' . terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            return terbilang(floor($angka / 1000)) . ' ribu ' . terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            return terbilang(floor($angka / 1000000)) . ' juta ' . terbilang($angka % 1000000);
        } else {
            return 'Angka terlalu besar';
        }
    }

}
