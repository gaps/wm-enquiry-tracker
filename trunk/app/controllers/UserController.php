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

    public function getLogin()
    {
        return View::make('user.login');
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
                    'url' => URL::to('#/enquiry/list')
                )
            );
        } else
            return Response::json(array(
                'status' => false
            ));
    }


    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('#/user/login');
    }

}