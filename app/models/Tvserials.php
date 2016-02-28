<?php

use Carbon\Carbon;

class Tvserials extends myModel
{
    use linkableTrait;

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $summary;
    /**
     *
     * @var string
     */
    public $start;
    /**
     *
     * @var string
     */
    public $end;

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
        $this->setSource("'tvSerials'");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tvSerials';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tvserials[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Tvserials
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
            'title' => 'title',
            'summary' => 'summary',
            'start' => 'start',
            'end' => 'end',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }

    public static function fetchAllWithSerialCounts(){
        return self::query()
            ->innerJoin('Serials','Serials.tvSerial_id = Tvserials.id')
            ->groupBy('Tvserials.id')
            ->orderBy('Tvserials.updated_at DESC')
            ->columns(['Tvserials.*','count(Serials.id) AS counts'])
            ->execute();
    }
    private $totalSerialNum = null;
    public function getTotalSerialNum()
    {
        if($this->totalSerialNum == null){
            $this->totalSerialNum = Serials::count("tvSerial_id = '".$this->id."'");
        }
        return $this->totalSerialNum;
    }

    private  $serialList = null;
    public function getSerialList()
    {
        if($this->serialList == null){
            $this->serialList = Serials::query()
                ->where('tvSerial_id = :id:',['id'=>$this->id])
                ->orderBy('serial_num')
                ->execute();
        }
        return $this->serialList;
    }

    private $serialListMovies = null;
    public function getSerialListMovies()
    {
        if($this->serialListMovies == null){
            $movies = Movies::query()
                ->leftJoin('Serials','Serials.movie_id = Movies.id')
                ->Where('Serials.tvSerial_id = :tvSerial_id:',['tvSerial_id'=>$this->id])
                ->orderBy('Serials.year,Serials.serial_num')
                ->columns(['Movies.*','Serials.*'])
                ->execute();
            $result = [];
            foreach($movies as $m){
                $movie = $m->movies;
                $movie->serial_num = $m->serials->serial_num;
                $movie->serial_id =  $m->serials->id;
                $result[]=$movie;
            }
            $this->serialListMovies = $result;
        }
        return $this->serialListMovies;
    }

//    public function getEpisodesInfos($url)
//    {
//        if(count($this->getSerialList()->getFirst()->getEpisodes())) return null;
//        $kat = new KickAssTorrent();
//        $seasons = $kat->parseTVSerialsinfo($url);
//        foreach($seasons as $season=>$episodes){
//            $season_number = (int) preg_replace('|^Season\s+([0-9]+)$|','$1',$season);
//            $serial = $this->getSerialBySeasonNum($season_number);
//            if(null == $serial) continue;//如果没有序列的话，则直接退出
//            //@todo 是否会出现重复导入的问题呢？该如何处理呢？
//            foreach($episodes as $episode){
//                $e = new Episodes();
//                $this->saveEpisode($episode,$e,$serial);
//            }
//        }
//    }



    public function updateEpisodesInfo($url=null,$for_season_num = null)
    {
//        dd($for_season_num);
        if($url == null){
            $url = $this->getFirstKATLink()->url;
            $link = $this->getDI()->get('url')->get(['for'=>'tvserials.show','tvserial'=>$this->id]);
            if(!$url) {
                echo '无法获取KAT的url地址，请先设置本连续剧的kat地址：<a href="'.$link.'">链接</a>';
                die();
            }
        }
//        dd($url);
        $parser = Sites::findByUrl($url)->getParser();
        $data = $parser->parseTVSerialsinfo($url);

        $this->updateTvSerial($data['info']);

        $seasons = $data['seasons'];

        foreach($seasons as $season=>$episodes){
//            $season_number = (int) preg_replace('|^Season\s+([0-9]+)$|','$1',$season);
            $season_number = $season;

            if(null <> $for_season_num AND $for_season_num <> $season_number)  continue;

            $serial = $this->getSerialBySeasonNum($season_number);
//            dd($serial);
            if(null == $serial) continue;//如果没有序列的话，则直接退出
            foreach($episodes as $episode){
                $num = $episode['num'];
                if($e = Episodes::findBySerialAndNumOrNew($serial->id,$num)){
                    $this->saveEpisode($episode,$e,$serial);
                };

            }
        }
    }

    public function isOnTheAir()
    {
        return $this->end == 'Present' OR $this->end == null;
    }

    /**
     * @param $season_number
     * @return Serials
     */
    private function getSerialBySeasonNum($season_number)
    {
        $list = $this->getSerialList();
        foreach($list as $serial){
            if($serial->serial_num == $season_number) return $serial;
        }
        return null;
    }
    private function saveEpisode($source,Episodes $to,$serial)
    {
        $to->num = $source['num'];
        $to->date = (new Carbon())->createFromTimestamp(strtotime($source['date']))->toDateTimeString();
        $to->title = $source['title'];
        $to->serial_id = $serial->id;
        $to->save();
    }

    private function updateTvSerial($info)
    {
        if([]<>$info){
            $this->start = $info['start'];
            $this->end  = $info['end'];
            $this->save();
        }
    }



}
