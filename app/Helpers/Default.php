<?php

use App\Models\General\Language;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\ImageManagerStatic as Image;

function noImage()
{
    return asset('coloradmin/img/no-image.png');
}
function requestOrder()
{
    $order = request()->get('order', '-id');
    if ($order[0] == '-') {
        $result = [
            'key' => substr($order, 1),
            'value' => 'desc'
        ];
    } else {
        $result = [
            'key' => $order,
            'value' => 'asc'
        ];
    }
    return $result;
}

function filterPhone($phone)
{
    return str_replace(['(', ')', ' ', '-'], '', $phone);
}

function uploadFile($file, $path, $old = null): ?string
{
    $result = null;
    if ($old) {
        $old_75 = "\\storage\\$path\\" . imagePathEdit($old, '_75');
        $old_90 = "\\storage\\$path\\" . imagePathEdit($old, '_90');
        deleteFile($old_75);
        deleteFile($old_90);
    }
    deleteFile($old);
    if ($file != null) {
        $names = explode(".", $file->getClientOriginalName());
        $file_name = str_replace(" ", '_', mb_strimwidth($names[0], 0, 20)) . rand(1, 999);
        $model = time() . $file_name . '.' . $names[count($names) - 1];
        $file->storeAs("public/$path", $model);
        // file size 75% and 90%
        fileResize($file, $path, $model);
        //
        $result = "/storage/$path/" . $model;
    }
    return $result;
}
function fileResize($file, $path, $model)
{
    // Faylning formatini aniqlash
    $format_fayl = pathinfo($model, PATHINFO_EXTENSION);
    // Faylning formatini va qabul qilish shartini tekshirish
    if (in_array($format_fayl, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
        // Rasmni 75% va 90% kompressiya darajasi bilan o'zgartirish va saqlash
        $img_75 = Image::make($file);
        $img_75->resize($img_75->width() * 0.25, $img_75->height() * 0.25);
        $img_75->save((storage_path('app/public/' . $path . "/" . imagePathEdit($model, '_75'))), 75);

        $img_90 = Image::make($file);
        $img_90->resize($img_90->width() * 0.10, $img_90->height() * 0.10);
        $img_90->save((storage_path('app/public/' . $path . "/" . imagePathEdit($model, '_90'))), 90);
    }
}
function deleteFile($path): void
{
    $path = str_replace('storage', '', $path);
    if ($path != null && file_exists(storage_path("app/public/$path"))) {
        unlink(storage_path("app/public/$path"));
    }
}

function nudePhone($phone)
{
    if (strlen($phone) > 0)
        $phone = str_replace(['(', ')', ' ', '-', '+'], '', $phone);

    if (strlen($phone) > 0) {
        if ($phone[0] == '7') {
            $phone = substr($phone, 1);
        }
    }

    return $phone;
}

function buildPhone($phone): string
{
    $phone = nudePhone($phone);
    return '+7 ' . '(' . substr($phone, 0, 3) . ') '
        . substr($phone, 3, 3) . '-'
        . substr($phone, 6, 2) . '-'
        . substr($phone, 8, 2);
}

function getKey()
{
    $key = explode('.', request()->route()->getName());
    array_pop($key);
    $key = implode('.', $key);
    return $key;
}

function getRequest($request = null)
{
    return $request ?? request();
}

function defaultLocale()
{
    $lang = null;
    if (Schema::hasTable('languages')) {
        $lang = Language::where('default', true)->first();
    }

    return $lang;
}

function allLanguage()
{
    return Language::where('status', true)->orderBy('default', 'desc')->get();
}
function allUrl()
{
    return Language::where('status', true)->orderBy('default', 'desc')->pluck('url')->toArray();
}
function defaultLocaleCode()
{
    return optional(defaultLocale())->url;
}

function paginate($items, $perPage = 15, $page = null, $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

    $items = $items instanceof Collection ? $items : Collection::make($items);

    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
}

function formatDate($date_string, $format = 'd-m-Y')
{
    if ($date_string == null || $date_string == '')
        return '';
    return date($format, strtotime($date_string));
}

function formatDateTime($date_string, $format = 'd.m.Y H:i:s')
{
    if ($date_string == null || $date_string == '')
        return '';
    return date($format, strtotime($date_string));
}

function clearRequest($request, $arr)
{
    foreach ($arr as $value) {
        if ($request->has($value))
            $request->offsetUnset($value);
    }
    return $request;
}

function enabledNotifications($id, $type)
{
    DB::table('notifications')->where('earthquake_reservoir_id', $id)
        ->where('type', $type)->update(['status' => false]);
};
function formatDateTimeFormat($inputDateTime, $language = 'en')
{
    // Input sana va vaqtni strtotime() orqali sanaga o'tkazamiz
    $timestamp = strtotime($inputDateTime);

    // Oy nomini olish
    $monthName = date("F", $timestamp);

    // Oy nomlarini tarjima qilish
    $translatedMonths = [
        'en' => [
            'January' => 'January',
            'February' => 'February',
            'March' => 'March',
            'April' => 'April',
            'May' => 'May',
            'June' => 'June',
            'July' => 'July',
            'August' => 'August',
            'September' => 'September',
            'October' => 'October',
            'November' => 'November',
            'December' => 'December',
        ],
        'uz' => [
            'January' => 'Yanvar',
            'February' => 'Fevral',
            'March' => 'Mart',
            'April' => 'Aprel',
            'May' => 'May',
            'June' => 'Iyun',
            'July' => 'Iyul',
            'August' => 'Avgust',
            'September' => 'Sentabr',
            'October' => 'Oktabr',
            'November' => 'Noyabr',
            'December' => 'Dekabr',
        ],
        'ru' => [
            'January' => 'Январь',
            'February' => 'Февраль',
            'March' => 'Март',
            'April' => 'Апрель',
            'May' => 'Май',
            'June' => 'Июнь',
            'July' => 'Июль',
            'August' => 'Август',
            'September' => 'Сентябрь',
            'October' => 'Октябрь',
            'November' => 'Ноябрь',
            'December' => 'Декабрь',
        ],
    ];

    // Formatni tanlaymiz va chiqaramiz
    $formattedDate = date("F j, Y", $timestamp);

    // Oy nomini tilga qarab almashtiramiz
    $translatedMonthName = $translatedMonths[$language][$monthName];
    $formattedDate = str_replace($monthName, $translatedMonthName, $formattedDate);

    return $formattedDate;
};
function imagePathEdit($old_path, $new_string)
{
    $new_path = pathinfo($old_path, PATHINFO_FILENAME) . $new_string . '.' . pathinfo($old_path, PATHINFO_EXTENSION);
    return $new_path;
}
function imagePathEditGet($old_path, $new_string, $path)
{
    $new_path = '/' . $path . '/' . pathinfo($old_path, PATHINFO_FILENAME) . $new_string . '.' . pathinfo($old_path, PATHINFO_EXTENSION);
    return $new_path;
}

function getCurrentYearMonth(): object
{
    return (object) [
        'year' => date('Y'),
        'month' => date('m')
    ];
}
function generateYears($startYear, $endYear) {
    $years = [];
    for ($year = $startYear; $year <= $endYear; $year++) {
        $years[] = [
            'year' => $year
        ];
    }
    return $years;
}
function generateMonthNames($lang = 'en') {
    $monthNames = [
        'en' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        'ru' => ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        'uz' => ['Yanvar', 'Fevral', 'Mart', 'Aprel', 'May', 'Iyun', 'Iyul', 'Avgust', 'Sentabr', 'Oktabr', 'Noyabr', 'Dekabr'],
    ];

    if (!isset($monthNames[$lang])) {
        $lang = 'en';
    }

    return $monthNames[$lang];
}

function monthList()
{
    return [
        1 => 'Yanvar',
        2 => 'Fevral',
        3 => 'Mart',
        4 => 'Aprel',
        5 => 'May',
        6 => 'Iyun',
        7 => 'Iyul',
        8 => 'Avgust',
        9 => 'Sentabr',
        10 => 'Oktabr',
        11 => 'Noyabr',
        12 => 'Dekabr',
    ];
}
if (!function_exists('sumSalariesBySprint')) {
    function sumSalariesBySprint(array|object $data): array
    {
        $result = [];

        foreach ($data as $item) {
            // Obyekt yoki massiv ekanligini tekshiramiz
            $sprint = is_object($item) ? $item->sprint : $item['sprint'];
            $salary = is_object($item) ? (float) $item->calculated_salary : (float) $item['calculated_salary'];

            if (!isset($result[$sprint])) {
                $result[$sprint] = 0;
            }
            $result[$sprint] += $salary;
        }

        // Formatlash
        foreach ($result as $sprint => &$salary) {
            $salary = number_format($salary, 0, '.', ' ');
        }

        return $result;
    }
}

if (!function_exists('sumWorkTimeBySprint')) {
    function sumWorkTimeBySprint(array|object $data): array
    {
        $result = [];

        foreach ($data as $item) {
            $sprint = is_object($item) ? $item->sprint : $item['sprint'];
            $workTime = is_object($item) ? (int) $item->actual_work_time : (int) $item['actual_work_time'];

            if (!isset($result[$sprint])) {
                $result[$sprint] = 0;
            }
            $result[$sprint] += $workTime;
        }

        return $result;
    }
}
