<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/10/17
 * Time: 21:04
 */
trait FavoritableTrait
{
    /**
     *将当前对象收藏起来，归属于当前登录用户
     */
    private $favorites = null;
    private $usersFavoriteThis = null;


    public function getFavorites()
    {
        if($this->favorites == null) $this->favorites = Favorites::query()
            ->where('favoritable_id = :id:',['id'=>$this->id])
            ->andWhere('favoritable_type = :type:',['type'=>get_class($this)])
            ->execute();
        return $this->favorites;
    }
    /**
     *查询哪些人收藏了当前对象
     */
    public function getUsersFavoriteThis()
    {
        if($this->usersFavoriteThis == null) $this->usersFavoriteThis = Users::query()
            ->rightJoin('Favorites','Users.id = Favorites.user_id')
            ->where('Favorites.favoritable_id = :id:',['id'=>$this->id])
            ->andWhere('Favorites.favoritable_type = :type:',['type'=>get_class($this)])
            ->execute();
        return $this->usersFavoriteThis;
    }


    /**
     *删除所有当前对象的收藏记录
     */
    public function deleteFavorites()
    {
        foreach($this->getFavorites() as $favorite ){
            $favorite->delete();
        }
        return $this;
    }

    public function deleteFavoritesHook()
    {
        $this->deleteFavorites();
        return $this;
    }

    public function addFavoriteBy(Users $byUser)
    {

        if(!$byUser->hasFavoredThis($this)){
            $data = [
                'user_id'=>$byUser->id,
                'favoritable_id'=>$this->id,
                'favoritable_type'=>get_class($this)
            ];
            return (new Favorites())->save($data);
        }

        return $this;

    }
    public function removeFavoriteBy(Users $user)
    {
        if($user->hasFavoredThis($this)){
            Favorites::query()
                ->where('user_id = :user:',['user'=>$user->id])
                ->andWhere('favoritable_type = :type:',['type'=>get_class($this)])
                ->andWhere('favoritable_id = :id:',['id'=>$this->id])
                ->execute()->delete();
        }
        return $this;
    }


}