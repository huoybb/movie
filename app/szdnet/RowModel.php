<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/5/19
 * Time: 19:12
 */

namespace szdnet;



trait RowModel {
//------------一些常见的放啊--------

    /**
     * 获取当前模型的redis的index key列表
     * @return string
     */
    public function getIndexKey()
    {
        return $this->getClassString().'index';
    }

    /**
     * 记录当前key位置的键值，每增加一个记录，则该记录应该增加1
     * @return string
     */
    public function getKeyIndex()
    {
        return $this->getClassString().'key';
    }
    public function getKeyIndexValue()
    {
        return $this->redis->get($this->getKeyIndex());
    }



    /**
     * 获得当前模型的redis的key
     * @param $id
     * @return string
     */
    public function getKey($id=null)
    {
        $id = $id ?: $this->id;
        $key = $this->getClassString().$id;
        if($this->redis->exists($key)) {
            return $key;
        }

        return $key.':main';
    }



//    ------------CRUD 操作---------
    /**
     * 获得所有制定index list之下的条目
     * @param null $indexKey
     * @return array
     */
    public function getAll($indexKey = null)
    {
        $indexKey = $indexKey ?: $this->getIndexKey();
//        dd($indexKey);
        $keys = $this->redis->lrange($indexKey,0,-1);
//        dd($keys);
        $results = [];
        foreach($keys as $key){
            $results[]= new self($key);
        }
//        dd($results);
        return $results;
    }


    /**
     * 这里其实应该设计成为如果不存在，则返回null
     *
     * @param $id
     * @return null|RowModel
     */
    public function find($id)
    {
        if($this->redis->exists($this->getKey($id))){
            return new self($id);
        }
        return null;

    }

    /**
     * 将目前条目信息保存到数据库中
     * @return $this
     */
    public function save()
    {

        $data=$this->getAttributeArray();
//        dd($data);
        $key = $this->getKey();
//        dd($key);
        $this->redis->hmset($key,$this->attributes);
        return $this;
    }

    /**利用数据data更新目前的条目信息
     * @param array $data
     * @return RowModel
     */
    public function update(array $data = null)
    {
//        dd($data);
        if($data){
            foreach($data as $key => $value){
                if(isset($this->attributes[$key]) || in_array($key,$this->format)){
                    $this->setAttribute($key,$value);
                }
            }
        }
//        dd($this->getAttributeArray());
        return $this->save();
    }


    /**
     * 删除本条记录
     * @todo 相关记录也需要一并删除
     * @return mixed
     */
    public function delete()
    {
        $key = $this->getKey();
        //首先，将与跟本对象关联的其他的关系也一并删除
        //   这里的关系有几种，一种是本条记录隶属其他对象，例如评论就一定隶属于图书或者其他的对象。
        event(new DeleteHasManyRelationshipForRedisModel($this));
        //   还有就是如果本条记录含有的关系，例如多对多关系，以及一对多关系
        //      例如，一本书删除的话，则该本书的所有评论术语一对多关系，则应该删除干净，而标签，则仅仅需要将关系删除即可
        //其次，将本条数据删除
        return $this->redis->del($key);
    }

    /**
     * 新增一条数据，并返回一个对象本身
     * @param $data
     * @return RowModel
     */
    public function addNew($data,$id = null)
    {
        $this->id = $id ?: $this->redis->incr($this->getKeyIndex());

        $this->addId2Index($this->id);

        //保存本条记录的数据
        $this->update($data);
        //看看是否需要额外增加的东西，可以通过_addNew方法来做到
        if(method_exists($this,'_addNew')){
            $this->_addNew();
        }
        return $this;
    }
    public function addId2Index($id)
    {
        $thisClassIndex = $this->getIndexKey();
//        dd($thisClassIndex);
        $this->redis->lrem($thisClassIndex,0,$id);//避免重复，先删除，再添加
        return $this->redis->lpush($thisClassIndex,$id);
    }


    /**
     * 将附件、评论等增加到本条目之下，list
     * @param $object
     * @return mixed
     */
    public function add(redisModel $object)
    {
        //获取object的名字，如"szdnet:book"变成"book"
        $objectName = $this->getClassShortName($object);

        //将object的id登记到本条目下的对应的list下
        $objectId = $object->id;
        $thisObjectIndex = preg_replace('|main|',$objectName,$this->getKey());
        return $this->redis->lpush($thisObjectIndex,$objectId);
    }

//    -----------设置与其他对象的关系------------

    public function hasMany(redisModel $object)
    {
        $thisObjectIndex = $this->getHasManyObjectIndex($object);
        $this->deleteHasMany($object)  //避免之前有重复，先删除，后增加
            ->redis->lpush($thisObjectIndex,$object->id);
        return $this;
    }



    public function deleteHasMany(redisModel $object)
    {
        $thisObjectIndex  = $this->getHasManyObjectIndex($object);
        $this->redis->lrem($thisObjectIndex,0,$object->id);
        return $this;
    }


    public function belongTo(redisModel $subject)
    {
        $objectType = get_class($subject);
        $objectId = $subject->id;
        $key = $this->getKey();
        $this->redis->hset($key,'objectType',$objectType);
        $this->redis->hset($key,'objectId',$objectId);
        return new self($this->id);
    }

//    ---------获得关系对象-----------
    public function getHasManyObjects($ObjectName=null)
    {
        if($ObjectName){
            $shortName = explode('\\',$ObjectName);
            $shortName = array_pop($shortName);
            $index = $this->getObjectIndex($shortName);
            return new hasMany($this,$ObjectName,$index);
        }
//        $thisIndex = preg_replace('|:main|','',$this->getKey());
//        $keys = $this->redis->keys($thisIndex.'*');
//        $results =[];
//        foreach($keys as $key){
//            $key = explode(':',$key);
//            $key = array_pop($key);
//
//            $ObjectName = preg_replace('|main|',$key,$this->getClassString());
//            $index = $this->getObjectIndex($key);
////            dd($index);
//
//            if($key <> 'main'){
//                $results[$key]= new hasMany($this,$ObjectName,$index);
//            }
//
//        }
//        return $results;
    }

    public function getBelongToObject()
    {
        if(isset($this['objectType'])){
            return new $this['objectType']($this['objectId']);
        }
        return null;
    }


    //    --------------helper function----------
    /**
     * 获取当前Class的名称前缀，为后续构建key做准备,如"szdnet:book:"
     * @return string
     */
    private function getClassString()
    {
        return preg_replace('|\\\|',':',get_class($this)).':';
    }

    private function getClassShortName($object){
        $names = explode('\\',get_class($object));
        $objectName = array_pop($names);
        return $objectName;
    }

    private function getObjectIndex($objectName)
    {
        return preg_replace('|main|',$objectName,$this->getKey());
    }

    /**获取当前对象的hasMany的index，以便能够对该列表进行更新
     * @param $object
     * @return mixed
     */
    private  function getHasManyObjectIndex($object)
    {
        //获取object的名字，如"szdnet:book"变成"book"
        $objectName = $this->getClassShortName($object);
        return preg_replace('|main|',$objectName,$this->getKey());
    }

} 