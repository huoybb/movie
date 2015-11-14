<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/10/17
 * Time: 15:40
 */
trait commentableTrait
{
    /**
     * 获取该模型的所有评论
     * 这个其实也可以变成一个通用的方法落实在myModel中的
     * @return null|\Phalcon\Mvc\Model\ResultsetInterface
     */
    private $comments = null;
    public function comments()
    {
        if($this->comments == null) $this->comments = Comments::query()
            ->where('commentable_id = :tag_id:')
            ->andWhere('commentable_type = :type:')
            ->bind(['tag_id'=>$this->id,'type'=>get_class($this)])
            ->orderBy('updated_at Desc')
            ->execute();
        return $this->comments;
    }

    public function addCommentBy(Users $user,$data)
    {
        $comment = new Comments();
        $comment->content=$data['content'];
        $comment->commentable_id = $this->id;
        $comment->commentable_type = get_class($this);
        $comment->user_id = $user->id;
        $comment->save();
        return $this;
    }
    public function deleteComments()
    {
        $this->comments()->delete();
        return $this;
    }
    public function deleteCommentsHook()
    {
        $this->deleteComments();
        return $this;
    }


}