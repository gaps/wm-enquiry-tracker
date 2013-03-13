<?php
/**
 * Created by JetBrains PhpStorm.
 * User: keshav
 * Date: 12/3/13
 * Time: 5:58 PM
 * To change this template use File | Settings | File Templates.
 */

class UserController extends BaseController
{
    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.common';


    public function getLogin()
    {
//        $this->layout->content = View::make('user.login');
        return View::make('user.login');
    }

    public function postTest(){
        return Response::json(array('name'=>'keshav'));
    }

    public function postLogin()
    {

        $data = (object)Input::json();

        if (empty($data))
            return Response::make(Lang::get('errors.bad'), Constants::BAD_REQUEST_CODE);

        $credentials = array(
            'email' => $data->email,
            'password' => $data->password
        );

        if (Auth::attempt($credentials)) {
            return Response::json(
                array(
                    'status' => true,
                    'url' => URL::to('/')
                )
            );
        } else
            return Response::json(array(
                'status' => false
            ));
    }


}