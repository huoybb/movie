<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/5/13
 * Time: 23:06
 */

namespace szdnet;


class tag extends redisModel{
    use RowModel;
    public $format = ['name','time','description','keywords'];

    public function _addNew()
    {
        $key = $this->getKey($this->name);
        $this->redis->set($key,$this->id);
        return $this;
    }
    public function findByName($name)
    {
        $key = $this->getKey($name);
        $key = $this->redis->get($key);
        if($key) return new self($key);
        return null;
    }


    public function books()
    {
        return $this->getHasManyObjects('szdnet\book');
    }

    public function comments()
    {
        return $this->getHasManyObjects('szdnet\comment');
    }



}