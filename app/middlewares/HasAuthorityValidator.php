<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/8/15
 * Time: 22:10
 */

class HasAuthorityValidator extends  myValidation{
    public function ifAuthHasAuthorityOver($ojbect)
    {
        return $this->session->get('auth')['id'] == $ojbect->user_id;
    }

    public function isValid($object)
    {
        return $this->ifAuthHasAuthorityOver($object);
    }


    public function initialize()
    {
        $this->redirectUrl = 'http://myphalcon2/auth/notFinded';
        $this->excludedRoutes = ['auth.login'];
    }
} 