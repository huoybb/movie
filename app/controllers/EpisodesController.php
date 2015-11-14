<?php

class EpisodesController extends myController
{

    public function indexAction()
    {

    }

    public function addCommentAction(Episodes $episode)
    {
        $episode->addCommentBy($this->auth,$this->request->getPost());
        return $this->redirectBack();
    }

    public function editCommentAction(Episodes $episode,Comments $comment)
    {
        if($this->request->isPost()){
            $comment->update($this->request->getPost());
            return $this->redirectByRoute(['for'=>'movies.showEpisode','movie'=>$episode->getMovie()->id,'episode'=>$episode->id]);
        }
        $this->view->comment = $comment;
        $this->view->form = myForm::buildCommentForm($episode,$comment);
    }

    public function deleteCommentAction(Episodes $episode,Comments $comment)
    {
        if($this->auth->has($comment)) $comment->delete();
        return $this->redirectByRoute(['for'=>'movies.showEpisode','movie'=>$episode->getMovie()->id,'episode'=>$episode->id]);
    }




}

