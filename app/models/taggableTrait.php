<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/10/17
 * Time: 16:28
 */
trait taggableTrait
{
    /**下面三个函数与tag相关，应该抽象出来移植到myModel中去，这样每个继承的类都有这种方法啦
     * @return null|\Phalcon\Mvc\Model\ResultsetInterface
     */
    private $tags = null;
    private $tagsWithCounts = null;
    public function tags()
    {
        if($this->tags == null) $this->tags = Tags::query()
            ->rightJoin('Taggables','Tags.id = Taggables.tag_id')
            ->leftJoin('Users','Taggables.user_id = Users.id')
            ->where('Taggables.taggable_type = :type:',['type'=>get_class($this)])
            ->andWhere('Taggables.taggable_id = :id:',['id'=>$this->id])
            ->columns(['Tags.id','Taggables.id AS taggable_id','Tags.name AS name','Taggables.created_at','Users.name AS userName','Taggables.user_id'])
            ->execute();
        return $this->tags;
    }

    public function tagsWithCounts()
    {
        if(null == $this->tagsWithCounts) $this->tagsWithCounts = Tags::query()
            ->rightJoin('Taggables','Tags.id = Taggables.tag_id')
            ->leftJoin('Users','Taggables.user_id = Users.id')
            ->where('Taggables.taggable_type = :type:',['type'=>get_class($this)])
            ->andWhere('Taggables.taggable_id = :id:',['id'=>$this->id])
            ->groupBy('Tags.id')
            ->columns(['Tags.id','Tags.name AS name','COUNT(Users.name) AS counts'])
            ->execute();
        return $this->tagsWithCounts;
    }


    /**
     * 为当前对象增加标签，如果存在，则直接返回；接受的参数是标签名称
     * @param $tagName
     * @return \Phalcon\Mvc\Model|Tags
     */
    public function addTag($tagName,Users $byUser)
    {
        $tag =(new Tags())->getTagByName($tagName);
        $taggables = Taggables::query()
            ->where('tag_id = :tag_id:',['tag_id'=>$tag->id])
            ->andWhere('taggable_id = :taggable_id:',['taggable_id'=>$this->id])
            ->andWhere('taggable_type = :type:',['type'=>get_class($this)])
            ->andWhere('user_id = :user:',['user'=>$byUser->id])
            ->execute();

        if(count($taggables) == 0){
            $taggable = new Taggables();
            $taggable->tag_id = $tag->id;
            $taggable->taggable_id = $this->id;
            $taggable->taggable_type = get_class($this);
            $taggable->user_id = $this->getDI()->getShared('session')->get('auth')['id'];
            $taggable->save();
        }

        return $tag;
    }

    public function deleteTag(Tags $tag = null)
    {
        $query = Taggables::query()
            ->Where('taggable_id = :taggable_id:',['taggable_id'=>$this->id])
            ->andWhere('taggable_type = :type:',['type'=>get_class($this)])
            ->andWhere('user_id = :user:',['user'=>$this->getDI()->get('session')->auth['id']]);
        if($tag) $query=$query->andWhere('tag_id = :tag_id:',['tag_id'=>$tag->id]);

        $taggables = $query
            ->execute();
        foreach($taggables as $taggable){
            $taggable->delete();
        }
        return $this;
    }

    public function deleteTagHook()
    {
        $this->deleteTag();
        return $this;
    }

}