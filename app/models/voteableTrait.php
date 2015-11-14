<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/11/14
 * Time: 16:06
 */
trait voteableTrait
{
    private $votes = [];
    public function getAllVotes()
    {
        if([]==$this->votes) $this->votes = Vote::query()
            ->where('voteable_type = :type:',['type'=>get_class($this)])
            ->andWhere('voteable_id = :id:',['id'=>$this->id])
            ->execute();
        return $this->votes;
    }
    public function countVotes()
    {
        $support = [];$deny = [];
        foreach($this->getAllVotes() as $vote){
            if($vote->vote == 'yes') {
                $support[]=$vote;
            }else{
                $deny[]=$vote;
            }
        }
        return ['support'=>count($support),'deny'=>count($deny)];
    }

    /**
     * @param $vote : 这个参数应该是yes或者no，以便能够统一统计
     * @param Users $user
     */
    public function addVoteBy($YesOrNo,Users $user)
    {
        if(!$this->isVotedBy($user)){
            $vote = new Vote();
            $vote->voteable_type = get_class($this);
            $vote->voteable_id = $this->id;
            $vote->user_id = $user->id;
            $vote->vote = $YesOrNo;
            $vote->save();
        }
        return $this;
    }

    public function isVotedBy(Users $user = null)
    {
        if(null == $user) $user = \Phalcon\Di::getDefault()->get('auth');
        return Vote::query()
            ->where('voteable_type = :type:',['type'=>get_class($this)])
            ->andWhere('voteable_id = :id:',['id'=>$this->id])
            ->andWhere('user_id = :user:',['user'=>$user->id])
            ->execute()->count() > 0;
    }


    public function deleteAllVotes()
    {
        $this->getAllVotes()->delete();
        return $this;
    }

    public function deleteVotesHook()
    {
        $this->deleteAllVotes();
        return $this;
    }




}