<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 19/1/13
 * Time: 8:25 PM
 * To change this template use File | Settings | File Templates.
 */
class Enquiry extends Eloquent
{

    public static $factory = array(
        'name' => 'string',
        'program' => 'string',
        'type' => 'string',
        'enquiryDate' => '1 Jan 2013',
        'user_id' => 'factory|User',
        'branch_id' => 'factory|Branch'
    );

    public function enquiryStatus()
    {
        //default order for demo status
//        return $this->hasMany('EnquiryStatus');
        return $this->hasMany('EnquiryStatus')->orderBy('created_at', 'desc');
    }

    public function branch()
    {
        return $this->belongsTo('Branch');
    }

    public function student()
    {
        return $this->belongsTo('Student');
    }

    public function createdBy()
    {
        return $this->belongsTo('User');
    }


}
