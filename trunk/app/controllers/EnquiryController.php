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
        $this->beforeFilter('auth');
    }

    /**
     * Get view for viewing list of demo for authenticated user
     * @return Laravel\View
     */
    public function getList()
    {
        return View::make('enquiry.list');
    }

    public function getBranches()
    {
        $branchesArray = array();
        $branches = Auth::user()->branches()->get();
        foreach ($branches as $branch) {
            $branchesArray[] = array('name' => $branch->name, 'id' => $branch->id);
        }

        return Response::json($branchesArray);

    }

    public function getFollowups()
    {
        return View::make('followups/list');
    }

    public function getTypes()
    {
        return Response::json(Util::getTypes());
    }

    public function getStatuses()
    {

        return Response::json(Util::getStatus());
    }

    public function getCourses()
    {
        return Response::json(Util::getCourses());
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
        $newEnquiry = $this->enquiryRepo->createEnquiry($name, $mobile, $email, $date, $program, $type, Auth::user()->id, $branch_id);
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
        $pageCount = isset($data->pageCount) ? $data->pageCount : Constants::PAGECOUNT;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : 1;
        $skip = $pageCount * ($pageNumber - 1);
        try {
            $enquiries = $this->enquiryRepo->getEnquiries($branchIds, $status, $types, $fromDate, $toDate, $skip, $pageCount);
        } catch (PDOException $e) {
            Log::exception($e);
            return Response::make(Lang::get('error.bad'), Constants::DATABASE_ERROR_CODE);
        }

        if (!is_array($enquiries) && !$enquiries)
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        else if (empty($enquiries)) {
            return Response::json($enquiries);
        }

        return $enquiries->toJson();


    }


    public function postGetFollowups()
    {
        $data = (object)Input::json();

        if (empty($data)) {
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);
        }

        $fromDate = !isset($data->fromDate) || empty($data->fromDate) ? new DateTime('now') : Util::getFromDate(new DateTime($data->fromDate));
        $toDate = !isset($data->toDate) || empty($data->toDate) ? new DateTime('now') : Util::getToDate(new DateTime($data->toDate));
        $branchIds = isset($data->branchIds) ? $data->branchIds : array();
        $types = isset($data->types) ? $data->types : array();
        $pageCount = isset($data->pageCount) ? $data->pageCount : Constants::PAGECOUNT;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : 1;
        $skip = $pageCount * ($pageNumber - 1);
        try {
            $enquiries = $this->enquiryRepo->getFollowUps($fromDate, $toDate, $types, $branchIds, $skip, $pageCount);
        } catch (PDOException $e) {
            Log::exception($e);
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);
        }

        if (!is_array($enquiries) && !$enquiries)
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        else if (empty($enquiries)) {
            return Response::json($enquiries);
        }

        return $enquiries->toJson();
    }

    public function postGetExportEnquiries()
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
        $pageCount = isset($data->pageCount) ? $data->pageCount : PHP_INT_MAX;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : 0;
        $skip = $pageNumber > 0 ? $pageCount * ($pageNumber - 1) : 0;
        try {
            $enquiries = $this->enquiryRepo->getEnquiries($branchIds, $status, $types, $fromDate, $toDate, $skip, $pageCount);
        } catch (PDOException $e) {
            Log::exception($e);
            return Response::json(array('status' => false));
        }
        if (!is_array($enquiries) && !$enquiries)
            return Response::json(array('status' => false));
        if (is_array($enquiries) && count($enquiries) == 0)
            return Response::json(array('status' => false));

        $csvData = Util::ConvertEnquiriesToCSV($enquiries);
        $filePath = Util::generateTempFilePath("csv");
        File::put(Util::convertToAbsoluteURL($filePath), $csvData);
        return Response::json(array('status' => true, 'filePath' => Util::convertToHttpURL($filePath)));
    }


    public function postGetExportFollowups()
    {
        $data = (object)Input::json();

        if (empty($data)) {
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);
        }

        $fromDate = !isset($data->fromDate) || empty($data->fromDate) ? new DateTime('now') : Util::getFromDate(new DateTime($data->fromDate));
        $toDate = !isset($data->toDate) || empty($data->toDate) ? new DateTime('now') : Util::getToDate(new DateTime($data->toDate));
        $branchIds = isset($data->branchIds) ? $data->branchIds : array();
        $types = isset($data->types) ? $data->types : array();
        $pageCount = isset($data->pageCount) ? $data->pageCount : PHP_INT_MAX;
        $pageNumber = isset($data->pageNumber) ? $data->pageNumber : 0;
        $skip = $pageNumber > 0 ? $pageCount * ($pageNumber - 1) : 0;

        try {
            $enquiries = $this->enquiryRepo->getFollowUps($fromDate, $toDate, $types, $branchIds, $skip, $pageCount);
        } catch (PDOException $e) {
            Log::exception($e);
            return Response::json(array('status' => false));
        }

        if (!is_array($enquiries) && !$enquiries)
            return Response::json(array('status' => false));
        if (is_array($enquiries) && count($enquiries) == 0)
            return Response::json(array('status' => false));

        $csvData = Util::ConvertFollowupToCSV($enquiries);
        $filePath = Util::generateTempFilePath("csv");
        File::put(Util::convertToAbsoluteURL($filePath), $csvData);
        return Response::json(array('status' => true, 'filePath' => Util::convertToHttpURL($filePath)));

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

    public function getEdit()
    {
        return View::make('enquiry/edit')->with('branches',Auth::user()->branches()->get());

    }

    public function postGetEnquiry()
    {
        $data = (object)Input::json();

        if (empty($data))
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);
        $enquiryId = isset($data->enquiryId) ? $data->enquiryId : null;
        return Enquiry::find($enquiryId)->toJson();
    }

    public function postUpdateEnquiry()
    {
        $data = (object)Input::json();

        if (empty($data))
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);

        $enquiry_id = $data->enquiryId;
        $name = isset($data->name) ? $data->name : "";
        $mobile = isset($data->mobile) ? $data->mobile : NULL;
        $email = isset($data->email) ? $data->email : NULL;
        $date = isset($data->date) ? new DateTime($data->date) : new DateTime('now');
        $program = isset($data->program) ? $data->program : "";
        $branch_id = $data->branch_id;
        $type = isset($data->type) ? $data->type : "";
        $updatedEnquiry = $this->enquiryRepo->updateEnquiry($enquiry_id, $branch_id, $name, $mobile, $email, $date, $program, $type);

        if ($updatedEnquiry == false)
            return Response::make(Lang::get('error.database'), Constants::DATABASE_ERROR_CODE);

        return $updatedEnquiry->toJson();
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