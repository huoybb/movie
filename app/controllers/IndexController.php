<?php

use Carbon\Carbon;

class IndexController extends myController
{

    public function indexAction()
    {
        $movies = Movies::find();
        $this->view->movies = $movies;
    }

}

