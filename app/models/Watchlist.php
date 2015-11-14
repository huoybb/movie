<?php

class Watchlist extends myModel
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $movie_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $currentEpisode_id;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     * @param Movies $movie
     * @param Users $user
     * @return \Phalcon\Mvc\ModelInterface|Watchlist
     */
    public static function findOrCreateNew(Movies $movie, Users $user)
    {
        $watchlist = self::query()
            ->where('movie_id = :movie:',['movie'=>$movie->id])
            ->andWhere('user_id = :user:',['user'=>$user->id])
            ->orderBy('id DESC')
            ->execute()
            ->getFirst();
//        dd($watchlist);
        if(!$watchlist) $watchlist = new self;

        return $watchlist;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("'watchList'");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'watchList';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Watchlist[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Watchlist
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'movie_id' => 'movie_id',
            'user_id' => 'user_id',
            'status' => 'status',
            'currentEpisode_id' => 'currentEpisode_id',
            'updated_at' => 'updated_at',
            'created_at' => 'created_at'
        );
    }

}
