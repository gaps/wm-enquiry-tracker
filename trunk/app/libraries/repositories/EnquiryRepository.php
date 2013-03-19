<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 2/3/13
 * Time: 1:06 PM
 * To change this template use File | Settings | File Templates.
 */

class EnquiryRepository
{

    public function createEnquiry($name, $mobile, $email, $date, $program, $type, $user_id, $branch_id)
    {
        $enquiryObject = new Enquiry();
        $enquiryObject->name = $name;
        $enquiryObject->mobile = $mobile;
        $enquiryObject->email = $email;
        $enquiryObject->user_id = $user_id;
        $enquiryObject->enquiryDate = $date;
        $enquiryObject->program = $program;
        $enquiryObject->branch_id = $branch_id;
        $enquiryObject->type = $type;

        $status = new EnquiryStatus();
        $status->status = EnquiryStatus::CREATED;


        DB::connection()->transaction(function () use ($status, $enquiryObject) {
            try {
                $enquiryObject->save();
                $status->enquiry_id = $enquiryObject->id;
                $status->save();

            } catch (Exception $e) {
                Log::error("$e");
                return false;
            }
        });
        return $enquiryObject;
    }


    public function getEnquiries($branchIds, $status, $types, $fromDate = null, $toDate = null, $skip = 0, $perPage = Constants::PAGECOUNT)
    {
        if (empty($status) || empty($types) || empty($branchIds) || $fromDate == null || $toDate == null)
            return array();

        $statusString = "";

        foreach ($status as $st) {
            $statusString .= "'$st',";
        }

        $statusString = rtrim($statusString, ",");

        $query = "
          SELECT enquiry_id from (
            SELECT
            es.enquiry_id,
            es.status,
            ROW_NUMBER() OVER (PARTITION BY es.enquiry_id ORDER BY es.created_at DESC) as rownum
            from \"enquiryStatus\" es) as filteredTable where rownum = 1 AND status in ($statusString)
        ";

        try {
            $results = DB::select($query);
        } catch (PDOException $e) {
            Log::error($e);
            return false;
        }

        if (empty($results))
            return array();

        $filteredEnquiryIds = array();

        foreach ($results as $result) {
            $filteredEnquiryIds[] = $result->enquiry_id;
        }

        $query = Enquiry::with(
            array('branch', 'enquiryStatus'))->
            whereIn('id', $filteredEnquiryIds)->
            whereIn('branch_id', $branchIds);


        try {
            return $query->where('enquiryDate', '>=', $fromDate)->
                where('enquiryDate', '<', $toDate)->whereIn('type', $types)->orderBy('enquiryDate', 'desc')->skip($skip)->take($perPage)->get();
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }

    public function getFollowUps(DateTime $fromDate, DateTime $toDate, $types, $branchIds, $skip = 0, $perPage = Constants::PAGECOUNT)
    {

        if (empty($types) || empty($branchIds) || $fromDate == null || $toDate == null)
            return array();

        $query = "
          SELECT enquiry_id from (
            SELECT
            es.enquiry_id,
            es.status,
            ROW_NUMBER() OVER (PARTITION BY es.enquiry_id ORDER BY es.created_at DESC) as rownum
            from \"enquiryStatus\" es) as filteredTable where rownum = 1 AND status in  ('follow_up')
        ";


        try {
            $results = DB::select($query);
        } catch (PDOException $e) {
            Log::error($e);
            return false;
        }

        if (empty($results))
            return array();

        $filteredEnquiryIds = array();

        foreach ($results as $result) {
            $filteredEnquiryIds[] = $result->enquiry_id;
        }


        $branchIdsString = implode(",", $branchIds);
        $enquiryIdsString = implode(",", $filteredEnquiryIds);

        $query = "
                SELECT e.id FROM \"enquiries\" e
                    JOIN \"enquiryStatus\" es on e.id = es.enquiry_id
                    where
                    es.\"followupDate\" >= ? AND es.\"followupDate\" < ? AND
                    e.branch_id in ($branchIdsString) AND
                    e.id in ($enquiryIdsString)";

        $results = DB::select($query, array($fromDate, $toDate));

        if (empty($results))
            return array();

        $enquiryIds = array();

        foreach ($results as $result) {
            $enquiryIds[] = $result->id;
        }

        $query = Enquiry::with(array('branch', 'enquiryStatus'))
            ->whereIn('id', $enquiryIds)->whereIn('type', $types);


        return $query->orderBy('enquiryDate', 'desc')->skip($skip)->take($perPage)->get();
    }


    public function createFollowup($enquiryId, DateTime $followupDate, $remarks)
    {
        return $this->updateStatus($enquiryId, EnquiryStatus::FOLLOW_UP, $followupDate, null, $remarks);
    }

    public function markEnquiryNew($enquiryId)
    {
        return $this->updateStatus($enquiryId, EnquiryStatus::CREATED);
    }

    public function markEnquiryEnrolled($enquiryId, $joiningDate, $remarks)
    {
        return $this->updateStatus($enquiryId, EnquiryStatus::ENROLLED, null, $joiningDate, $remarks);
    }

    public function markEnquiryNotInterested($enquiryId, $remarks)
    {
        return $this->updateStatus($enquiryId, EnquiryStatus::NOT_INTERESTED, null, null, $remarks);
    }


    private function updateStatus(
        $enquiryId, $status, $followupDate = null,
        $joiningDate = null, $remarks = null)
    {

        $enquiry = Enquiry::find($enquiryId);

        if (empty($enquiry))
            return false;

        $enquiryStatus = new EnquiryStatus();
        $enquiryStatus->status = $status;
        $enquiryStatus->followupDate = $followupDate;
        $enquiryStatus->joiningDate = $joiningDate;
        $enquiryStatus->remarks = $remarks;

        try {
            $enquiry->enquiryStatus()->save($enquiryStatus);
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
        return $enquiryStatus;
    }


}