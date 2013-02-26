<?php
/**
 * Created by JetBrains PhpStorm.
 * User: naveen
 * Date: 19/1/13
 * Time: 8:14 PM
 * To change this template use File | Settings | File Templates.
 */
class Role extends Eloquent
{
    public function users()
    {
        $this->belongsToMany('User');
    }
}
