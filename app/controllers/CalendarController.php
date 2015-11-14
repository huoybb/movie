<?php

class CalendarController extends myController
{

    public function latestAction()
    {
        $this->view->episodes = Episodes::getLatest(7);
//        dd($this->view->episodes);
//        (new \Carbon\Carbon())->now()->format('l')
    }
    public function myLatestAction()
    {
        $this->view->episodes = $this->auth->getMyLastedEpisodes(7);
//        foreach($this->view->episodes as $episode){
//            dd($episode->links);
//        }
    }

    public function MyWatchListAction()
    {
        $this->view->movies = $this->auth->getMyWatchListMovies();
//        dd($this->view->movies->toArray());
    }


}

