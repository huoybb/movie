<?php

class UsersController extends myController
{

    public function indexAction($page)
    {
        $this->view->page = $this->getPaginator(Users::find(),10,$page);
    }
    public function showAction(Users $user)
    {
        $this->view->user = $user;
    }

    public function showCommentsAction(Users $user)
    {
        $this->view->user = $user;

    }

    public function editAction(Users $user)
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            if ($user->update($data)) {
                $this->redirectByRoute(['for'=>'users.show','user'=>$user->id]);
            }
        }

        $this->view->form = $this->buildFormFromModel($user);
        $this->view->user = $user;
    }


    public function showTagsAction(Users $user,$page =1)
    {
//        dd($user->tags()->getFirst()->taggables->getTaggedObject());
//        dd($user->tags()->toArray());
        $this->view->user = $user;
        $this->view->page = $this->getPaginator($user->taglogs(),15,$page);
    }
    public function deleteTagAction(Users $user,Taggables $tag)
    {
        if($this->request->isPost()){
            $tag->delete();
            return $this->redirectByRoute(['for'=>'users.showTags','user'=>$user->id]);
        }
    }


    public function showLinksAction(Users $user,$page =1)
    {
        $this->view->user = $user;
        $this->view->page = $this->getPaginator($user->links(),15,$page);
    }

    public function deleteLinkAction(Users $user,Links $link)
    {
        if ($this->request->isPost()) {
            $link->delete();
            return $this->redirectByRoute(['for'=>'users.showLinks','user'=>$user->id]);
        }
    }

    public function showFavoritesAction(Users $user,$page = 1)
    {
        $this->view->user = $user;
        $this->view->page = $this->getPaginator($user->favorites(),15,$page);
    }

    public function voteForCommentAction(Comments $comment,$YesOrNo)
    {
        $comment->addVoteBy($YesOrNo,$this->auth);
        return $this->redirectBack();
    }






}

