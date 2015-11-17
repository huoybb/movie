<?php

use Carbon\Carbon;

class Serials extends myModel
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
    public $tvSerial_id;

    /**
     *
     * @var integer
     */
    public $movie_id;

    /**
     *
     * @var integer
     */
    public $serial_num;

    /**
     *
     * @var string
     */
    public $year;

    /**
     *
     * @var string
     */
    public $keywords;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource("'Serials'");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'Serials';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Serials[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Serials
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
            'tvSerial_id' => 'tvSerial_id',
            'movie_id' => 'movie_id',
            'serial_num' => 'serial_num',
            'year' => 'year',
            'keywords' => 'keywords',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }

    private $Episodes = null;
    public function getEpisodes()
    {
        if(null == $this->Episodes) {
            $this->Episodes = Episodes::getEpisodesFor($this);
        }
        return $this->Episodes;
    }
    private $lastEpisode = null;
    public function getLastUpdatedEpisode()
    {
        if(null == $this->lastEpisode) {
            $this->lastEpisode =  Episodes::getLastUpdatedEpisodeFor($this);
        }
        return $this->lastEpisode;
    }

    public function getEpisodeNextOnTV()
    {
        /** @var Episodes $episode */
        foreach($this->getEpisodes() as $episode){
            if($episode->date > \Carbon\Carbon::now()) return $episode;
        }
        return null;
    }


    private $movie = null;
    /**
     * @return Movies
     */
    public function getMovie()
    {
        if(null == $this->movie){
            $this->movie = Movies::query()
                ->where('id = :id:',['id'=>$this->movie_id])
                ->execute()->getFirst();
        }
        return $this->movie;
    }




    private $TVserial = null;

    /**
     * @return Tvserials
     */
    public function getTVSerial()
    {
        if(null == $this->TVserial){
            $this->TVserial = Tvserials::query()
                ->where('id =:id:',['id'=>$this->tvSerial_id])
                ->execute()->getFirst();
        }
        return $this->TVserial;
    }



}
