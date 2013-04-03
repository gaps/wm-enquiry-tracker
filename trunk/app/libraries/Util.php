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

    public static function getCourses()
    {

        $programs = array(
            "GMAT",
            "GRE",
            "SAT",
            "MS Admissions",
            "MBA admissions",
            "UG Admissions",
            "Phd Admissions",
            "Visa",
            "Admissions",
            "Write Ups",
            "IELTS",
            "TOEFL",
            "Speakeasy"
        );
        sort($programs);
        return $programs;
    }


    public static function  getTypes()
    {
        return array('Walk-in', 'Telephonic', 'Other');
    }

    public static function ConvertFollowupToCSV($enquiries)
    {
        $dataPoints = array();

        foreach ($enquiries as $enquiry) {
            $row = array();
            $row['name'] = $enquiry->name;
            $row['mobile'] = $enquiry->mobile;
            $row['followupDate'] = $enquiry->enquiryStatus[0]->followupDate;
            $row['program'] = $enquiry->program;
            $row['type'] = $enquiry->type;
            $row['status'] = $enquiry->enquiryStatus[0]->status;
            $row['branch'] = $enquiry->branch->name;
            array_push($dataPoints, $row);
        }

        return Util::ConvertToCSV($dataPoints);
    }

    public static function ConvertEnquiriesToCSV($enquiries)
    {
        $dataPoints = array();
        foreach ($enquiries as $enquiry) {
            $row = array();
            $row['name'] = $enquiry->name;
            $row['mobile'] = $enquiry->mobile;
            $row['enquiryDate'] = $enquiry->enquiryDate;
            $row['program'] = $enquiry->program;
            $row['type'] = $enquiry->type;
            $row['status'] = $enquiry->enquiryStatus[0]->status;
            $row['branch'] = $enquiry->branch->name;
            array_push($dataPoints, $row);
        }
        return Util::ConvertToCSV($dataPoints, false); //false passed for changed the header row status
    }

    /**
     * @param array $dataPoints - array of data points to convert to csv
     * @return string - csv record for data points
     */
    public static function ConvertToCSV(array $dataPoints, $isFollowUp = true)
    {
        $csvData = "";
        if ($isFollowUp)
            $headerRow = "Name,Mobile,FollowUp Date,Program,Type,Status,Branch \n";
        else
            $headerRow = "Name,Mobile,Enquiry Date,Program,Type,Status,Branch\n";
        $csvData .= $headerRow;

        foreach ($dataPoints as $data) {
            $dataRow = "";
            foreach ($data as $key => $value) {
                $dataRow .= "\"$value\",";
            }

            $dataRow = rtrim($dataRow, ",");

            $csvData .= "$dataRow \n";
        }

        return $csvData;
    }

    public static function convertToAbsoluteURL($filePath)
    {
        $path = base_path();
        $path = dirname(dirname($path));
        return $path . '/public_html/' . ltrim($filePath, "/");
    }

    public static function convertToHttpURL($filePath)
    {
        return URL::asset("$filePath");
//        return URL::asset('tmp/') . "/" . ltrim($filePath, "/");
    }

    public static function  generateTempFilePath($extension)
    {
        $extension = rtrim(ltrim($extension, "."), ".");
        $fileName = Str::random(64, 'alnum');
        return "tmp/$fileName.$extension";
    }

}