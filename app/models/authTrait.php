<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/11/8
 * Time: 17:25
 */
trait authTrait
{

    //登录的相关操作
    public function setLastLogin()
    {
        $data = [
            'user_id'=>$this->id
        ];

        return (new Loginlogs())->save($data);

    }

    public function getLastLoginTime()
    {
        $lastLogin = Loginlogs::query()
            ->where('user_id = :user:',['user'=>$this->id])
            ->orderBy('id DESC')
            ->limit(2)
            ->execute();
//        dd($lastLogin[0]);

        if($this->getDI()->get('session')->auth['id'] == $this->id){
            return $lastLogin[1]->created_at->diffForHumans();
        }else{
            return $lastLogin[0]->created_at->diffForHumans();
        }

    }

    /**判断是否拥有某个资源，object可以是电影、评论、链接、标签等
     * @param $object
     * @return bool
     */
    public function has($object)
    {
        return $this->id == $object->user_id;
    }
}