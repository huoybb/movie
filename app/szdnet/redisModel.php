<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/5/17
 * Time: 13:52
 */

namespace szdnet;



use Phalcon\Di;
use Phalcon\Di\Injectable;

class redisModel  implements \ArrayAccess{

    use RowModel;

    public   $id = null;
    public   $redis = null;
    private  $attributes=[];

    function __construct($id = null)
    {
        $this->redis = Di::getDefault()->getShared('redis');//这里是否会出现一大堆connection呢？怎么能够查询出来呢？
        if(method_exists($this,'init') AND null <> $id){
            $this->init($id);
        }

    }
    public function init($id)
    {
        $key = $this->getKey($id);
//        dd($key);
        $this->attributes = $this->redis->hgetall($key);
        $this->id = $id;//这里其实不用设置这个值的吧?
        return $this;
    }


    //----------魔术方法，学习Laravel的做法-------

    public function __get($key)
    {
        if(isset($this->attributes[$key])){
            return $this->getAttribute($key);
        }else{
            return $this->$key;
        }
    }
    public function __set($key, $value)
    {
        if(isset($this->$key)){
            $this->$key = $value;
        }else{
            $this->setAttribute($key, $value);
        }

    }



    public function __unset($key){
        unset($this->attributes[$key]);
    }

    public function __toString()
    {
        return $this->toJson();
    }
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function toArray()
    {
        return $this->attributes;
    }


//    ---------------实施ArrayAccess的相关方法----------------
    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset];
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

//    --------------属性设置与获取的三个方法----------
    public  function getAttribute($key)
    {
        return $this->attributes[$key];
    }

    public  function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function getAttributeArray()
    {
        return $this->attributes;
    }





}