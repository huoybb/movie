<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/5/21
 * Time: 11:37
 */

namespace szdnet;

class hasMany {
    private  $redis = null;

    private $results =[];


    function __construct(redisModel $subject,$objectName,$index)
    {
        $this->redis = Redis::connection();
        $this->subject = $subject;
        $this->objectName = $objectName;
        $this->objectIndex = $index;
    }

    public function getall()
    {
        $keys = $this->redis->lrange($this->objectIndex,0,-1);
        $object = $this->objectName;
        foreach($keys as $key){
            $this->results[]=new $object($key);
        }
        return $this->results;
    }
    public function create($data)
    {
        $object = $this->results[]=(new $this->objectName())->addNew($data);
        $this->relate($object);
        return $object;
    }
    public function relate(redisModel $object)
    {
        $this->subject->add($object);
        $object->add($this->subject);
        return $this;
    }







}