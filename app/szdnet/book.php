<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/5/13
 * Time: 22:32
 */
namespace szdnet;

class book extends redisModel {
    use RowModel;
    public $format = ['title','writer','isbn','price','category','category','pages','picture','reference','updatedAt','serials','summary','url','keywords'];

    public function init($isbn)
    {
        parent::init($isbn);
        $this->doubanUrl = 'http://book.douban.com/isbn/'.$isbn;

//        dd($this->getKey());
    }


//    --------------relationship的设置-----------
    public function comments()
    {
        return $this->getHasManyObjects('szdnet\comment');
    }


    public function tags()
    {
        return $this->getHasManyObjects('szdnet\tag');

    }





}