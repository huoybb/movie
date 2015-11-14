<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/7/22
 * Time: 0:02
 */

class LinkValidator extends myValidation{
    public function initialize()
    {
        $this->add('url',new \Phalcon\Validation\Validator\PresenceOf([
            'message'=>'url地址不能为空'
        ]));
    }
} 