<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 19/1/13
 * Time: 8:12 PM
 * To change this template use File | Settings | File Templates.
 */
class Branch extends Eloquent
{
    public static $factory = array(
        "name" => 'text'
    );

    public function users()
    {
        return $this->belongsToMany('User');
    }

    public function demos()
    {
        return $this->hasMany('Enquiry', 'branch_id');
    }
}
