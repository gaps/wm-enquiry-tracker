<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 19/1/13
 * Time: 8:26 PM
 * To change this template use File | Settings | File Templates.
 */
class EnquiryStatus extends Eloquent
{
    const CREATED = "created";
    const ENROLLED = "enrolled";
    const NOT_INTERESTED = "not_interested";
    const FOLLOW_UP = "follow_up";

    protected $table = 'enquiryStatus';

    public static $factory = array(
        'status' => EnquiryStatus::CREATED,
        'remarks' => 'text',
        'enquiry_id' => 'factory|Enquiry',
        'followupDate' => '1 Jan 2013',
        'joiningDate' => '1 Jan 2013'
    );

    public function enquiry()
    {
        return $this->belongsTo('Enquiry');
    }
}
