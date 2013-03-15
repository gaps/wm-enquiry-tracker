<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 2/3/13
 * Time: 8:47 AM
 * To change this template use File | Settings | File Templates.
 */

class EnquiryController extends BaseController
{
    private $enquiryRepo;

    public function __construct()
    {
        $this->enquiryRepo = new EnquiryRepository();
    }

    /**
     * Get view for viewing list of demo for authenticated user
     * @return Laravel\View
     */
    public function getList()
    {
//        $fromDate = Util::getFromDate(new DateTime('now'));
//        $toDate = Util::getToDate(new DateTime('now'));
//
//        $branchIds = array();
//        $branches = Auth::user()->branches()->get();
//        foreach ($branches as $branch) {
//            $branchIds[] = $branch->id;
//        }
//
//        $enquiries = $this->enquiryRepo->getEnquiries($branchIds,
//            array(EnquiryStatus::FOLLOW_UP, EnquiryStatus::CREATED,
//                EnquiryStatus::ENROLLED, EnquiryStatus::NOT_INTERESTED),
//            Util::getTypes(), $fromDate, $toDate);
        return View::make('enquiry.list');

//        return View::make('enquiry.list')->with('fromDate', $fromDate)->
//            with('toDate', $toDate)->
//            with('enquiries', $enquiries)->
//            with('branches', $branches);
    }

    public function getBranches()
    {
        $branchesArray = array();
        $branches = Auth::user()->branches()->get();
        foreach ($branches as $branch) {
            $branchesArray[] = $branch->name;
        }

        return Response::json($branchesArray);

    }


    public function getTypes()
    {
        return Response::json(Util::getTypes());
    }

    public function getStatuses()
    {

        return Response::json(Util::getStatus());
    }

    public function postAddEnquiry()
    {
        $data = (object)Input::json();
        if (empty($data) || $data == null) {
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);
        }

        //todo: add validation

        $name = isset($data->name) ? $data->name : "";
        $mobile = isset($data->mobile) ? $data->mobile : NULL;
        $email = isset($data->email) ? $data->email : NULL;
        $date = isset($data->date) ? $data->date : date("Y-m-d");
        $program = isset($data->program) ? $data->program : "";
        $branch_id = $data->branch_id;
        $type = isset($data->type) ? $data->type : "";

        try {
            $newEnquiry = $this->enquiryRepo->createEnquiry($name, $mobile, $email, $date, $program, $type, Auth::user()->id, $branch_id);
        } catch (PDOException $e) {
            Log::exception($e);
            return Response::make(Lang::get('error.bad'), Constants::DATABASE_ERROR_CODE);
        }

        if (empty($newEnquiry))
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        return $newEnquiry->toJson();
    }

    public function postGetEnquiries()
    {
        $data = (object)Input::json();

        if (empty($data)) {
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);
        }

        $fromDate = !isset($data->fromDate) || empty($data->fromDate) ? new DateTime('now') : Util::getFromDate(new DateTime($data->fromDate));
        $toDate = !isset($data->toDate) || empty($data->toDate) ? new DateTime('now') : Util::getToDate(new DateTime($data->toDate));

        $status = isset($data->status) ? $data->status : array();
        $branchIds = isset($data->branchIds) ? $data->branchIds : array();
        $types = isset($data->types) ? $data->types : array();

        try {
            $enquiries = $this->enquiryRepo->getEnquiries($branchIds, $status, $types, $fromDate, $toDate);
        } catch (PDOException $e) {
            Log::exception($e);
            return Response::make(Lang::get('error.bad'), Constants::DATABASE_ERROR_CODE);
        }
        if (empty($enquiries))
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        return $enquiries->toJson();


    }

    public function postGetFollowUps()
    {
        $data = (object)Input::json();

        if (empty($data)) {
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);
        }

        $fromDate = !isset($data->fromDate) || empty($data->fromDate) ? new DateTime('now') : Util::getFromDate(new DateTime($data->fromDate));
        $toDate = !isset($data->toDate) || empty($data->toDate) ? new DateTime('now') : Util::getToDate(new DateTime($data->toDate));

        $branchIds = isset($data->branchIds) ? $data->branchIds : array();
        $types = isset($data->types) ? $data->types : array();

        try {
            $enquiries = $this->enquiryRepo->getFollowUps($fromDate, $toDate, $types, $branchIds);
        } catch (PDOException $e) {
            Log::exception($e);
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);
        }

        if (!is_array($enquiries) && !$enquiries)
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        else if (empty($enquiries)) {
            return Response::json($enquiries);
        }
//
//            if (empty($enquiries))
//                return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        return $enquiries->toJson();


    }

    public function postMarkEnrolled()
    {
        $data = (object)Input::json();

        if (empty($data))
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);


        $enquiryId = isset($data->enquiryId) ? $data->enquiryId : null;
        $joiningDate = isset($data->joiningDate) ? $data->joiningDate : null;
        $remarks = isset($data->remarks) ? $data->remarks : null;

        $enquiryStatus = $this->enquiryRepo->markEnquiryEnrolled($enquiryId, $joiningDate, $remarks);

        if ($enquiryStatus == false)
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        return $enquiryStatus->toJson();
    }


    public function postMarkEnquiryNew()
    {
        $data = (object)Input::json();

        if (empty($data))
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);


        $enquiryId = isset($data->enquiryId) ? $data->enquiryId : null;
        $enquiryStatus = $this->enquiryRepo->markEnquiryNew($enquiryId);

        if ($enquiryStatus == false)
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        return $enquiryStatus->toJson();
    }


    public function postMarkEnquiryNotInterested()
    {
        $data = (object)Input::json();

        if (empty($data))
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);


        $enquiryId = isset($data->enquiryId) ? $data->enquiryId : null;
        $remarks = isset($data->remarks) ? $data->remarks : null;
        $enquiryStatus = $this->enquiryRepo->markEnquiryNotInterested($enquiryId, $remarks);

        if ($enquiryStatus == false)
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        return $enquiryStatus->toJson();
    }


    public function postCreateFollowup()
    {
        $data = (object)Input::json();

        if (empty($data))
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);


        $enquiryId = isset($data->enquiryId) ? $data->enquiryId : null;
        $remarks = isset($data->remarks) ? $data->remarks : null;
        $followupDate = isset($data->followupDate) ? new DateTime($data->followupDate) : null;
        $enquiryStatus = $this->enquiryRepo->createFollowup($enquiryId, $followupDate, $remarks);

        if ($enquiryStatus == false)
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        return $enquiryStatus->toJson();
    }


}