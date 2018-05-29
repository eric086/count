<?php

namespace app\extensions;

class Date {
    //获取给定时间的周一周日 日期
    public static function getWeekDays($date = '') {
        $timestamp = $date ? strtotime($date) : strtotime(date('Y-m-d'));
        $sdate = date('Y-m-d', $timestamp - (date('N', $timestamp) - 1) * 86400);
        $edate = date('Y-m-d', $timestamp + (7 - date('N', $timestamp)) * 86400);
        return [$sdate, $edate];
    }
    
    //获取给定时间的上周 周一周日 日期
    public static function getLastWeekDays($date = ''){
        $timestamp = $date ? strtotime($date) - 7 * 86400 : strtotime(date('Y-m-d')) - 7 * 86400;
        $sdate = date('Y-m-d', $timestamp - (date('N', $timestamp) - 1) * 86400);
        $edate = date('Y-m-d', $timestamp + (7 - date('N', $timestamp)) * 86400);
        return [$sdate, $edate];
    }
    
    //获取给定时间的下周 周一周日 日期
    public static function getNextWeekDays($date = ''){
        $timestamp = $date ? strtotime($date) + 7 * 86400 : strtotime(date('Y-m-d')) + 7 * 86400;
        $sdate = date('Y-m-d', $timestamp - (date('N', $timestamp) - 1) * 86400);
        $edate = date('Y-m-d', $timestamp + (7 - date('N', $timestamp)) * 86400);
        return [$sdate, $edate];
    }
    
    //获取给定时间的月初 月末 日期
    public static function getMonthDays($date = '') {
        $timestamp = $date ? strtotime($date) : strtotime(date('Y-m-d'));
        $firstday = date("Y-m-01", $timestamp);
        $lastday = date("Y-m-d", strtotime("$firstday +1 month -1 day"));
        return [$firstday, $lastday];
    }

    //获取给定时间的上月 月初 月末 日期
    public static function getlastMonthDays($date = '') {
        $timestamp = $date ? strtotime($date) : strtotime(date('Y-m-d'));
        $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        return [$firstday, $lastday];
    }
    
    //获取给定时间的下月 月初 月末 日期
    public static function getNextMonthDays($date = '') {
        $timestamp = $date ? strtotime($date) : strtotime(date('Y-m-d'));
        $arr = getdate($timestamp);
        if ($arr['mon'] == 12) {
            $year = $arr['year'] + 1;
            $month = $arr['mon'] - 11;
            $firstday = $year . '-0' . $month . '-01';
            $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        } else {
            $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) + 1) . '-01'));
            $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        }
        return [$firstday, $lastday];
    }
}



