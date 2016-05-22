<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/7/16
 * Time: 13:44
 */
use Carbon\Carbon;
use Phalcon\Mvc\Model;

abstract class myModel extends Model{

//    public $comments = null;

    /*
     * 仿照Laravel对时间的数据进行处理，便于将来的使用
     */

//    public function beforeCreate()
//    {
//        $this->created_at = Carbon::now()->toDateTimeString();
//        $this->updated_at = Carbon::now()->toDateTimeString();
//    }
    public abstract function columnMap();
    public function beforeSave()
    {
        if(isset($this->created_at)) {
            if(is_a($this->created_at,'Carbon\Carbon')) {
                $this->created_at = $this->created_at->toDateTimeString();
            }
        }else{
            $this->created_at = Carbon::now()->toDateTimeString();
        }

        $this->updated_at = Carbon::now()->toDateTimeString();
        $this->getEventMethodsAndExecute('|beforeSave.+|');
    }

    public function afterFetch()
    {
        if(isset($this->created_at)) $this->created_at = Carbon::parse($this->created_at);
        if(isset($this->updated_at)) $this->updated_at = Carbon::parse($this->updated_at);
        $this->getEventMethodsAndExecute('|afterFetch.+|');
    }

    public function getClassName()
    {
        return get_class($this);
    }

    //-------事件处理的相关函数---------
    /*
     * 利用反射功能，将所有delete*类的函数都运行以便，以便能够清楚相关的项目，如评论、标签、关键词、收藏、列表、季数等被trait类加入到本对象中的附加操作对象
     * 需要注意的是要避免重复，以及在构建delete*函数时，需要加以注意。
     * 这种形式类似钩子的功能，也是面向切面编程对我的启发！
     */
    public function beforeDelete()
    {
        $this->getEventMethodsAndExecute('|delete.+Hook|');
    }

    public function afterSave()
    {
        $this->getEventMethodsAndExecute('|afterSave.+|');
    }

    /**获取时间函数并执行，例如删除前***的函数，就可以通过这种函数的模式定义来获取并执行，从而尽量做到避免耦合
     * 利用反射的功能来提供解耦的解决方案
     * @param $format
     */
    private function getEventMethodsAndExecute($format){
        $hooks = [];
        foreach($this->getMethods() as $method){
            if(preg_match($format,$method->name)) $hooks[]=$method->name;
        }
        foreach($hooks as $method){
            $this->{$method}();
        }
    }


    /**
     * 获取当前对象的所有方法
     * @return ReflectionMethod[]
     */
    private function getMethods(){
        $r = new ReflectionClass($this);
        return $r->getMethods();
    }

    /**这个函数可以作为将来的array_intersect的string化的函数，以便于判断两个对象是否相同
     * @return string
     */
    public function __toString()
    {
        return get_class($this).'::'.$this->id;
    }

    public static function getDataTypes()
    {
        $instance  = new static();
        $meta = $instance->getModelsMetaData();
        $types = $meta->getDataTypes($instance);
        return $types;
    }


} 