<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/7/21
 * Time: 22:02
 */

class TagValidator extends myValidation{
    public function initialize()
    {
        $this->add('tagName',new \Phalcon\Validation\Validator\PresenceOf([
            'message'=>'标签不能为空！'
        ]));
    }
} 