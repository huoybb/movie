<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/8/13
 * Time: 16:10
 */
use Phalcon\Validation;

class loginValidator extends myValidation{
    public function initialize()
    {
        $this->add('email',new Validation\Validator\PresenceOf(['message'=>'邮件地址不能为空']));
        $this->add('password',new Validation\Validator\PresenceOf(['message'=>'密码不能为空']));
    }

} 