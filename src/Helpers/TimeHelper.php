<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2020 seffeng
 */
namespace Seffeng\Basics\Helpers;

class TimeHelper
{
    /**
     * 计算某年每周时间范围
     * @author zxf
     * @date   2020年4月30日
     * @param  int $year
     * @return array
     */
    public static function getWeekRangeTime(int $year)
    {
        $time = strtotime(date($year .'-01-01'));
        $weekDay = date('w', $time);
        if ($weekDay == 0) {
            $weekDay = 7;
        }
        $data = [];
        for ($i = 1; ; $i++) {
            $startTime = strtotime('-'. ($weekDay - 1) .' days', $time);
            $endTime = strtotime('+'. (7 - $weekDay) .' days', $time);
            $data[$i] = [
                'start' => date('Y-m-d', $startTime),
                'end' => date('Y-m-d', $endTime),
            ];
            $time = strtotime('+7 days', $startTime);
            if ($i > 1 && date('Y', $endTime) > date('Y', $startTime)) {
                break;
            }
            $weekDay = date('w', $startTime);
            if ($weekDay == 0) {
                $weekDay = 7;
            }
        }
        return $data;
    }

    /**
     * 返回周N
     * @author zxf
     * @date   2020年4月30日
     * @param  int $time
     * @param  string $key
     * @return string
     */
    public static function asWeekCN(int $time, string $key = '周')
    {
        $weekItems = ['日', '一', '二', '三', '四', '五', '六'];
        $week = date('w', $time);
        return $key . $weekItems[$week];
    }
}