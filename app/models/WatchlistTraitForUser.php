<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/11/13
 * Time: 5:54
 */
trait WatchlistTraitForUser
{
    private $watchListMovies = [];

    public function getMyWatchListMovies()
    {
        if([] == $this->watchListMovies){
            $auth = \Phalcon\Di::getDefault()->get('auth');
            $this->watchListMovies = Watchlist::query()
//                ->leftJoin('Users','Users.id = Watchlist.user_id ')
                ->leftJoin('Movies','Movies.id = Watchlist.movie_id')
                ->leftJoin('Episodes','Episodes.id = Watchlist.currentEpisode_id')
                ->where('Watchlist.user_id = :user:',['user'=>$auth->id])
                ->andWhere('Watchlist.status != "done" ')
                ->orderBy('Watchlist.updated_at DESC')
                ->columns(['Watchlist.*','Movies.*','Episodes.*'])
                ->execute();
        }
        return $this->watchListMovies;
    }
}