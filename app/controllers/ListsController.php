<?php

class ListsController extends myController
{

    public function indexAction()
    {

    }
    public function addAction()
    {

    }
    public function showAction(Lists $list,$page = 1)
    {
        $this->view->list = $list;
        $this->view->page = $this->getPaginator($list->getRelatedMovies(),20,$page);
    }


    public function addToMovieAction(Movies $movie,\Phalcon\Http\Request $request)
    {
        //@todo 需要增加验证的环节
        $movie->createAndAddToListFromRequest($request);
        return $this->redirectByRoute(['for'=>'movies.addToList','movie'=>$movie->id]);
    }



}

