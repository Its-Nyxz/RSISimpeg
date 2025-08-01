<?php

use Carbon\Carbon;

if (!function_exists('rupiah')) {
    function rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('isActiveMenu')) {
    function isActiveMenu($items)
    {
        $currentUrl = request()->fullUrl(); // Ambil URL lengkap

        foreach ($items as $item) {
            if (isset($item['href'])) {
                $menuUrl = url($item['href']); // Ubah menjadi URL lengkap

                // Cek apakah URL menu adalah bagian dari URL saat ini
                if (strpos($currentUrl, $menuUrl) !== false) {
                    return true;
                }
            }
            // Cek jika submenu aktif
            if (isset($item['child']) && isActiveMenu($item['child'])) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date, $format = 'd F Y', $locale = 'id') //jika format dan local kosong maka $format = 'd F Y' dan $locale = 'id' adalah defaultnya
    {
        return Carbon::parse($date)->locale($locale)->translatedFormat($format);
    }
}
