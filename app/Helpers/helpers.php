<?php

use Carbon\Carbon;

if (!function_exists('rupiah')) {
    function rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    if (!function_exists('isActiveMenu')) {
        function isActiveMenu($items)
        {
            foreach ($items as $item) {


                if (isset($item['href']) && request()->is(trim($item['href'], '/'))) {


                    return true;
                }
                if (isset($item['child']) && isActiveMenu($item['child'])) {
                    return true;
                }
            }
            return false;
        }
    }
}

if (!function_exists('isActiveMenu')) {
    function isActiveMenu($items)
    {
        foreach ($items as $item) {
            if (isset($item['href']) && request()->is(trim($item['href'], '/'))) {
                return true;
            }
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
