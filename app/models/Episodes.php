<?php

use Carbon\Carbon;

class Episodes extends myModel
{
    use linkableTrait;
    use commentableTrait;

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $num;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $abstract;

    /**
     *
     * @var integer
     */
    public $serial_id;

    /**
     *
     * @var string
     */
    public $date;
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

    public static function findBySerialAndNumOrNew($serial, $num)
    {
        $result = self::query()
            ->where('serial_id =:serial:',['serial'=>$serial])
            ->andWhere('num = :num:',['num'=>$num])
            ->execute()->getFirst();
        if(!$result) return new self;
        return $result;
    }

    public static function getLatest($days = 1,Users $user = null)
    {
        $now = (new Carbon())->now()->toDateTimeString();
        $then = (new Carbon())->now()->addDay(-$days)->toDateTimeString();
        $query = self::query()
            ->leftJoin('Serials','Serials.id = Episodes.serial_id')
            ->leftJoin('Movies','Movies.id = Serials.movie_id')
            ->leftJoin('Links','Links.linkable_type = "Episodes" AND Links.linkable_id = Episodes.id')
            ->leftJoin('Comments','Comments.commentable_type = "Episodes" AND Comments.commentable_id = Episodes.id')
            ->groupBy('Episodes.id')
            ->columns(['Episodes.*','Movies.*','Links.*','count(Comments.id) AS commentsCount'])
            ->where('date < :date:',['date'=>$now])
            ->andWhere('date > :then:',['then'=>$then])
            ->orderBy('date DESC');
//            ->limit(50)
        if(null != $user){
            $query = $query->join('Favorites','Favorites.favoritable_type ="Movies" AND Favorites.favoritable_id = Movies.id')
                ->andWhere('Favorites.user_id = :user_id:',['user_id'=>$user->id]);
        }
        return $query->execute();
    }

    /**取出某部电视剧的所有episodes
     * @param Serials $serial
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function getEpisodesFor(Serials $serial)
    {
        return self::query()
            ->where('serial_id = :id:',['id'=>$serial->id])
            ->orderBy('num')
            ->execute();
    }

    public static function getLastUpdatedEpisodeFor(Serials $serial)
    {
        $now = (new Carbon())->now()->toDateTimeString();
        return self::query()
            ->where('serial_id = :id:',['id'=>$serial->id])
            ->andWhere('date < :date:',['date'=>$now])
            ->orderBy('num DESC')
            ->execute()->getFirst();
    }

//    public static function getMyLatest($days = 1,Users $user = null)
//    {
//        $now = (new Carbon())->now()->toDateTimeString();
//        $then = (new Carbon())->now()->addDay(-$days)->toDateTimeString();
////        dd(\Phalcon\Di::getDefault()->get('auth')->id);
//        if($user == null){
//            $user = \Phalcon\Di::getDefault()->get('auth');
//        }
//
//        return self::query()
//            ->leftJoin('Serials','Serials.id = Episodes.serial_id')
//            ->leftJoin('Movies','Movies.id = Serials.movie_id')
//            ->join('Favorites','Favorites.favoritable_type ="Movies" AND Favorites.favoritable_id = Movies.id')
//            ->columns(['Episodes.*','Movies.*'])
//            ->where('date < :date:',['date'=>$now])
//            ->andWhere('date > :then:',['then'=>$then])
//            ->andWhere('Favorites.user_id = :user_id:',['user_id'=>$user->id])
//            ->orderBy('date DESC')
////            ->limit(50)
//            ->execute();
//    }


    /**
     *@todo 奇怪为什么需要这个函数呢？这个需要重新检查一遍？？
     */
    public function afterFetchTransformDateFormat()
    {
        $this->date=Carbon::parse($this->date);
    }


    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'episodes';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Episodes[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Episodes
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
            'num' => 'num',
            'title' => 'title',
            'abstract' => 'abstract',
            'serial_id' => 'serial_id',
            'date' => 'date',
            'updated_at' => 'updated_at',
            'created_at' => 'created_at'
        );
    }

    private $serial = null;
    /**
     * @return Serials
     */
    public function getSerial()
    {
        if(null == $this->serial){
            $this->serial =  Serials::findFirst('id = "'.$this->serial_id.'"');
        }
        return $this->serial;
    }

    private $movie = null;

    /**
     * @return Movies
     */
    public function getMovie()
    {
        if(null == $this->movie){
            $this->movie= Movies::query()
                ->leftJoin('Serials','Serials.movie_id = Movies.id')
                ->where('Serials.id = :serial_id:',['serial_id'=>$this->serial_id])
                ->execute()->getFirst();
        }
        return $this->movie;
    }



    public function getDoubanLink()
    {
        return 'http://movie.douban.com/subject/'.$this->getSerial()->getMovie()->doubanid.'/episode/'.$this->num.'/';
    }

    public function getNext()
    {
        $rowset = self::query()
            ->where('num > :num:',['num'=>$this->num])
            ->andWhere('serial_id = :serial:',['serial'=>$this->serial_id])
            ->orderBy('num ASC')
            ->limit(1)
            ->execute();
        if($rowset->count()){
            return $rowset->getFirst();
        }else{
            return self::query()
                ->where('serial_id = :serial:',['serial'=>$this->serial_id])
                ->orderBy('num ASC')
                ->limit(1)
                ->execute()->getFirst();
        }
    }

    public function getPrevious()
    {
        $rowset = self::query()
            ->where('num < :num:',['num'=>$this->num])
            ->andWhere('serial_id = :serial:',['serial'=>$this->serial_id])
            ->orderBy('num DESC')
            ->limit(1)
            ->execute();
        if($rowset->count()){
            return $rowset->getFirst();
        }else{
            return self::query()
                ->where('serial_id = :serial:',['serial'=>$this->serial_id])
                ->orderBy('num DESC')
                ->limit(1)
                ->execute()->getFirst();
        }
    }

    public function isPublished()
    {
        return $this->date < Carbon::now();
    }



}
