<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/5/13
 * Time: 22:50
 */

namespace szdnet;



class comment extends redisModel{
    use RowModel;
    public $format=['content','time','book','keywords'];

//    ----------Row方法---------
//这里最为简单的是否就是many-many的关系呢，这种隶属关系也可以用这种形式来做到吧？
//但有一个问题就是，到底是否有

    public function commentable()
    {
        return $this->getBelongToObject();//这里只能属于某一个类别
    }

}