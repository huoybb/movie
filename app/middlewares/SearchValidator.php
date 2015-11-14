<?php
use Phalcon\Validation\Validator\PresenceOf;
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/7/26
 * Time: 18:05
 */

class SearchValidator extends myValidation{

    public function initialize()
    {
        $this->add('search',new Phalcon\Validation\Validator\Regex([
            'pattern'=>'/[^\s]+/',
            'message'=>'搜索的关键词非法！'
        ]));
    }

}