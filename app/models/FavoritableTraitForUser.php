<?php
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/11/8
 * Time: 17:10
 */
trait FavoritableTraitForUser
{
    /*
    * 与收藏有关的几个函数
    */
//    public function isFavorite(myModel $object)
//    {
//
//        $favorites = $this->getFavorites($object);
//        return $favorites->count() > 0;
//    }

    public function hasFavoredThis(myModel $object)
    {
        return Favorites::query()
            ->where('favoritable_type = :type:',['type'=>get_class($object)])
            ->andWhere('favoritable_id = :id:',['id'=>$object->id])
            ->andWhere('user_id = :user:',['user'=>$this->id])
            ->execute()->count() > 0;
    }



    /**
     * @param myModel|null $object
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getFavorites(myModel $object = null)
    {
        $query = Favorites::query()
            ->where('user_id = :user:',['user'=>$this->id]);
        if($object) {
            $query = $query
                ->andWhere('favoritable_id = :id:',['id'=>$object->id])
                ->andWhere('favoritable_type = :type:',['type'=>get_class($object)]);
        }
        return $query->execute();
    }


    private $favorites = null;
    public function favorites()
    {
        if (null == $this->favorites) {
            $this->favorites = Favorites::query()
                ->where('user_id = :user:',['user'=>$this->id])
                ->leftJoin('Movies','Movies.id = Favorites.favoritable_id AND favoritable_type = "Movies"')
                ->orderBy('created_at DESC')
                ->columns(['Favorites.id','Favorites.user_id','Favorites.favoritable_id AS movie_id','Favorites.created_at AS created_at','Movies.title AS movie_title'])
                ->execute();
        }
        return $this->favorites;
    }


    private $myLatestEpisodes = [];
    public function getMyLastedEpisodes($days = 1)
    {
        if([]==$this->myLatestEpisodes){
            $this->myLatestEpisodes = Episodes::getLatest($days,$this);
        }
        return $this->myLatestEpisodes;

    }


}