<?php

use Carbon\Carbon;

class Comments extends myModel
{
//    use KeywordableTrait;

    use voteableTrait;
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $commentable_id;

    /**
     *
     * @var string
     */
    public $commentable_type;

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
     *
     * @var integer
     */
    public $user_id;

    private $commentable = null;
    private $user = null;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'content' => 'content',
            'commentable_id' => 'commentable_id',
            'commentable_type' => 'commentable_type',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'user_id' => 'user_id'
        );
    }


    public function user()
    {
        if(null == $this->user){
            $this->user = Users::findFirst($this->user_id);
        }
        return $this->user;
    }

    public function commentable()
    {
        if(null == $this->commentable){
            $className = $this->commentable_type;
//            dd($className);
            $this->commentable = $className::findFirst($this->commentable_id);
        }
        return $this->commentable;
    }

    public function getEditURL()
    {

        return "http://myphalcon2/{$this->getCommentableType()}/{$this->commentable_id}/comments/{$this->id}/edit";
    }

    public function getDeleteURL()
    {
        return "http://myphalcon2/{$this->getCommentableType()}/{$this->commentable_id}/comments/{$this->id}/delete";
    }


    private function getCommentableType()
    {
        return strtolower($this->commentable_type);
    }


}
