<?php
/**
 * Created by JetBrains PhpStorm.
 * User: keshav
 * Date: 11/3/13
 * Time: 11:15 AM
 * To change this template use File | Settings | File Templates.
 */

class Util
{

    public static function getFromDate($fromDate)
    {
        if (empty($fromDate))
            return $fromDate;
        $dateValue = date('Y', $fromDate->getTimestamp()) . "-" . date('m', $fromDate->getTimestamp()) . "-" . date('d', $fromDate->getTimestamp()) . " 00:00:00";
        $fromDate = new DateTime($dateValue);
        return $fromDate;
    }

    public static function getToDate($toDate)
    {

        if (empty($toDate))
            return $toDate;
        $dateValue = date('Y', $toDate->getTimestamp()) . "-" . date('m', $toDate->getTimestamp()) . "-" . date('d', $toDate->getTimestamp()) . " 00:00:00";
        $toDate = new DateTime($dateValue);
        $toDate->add(new DateInterval('P1D'));
        return $toDate;
    }

}