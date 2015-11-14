<?php
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/7/18
 * Time: 20:40
 */

class MovieValidator extends myValidation
{
    public function initialize()
    {
        $this->add('title',new PresenceOf(['message'=>'电影标题不能为空！']));
        $this->add('doubanid',new PresenceOf(['message'=>'doubanid不能为空！']));
    }

}
