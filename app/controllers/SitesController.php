<?php

use Phalcon\Forms\Form;

class SitesController extends myController
{

    public function indexAction($page=1)
    {
        $this->view->page = $this->getPaginator(Sites::getSitesInfo(),20,$page);
    }

    public function showAction(Sites $site,$page=1)
    {
        $this->view->site = $site;
        $this->view->page = $this->getPaginator($site->getLinksInfo(),25,$page);
//        dd($this->view->page);
        $this->view->form = myForm::buildCommentForm($site);
        $this->view->comments = $site->comments();
    }
    public function editAction(Sites $site)
    {
        if($this->request->isPost()){
            $data = $this->request->getPost();
//            dd($data);
            $site->update($data);
            $this->redirectByRoute(['for'=>'sites.show','site'=>$site->id]);
        }
        $this->view->site =$site;
        $this->view->form = myForm::buildFormFromModel($site);
    }
    public function addCommentAction(Sites $site)
    {
        $site->addCommentBy($this->auth,$this->request->getPost());
        return $this->redirectByRoute(['for'=>'sites.show','site'=>$site->id]);
    }
    public function deleteCommentAction(Sites $site,Comments $comment)
    {
        if($this->auth->has($comment)) $comment->delete();
        return $this->redirectByRoute(['for'=>'sites.show','site'=>$site->id]);
    }
    public function editCommentAction(Sites $site,Comments $comment)
    {
        if($this->request->isPost()){
            $comment->update($this->request->getPost());
            $this->redirectByRoute(['for'=>'sites.show','site'=>$site->id]);
        }
        $this->view->comment = $comment;
        $this->view->form = myForm::buildCommentForm($site,$comment);
    }





}

