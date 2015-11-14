<?php

class Taggables extends myModel
{

    /**
     *
     * @var integer
     */
    public $id;
    /**
     *
     * @var integer
     */
    public $tag_id;


    public $user_id;

    /**
     *
     * @var integer
     */
    public $taggable_id;

    /**
     *
     * @var string
     */
    public $taggable_type;

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
        $this->setSource('Taggables');
        $this->belongsTo('tag_id', 'Tags', 'id', array('alias' => 'Tags'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id'=>'id',
            'tag_id' => 'tag_id',
            'user_id' => 'user_id',
            'taggable_id' => 'taggable_id',
            'taggable_type' => 'taggable_type', 
            'created_at' => 'created_at', 
            'updated_at' => 'updated_at'
        );
    }
    public function getFromMovieAndTag(Movies $movie,Tags $tag)
    {
        return $this->query()
            ->where('tag_id = :tag_id:',['tag_id'=>$tag->id])
            ->andWhere('taggable_id = :movie_id:',['movie_id'=>$movie->id])
            ->andWhere('taggable_type = :type:',['type'=>'Movies'])
            ->execute()[0];
    }

    public function getTaggedObject()
    {
        $className = $this->taggable_type;
        return $className::findFirst($this->taggable_id);
    }



}
