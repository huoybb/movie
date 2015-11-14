<?php

use Phalcon\Forms\Form;

class TagsController extends myController
{

    public function indexAction($page =1)
    {
//        $this->view->tags = Tags::getTagsInfo();
        $this->view->page = $this->getPaginator(Tags::getTagsInfo(),20,$page);

    }
    public function showAction(Tags $tag,$page = 1)
    {

//        dd($tag->users()->toArray());
        $this->view->mytag =$tag;
//        $this->view->page = $this->getMoviePage($page,$tag->movies());
        $this->view->page = $this->getPaginator($tag->movies(),20,$page);
        $this->view->form = $this->buildCommentForm($tag);
        $this->view->comments = $tag->comments();
    }

    public function editAction(Tags $tag)
    {
        if($this->request->isPost()){
            $data = $this->request->getPost();
            $tag->update($data);
            $this->redirectByRoute(['for'=>'tags.show','tag'=>$tag->id]);
        }
        $this->view->myTag = $tag;
        $this->view->form = $this->buildFormFromModel($tag);
    }


    public function addCommentAction(Tags $tag)
    {
//        dd($tag);
        if($this->request->isPost()){
            $data = $this->request->getPost();
            $this->saveCommentTo($tag,$data);
            $this->redirectByRoute(['for'=>'tags.show','tag'=>$tag->id]);
        }
    }

    private function buildCommentForm(Tags $tag,Comments $comment = null)
    {
        if($comment){
            $form = new Form($comment);
            $form->Url = "tags/".$tag->id."/comments/".$comment->id."/edit";
        }else{
            $form = new Form();
            $form->Url = "tags/".$tag->id."/addComment";
        }
        $form->add(new \Phalcon\Forms\Element\TextArea('content'));
        $form->add(new \Phalcon\Forms\Element\Submit('Add Comment'));
        return $form;
    }

}

