<?php
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/7/15
 * Time: 12:10
 */

class CommentValidator extends myValidation{

    public function initialize()
    {
        $this->redirectUrl = $_SERVER['HTTP_REFERER'];
        $this->add('content',new PresenceOf([
            'message'=>'评论内容不能为空！'
        ]));
    }

}