<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/11/12
 * Time: 21:02
 */
trait WatchlistTrait
{

    public function addToWatchListBy(Users $user)
    {
        $watchList = Watchlist::findOrCreateNew($this,$user);

        if($watchList->status == 'done') $watchList = new Watchlist();

        $watchList->save([
            'movie_id'=>$this->id,
            'user_id'=>$user->id,
            'status'=>'want'
        ]);
        return $this;
    }
    private $watchList = null;
    public function getWatchList()
    {
        if(null == $this->watchList){
            $user = \Phalcon\Di::getDefault()->get('auth');
            $this->watchList = Watchlist::findOrCreateNew($this,$user);
        }
        return $this->watchList;
    }

    public function hasWatchList()
    {
        return $this->getWatchList()->id != null;
    }

    public function isBeingWatched()
    {
        return $this->getWatchList()->status == 'doing';
    }

    private $lastWatchedEpisode = null;
    public function getLastWatchedEpisode()
    {
        if(null == $this->lastWatchedEpisode) $this->lastWatchedEpisode = Episodes::findFirst('id = "'.$this->getWatchList()->currentEpisode_id.'"');
        return $this->lastWatchedEpisode;
    }

    private $allWatchlist = [];
    public function getAllWatchlist()
    {
        if($this->allWatchlist == []){
            $this->allWatchlist = Watchlist::query()
                ->where('movie_id = :movie:',['movie'=>$this->id])
                ->execute();
        }
        return $this->allWatchlist;
    }

    private $watchedRecords = [];
    public function getWatchedRecords()
    {
        if([]==$this->watchedRecords){
            $this->watchedRecords = Watchlist::query()
                ->leftJoin('Episodes','Episodes.id = Watchlist.currentEpisode_id')
                ->where('Watchlist.movie_id = :movie:',['movie'=>$this->id])
                ->andWhere('Watchlist.status = "done"')
                ->columns(['Watchlist.*','Episodes.*'])
                ->execute();
        }
        return $this->watchedRecords;
    }


    /**便于将来自动删除相关的watchlist记录
     * @return $this
     */
    public function deleteAllWatchListHook()
    {
        $this->getAllWatchList()->delete();
        return $this;
    }




}