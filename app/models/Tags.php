<?php

class Tags extends myModel
{
    use commentableTrait;

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('Tags');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'name' => 'name', 
            'created_at' => 'created_at', 
            'updated_at' => 'updated_at'
        );
    }
    public function getTagByName($name)
    {
        $tag = Tags::findFirst(['conditions'=>'name = :name:','bind'=>['name'=>$name]]);
        if(null == $tag){
            $tag = new Tags();
            $tag->name = $name;
            $tag->save();
        }
        return $tag;
    }

    public function users()
    {
        return Users::query()
            ->rightJoin('Taggables','Taggables.user_id = Users.id')
            ->leftJoin('Tags','Tags.id = Taggables.tag_id = Tags.id')
            ->where('Taggables.tag_id = :tag:',['tag'=>$this->id])
            ->groupBy('Users.id')
            ->execute();
    }


    public function movies()
    {
        //@todo 为什么此处返回的不是Moives的对象呢？这里比较奇怪！
        $movies = Movies::query()
            ->rightJoin('Taggables','Taggables.taggable_id = Movies.id')
            ->where('Taggables.tag_id = :id:',['id'=>$this->id])
            ->andWhere('Taggables.taggable_type = :type:',['type'=>'Movies'])
            ->groupBy('Movies.id')
//            ->columns(['Movies.id','Movies.title','Movies.poster','Movies.director','Movies.release_time','Taggables.created_at'])
            ->execute();
        return $movies;
    }



    static public function getTagsInfo()
    {
        return self::query()
            ->join('Taggables','Taggables.tag_id = Tags.id')
            ->orderBy('movieCounts DESC')
            ->groupBy('Tags.id')
            ->columns(['Tags.id','Tags.name','Tags.created_at','movieCounts'=>'COUNT(*)'])
            ->execute();
    }

    public function toString()
    {
        return $this->name;
    }






}
