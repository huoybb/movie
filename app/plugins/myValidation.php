<?php
use Phalcon\Validation;

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/7/18
 * Time: 21:32
 */

class myValidation extends Validation {
    public $redirectUrl = NULL;//能够自定义返回的url，或者默认为上一个url
    public $excludedRoutes = [];//对于每个路由都是用的如auth等，设置的一个排除路由的设置
    public function isValid($data)
    {
        $messages = $this->validate($data);
        if(count($messages)){
            foreach($messages as $message){
                $this->flash->error($message->getMessage());
            }
            return false;
        }else{
            return true;
        }
    }

    /**如果有设置，则返回设置地址，否则返回上一个页面
     * @return mixed
     */
    public function getRedirectedUrl()
    {
        return  $this->redirectUrl ?:$_SERVER['HTTP_REFERER'];
    }

} 