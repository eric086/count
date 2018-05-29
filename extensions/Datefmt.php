<?php

namespace app\extensions;

class Datefmt {
    /**
     * 根据指定日期获取所在周的起始时间和结束时间
     */
    public function get_weekinfo_by_date($date) {
        $idx = strftime("%u", strtotime($date));
        $mon_idx = $idx - 1;
        $sun_idx = $idx - 7;
        return array(
            'week_start_day' => strftime('%Y-%m-%d', strtotime($date) - $mon_idx * 86400),
            'week_end_day' => strftime('%Y-%m-%d', strtotime($date) - $sun_idx * 86400),
        );
    }

    /**
     * 根据指定日期获取所在月的起始时间和结束时间
     */
    public function get_monthinfo_by_date($date) {
        $ret = array();
        $timestamp = strtotime($date);
        $mdays = date('t', $timestamp);
        return array(
            'month_start_day' => date('Y-m-1', $timestamp),
            'month_end_day' => date('Y-m-' . $mdays, $timestamp)
        );
    }

    /**
     * 获取指定日期之间的各个周
     */
    public function get_weeks($sdate, $edate) {
        $range_arr = array();
        // 检查日期有效性
        $this->check_date(array($sdate, $edate));
        // 计算各个周的起始时间
        do {
            $weekinfo = $this->get_weekinfo_by_date($sdate);
            $end_day = $weekinfo['week_end_day'];
            $start = $this->substr_date($weekinfo['week_start_day']);
            $end = $this->substr_date($weekinfo['week_end_day']);
            $range = "{$start} ~ {$end}";
            $range_arr[] = [
                'start_day' => $start,
                'end_day' => $end
            ];
            $sdate = date('Y-m-d', strtotime($sdate) + 7 * 86400);
        } while ($end_day < $edate);
        return $range_arr;
    }

    /**
     * 获取指定日期之间的各个月
     */
    public function get_months($sdate, $edate) {
        $range_arr = array();
        do {
            $monthinfo = $this->get_monthinfo_by_date($sdate);
            $end_day = $monthinfo['month_end_day'];
            $start = $this->substr_date($monthinfo['month_start_day']);
            $end = $this->substr_date($monthinfo['month_end_day']);
            $range = "{$start} ~ {$end}";
            $range_arr[] = [
                'start_day' => $start,
                'end_day' => $end
            ];
            $sdate = date('Y-m-d', strtotime($sdate . '+1 month'));
        } while ($end_day < $edate);
        return $range_arr;
    }

    /**
     * 截取日期中的月份和日
     * @param string $date
     * @return string $date
     */
    public function substr_date($date) {
        if (!$date)
            return FALSE;
        return date('Y-m-d', strtotime($date));
    }

    /**
     * 检查日期的有效性 YYYY-mm-dd
     * @param array $date_arr
     * @return boolean
     */
    public function check_date($date_arr) {
        $invalid_date_arr = array();
        foreach ($date_arr as $row) {
            $timestamp = strtotime($row);
            $standard = date('Y-m-d', $timestamp);
            if ($standard != $row)
                $invalid_date_arr[] = $row;
        }
        if (!empty($invalid_date_arr)) {
            die("invalid date -> " . print_r($invalid_date_arr, TRUE));
        }
    }

}

