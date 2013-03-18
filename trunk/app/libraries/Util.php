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
        $dateid = date('Y', $fromDate->getTimestamp()) . "-" . date('m', $fromDate->getTimestamp()) . "-" . date('d', $fromDate->getTimestamp()) . " 00:00:00";
        $fromDate = new DateTime($dateid);
        return $fromDate;
    }

    public static function getToDate($toDate)
    {

        if (empty($toDate))
            return $toDate;
        $dateid = date('Y', $toDate->getTimestamp()) . "-" . date('m', $toDate->getTimestamp()) . "-" . date('d', $toDate->getTimestamp()) . " 00:00:00";
        $toDate = new DateTime($dateid);
        $toDate->add(new DateInterval('P1D'));
        return $toDate;
    }

    public static function getStatus()
    {
        return array(array("name" => "New", 'id' => EnquiryStatus::CREATED), array(
            "name" => "Enrolled", 'id' => EnquiryStatus::ENROLLED)
        , array("name" => "Enrolled Later", 'id' => EnquiryStatus::FOLLOW_UP)
        , array("name" => "Not Interested", 'id' => EnquiryStatus::NOT_INTERESTED));
    }

    public static function  getTypes()
    {
        return array('Walk-in', 'Telephonic', 'Other');
    }

}