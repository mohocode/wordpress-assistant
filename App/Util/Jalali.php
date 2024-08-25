<?php 
namespace App\Util;
class Jalali {

    public static $gregorainDayPerMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    public static  $jalaliDayPerMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
    public static  $jalaliMonthName = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];

    // public static function convert($date) {

    //     @\Morilog\Jalali\Jalalian::forge($date)
    //     ->format('%A, %d %B %y');
    // }

    public static function jalaliToGregorian($date) {

        $date = explode('-', date('Y-m-d'));

        $gregorianDate = [
            'year' => 0,
            'month' => 0,
            'day' => 0
        ];

        $mid = [
            'year' => $date[0] - 979,
            'month' => $date[1] - 1,
            'day' => $date[2] - 1
        ];

        $dayNo = 365 * $mid['year'] + floor($mid['year'] / 33) * 8 + floor(($mid['year'] % 33 + 3) / 4);

        for ($month = 0; $month < $mid['month']; ++$month)
            $dayNo += self::$jalaliDayPerMonth[$month];

        $dayNo += $mid['day'];


        $gregorianDate['day'] = $dayNo + 79;

        $gregorianDate['year'] = 1600 + 400 * floor($gregorianDate['day'] / 146097);
        $gregorianDate['day'] = $gregorianDate['day'] % 146097;

        $leap = true;

        if ($gregorianDate['day'] >= 36525) {
            $gregorianDate['day']--;
            $gregorianDate['year'] += 100 * floor($gregorianDate['day'] /  36524);
            $gregorianDate['day'] = $gregorianDate['day'] % 36524;

            if ($gregorianDate['day'] >= 365)
                $gregorianDate['day']++;
            else
                $leap = false;
        }

        $gregorianDate['year'] += 4 * floor($gregorianDate['day'] / 1461);
        $gregorianDate['day'] %= 1461;

        if ($gregorianDate['day'] >= 366) {
            $leap = false;

            $gregorianDate['day']--;
            $gregorianDate['year'] += floor($gregorianDate['day'] / 365);
            $gregorianDate['day'] = $gregorianDate['day'] % 365;
        }


        for ($index = 0; $gregorianDate['day'] >= self::$gregorainDayPerMonth[$index] + ($index == 1 && $leap); $index++)
            $gregorianDate['day'] -= self::$gregorainDayPerMonth[$index] + ($index == 1 && $leap);

        $gregorianDate['year'] = (int)$gregorianDate['year'];
        $gregorianDate['month'] = $index + 1;
        $gregorianDate['day']++;

        return ceil((int)$gregorianDate);
    }


    public static function gregorianToJalali( $date) {

        $date = explode('-', date('Y-m-d'));

        $mid = [
            'year' => $date[0] - 1600,
            'month' => $date[1] - 1,
            'day' => $date[2] - 1,

        ];

        $jalali = [
            'year' => 0,
            'month' => 0,
            'day' => 0
        ];

        $dayNo = 365 * $mid['year'] + floor(($mid['year'] + 3) / 4) - floor(($mid['year'] + 99) / 100) + floor(($mid['year'] + 399) / 400);

        for ($month = 0; $month < $mid['month']; ++$month) {

            $dayNo += self::$gregorainDayPerMonth[$month];
        }

        if ($mid['month'] > 1 && (($mid['year'] % 4 == 0 && $mid['year'] % 100 != 0) || ($mid['year'] % 400 == 0)))
            ++$dayNo;

        $dayNo += $mid['day'];
        $jalali['day'] = $dayNo - 79;

        $j_np = floor($jalali['day'] / 12053);

        $jalali['day'] %= 12053;
        $jalali['year'] = 979 + 33 * $j_np + 4 * floor($jalali['day'] / 1461);

        $jalali['day'] %= 1461;

        if ($jalali['day'] >= 366) {
            $jalali['year'] += floor(($jalali['day'] - 1) / 365);
            $jalali['day'] = ($jalali['day'] - 1) % 365;
        }


        for ($index = 0; $index < 11 && $jalali['day'] >= self::$jalaliDayPerMonth[$index]; ++$index) {
            $jalali['day'] -= self::$jalaliDayPerMonth[$index];
        }

        $jalali['year'] = (int)$jalali['year'];
        $jalali['month'] = $index + 1;
        $jalali['day']++;


        return $jalali;
    }

    public static function printMonthName($date) {

        for ($i = 0; $i < 12; $i++) {
            if ($date['month'] == $i) {
                $month =  self::$jalaliMonthName[$i];
            }
        }

        return $date['day'] . ' , ' . $month . ' , ' . $date['year'];
    }
    public static function currentYear($date) {
        return self::gregorianToJalali($date)['year'];
    }

}