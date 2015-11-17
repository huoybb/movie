<?php
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/10/19
 * Time: 11:34
 */
trait SerialableTrait
{

    private $serial = null;
    public function isSerialable()
    {
        $serial = $this->getSerial();
        return !($serial == null);
    }

    public function isLastSeason()
    {
        $serial_num = $this->getSerial()->serial_num;
        $totalSerials = $this->getTVSerial()->getTotalSerialNum();
        return $serial_num == $totalSerials;
    }

//    public function isThisYear()
//    {
//        $year = $this->getSerial()->year;
//        return Carbon::now()->format('Y') == $year;
//    }

    /**
     *是否在播放，还是已经停播
     */
    public function isAiring()
    {
        return $this->getTVSerial()->isOnTheAir();
    }




    public function getSerial()//@todo需要修补一下的，这个逻辑有点乱
    {
        if(null == $this->serial){
            $this->serial = Serials::findFirst(['movie_id = "'.$this->id.'"']);
        }
        return $this->serial;
    }
    public function hasEpisodes()
    {
        return count($this->getEpisodes());
    }

    public function getEpisodes()
    {
        if(!$this->getTVSerial()) return [];
        return $this->getSerial()->getEpisodes();
    }

    public function getLastUpdatedEpisode()
    {
        return $this->getSerial()->getLastUpdatedEpisode();
    }

    
    private $episodeNextOnTV = null;
    public function getEpisodeNextOnTV()
    {
        if($this->episodeNextOnTV == null){
            $this->episodeNextOnTV = $this->getSerial()->getEpisodeNextOnTV();
        }
        return $this->episodeNextOnTV;
    }
    

    private $EpisodesComments = [];//针对数据库查询的需要进行缓存，以便减少数据库查询次数
    public function getEpisodesComments()
    {
        if([] == $this->EpisodesComments){
            $episodes = $this->getEpisodes();
            $ids = [];
            foreach($episodes as $episode){
                $ids[]= $episode->id;
            }
            if(count($ids)){
                $this->EpisodesComments = Comments::query()
                    ->leftJoin('Episodes','Episodes.id = Comments.commentable_id')
                    ->where('Comments.commentable_type = :type:',['type'=>'Episodes'])
                    ->inWhere('Comments.commentable_id',$ids)
                    ->columns(['Comments.*','Episodes.*'])
                    ->orderBy('Comments.updated_at DESC')
                    ->execute();

            }
        }

        return $this->EpisodesComments;

    }


    /**
     * @return Tvserials
     */
    public function getTVSerial()
    {
        if(!$this->getSerial()) return null;
        return $this->getSerial()->getTVSerial();
    }



    public function getSerialMovies()
    {
        return $this->getTVSerial()->getSerialListMovies();
    }

    public function getSerialMovieList()
    {
        if(!$this->getTVSerial()) return null;
        return $this->getTVSerial()->getSerialList();
    }

    public function getSerialBySeasonNum($season_number)
    {
        $ss = $this->getSerialMovieList();
        foreach($ss as $serial){
            if($serial->serial_num == $season_number) /** @var Serials $serial */
                return $serial;
        }
        return null;
    }

//    /**
//     * 1、根据一个url地址，应该是kat网站的，这个以后需要验证
//     * 2、将信息采集下来，并保存到数据库中
//     * @param $url
//     * @return null
//     */
//    public function updateEpisodesInfoFromKAT($url=null,$for_season_num = null){
//        if($url == null){
//            $url = $this->getTVSerial()->getFirstKATLink()->url;
//        }
//        $parser = Sites::findByUrl($url)->getParser();
//        $seasons = $parser->parseTVSerialsinfo($url);
//        foreach($seasons as $season=>$episodes){
//            $season_number = (int) preg_replace('|^Season\s+([0-9]+)$|','$1',$season);
//            if(null <> $for_season_num){
//                if($for_season_num <> $season_number) continue;
//            }
//
//            $serial = $this->getSerialBySeasonNum($season_number);
//            if(null == $serial) return null;//如果没有序列的话，则直接退出
//
//            foreach($episodes as $episode){
//                $e = new Episodes();
//                $e->num = (int)preg_replace('|Episode\s+([0-9]+)|','$1',$episode['num']);
//                $e->date = $episode['date'];
//                $e->title = $episode['title'];
//                $e->serial_id = $serial->id;
//                $e->save();
//            }
//        }
//    }

    /**
     * @param $season_number
     */
    public function getSerialMovieBySeasonNum($season_number)
    {
        $movies = $this->getSerialMovies();
        /** @var Movies $movie */
        foreach($movies as $movie){
            if($movie->getSerial()->serial_num == $season_number){
                return $movie;
            }
        }
        return null;
    }

    public function deleteSerial()
    {
        $serial = Serials::query()
            ->where('movie_id = :id:',['id'=>$this->id])
            ->execute()->getFirst();
        if($serial) $serial->delete();
        return $this;
    }

    public function deleteSerialHook()
    {
        $this->deleteSerial();
        return $this;
    }


    /**
     * @return null|Serials
     */
    public function findOrCreateSerial()
    {
        $serial = $this->getSerial();
        if(!$serial){
            $serial = new Serials();
            $serial->movie_id = $this->id;
            $serial->year = $this->getMovieYear();
//            $serial->keywords =Keywords::encodeKeywords(Keywords::wordsplit($this->title));//这个需要网络连接的！
        }
        return $serial;

    }

    public function changeToSerial()
    {
        $serial = $this->findOrCreateSerial();
        if($serial->id == null){
            $tvSerial = new Tvserials();
            $tvSerial->title = $this->getTVserialTitleFromMovie($this);//将当前电影的title赋值给这个系列
            $tvSerial->save();
            $serial->tvSerial_id = $tvSerial->id;
            $serial->serial_num = $this->getSeasonNumberFromMovie($this);
            $serial->save();
        }

        return $serial;
    }

    public function combineToSerialWith(Movies $anotherMovie)
    {

        $serial1 = $this->findOrCreateSerial();
        $serial2 = $anotherMovie->findOrCreateSerial();
        $serial1->serial_num = $this->getSeasonNumberFromMovie($this);
        $serial2->serial_num = $this->getSeasonNumberFromMovie($anotherMovie);



        if($serial1->id == null && $serial2->id == null){
            $tvSerial = new Tvserials();
            $tvSerial->title = $this->getTVserialTitleFromMovie($this);//将当前电影的title赋值给这个系列
            $tvSerial->save();

            $serial1->tvSerial_id = $tvSerial->id;
            $serial1->save();

            $serial2->tvSerial_id = $tvSerial->id;
            $serial2->save();
            return '双空';
        }
        if($serial1->tvSerial_id <> null && $serial2->tvSerial_id == null){
            $serial2->tvSerial_id = $serial1->tvSerial_id;
            $serial2->save();
            return '1有2空' ;
        }
        if($serial2->tvSerial_id <> null && $serial1->tvSerial_id == null){
            $serial1->tvSerial_id = $serial2->tvSerial_id;
            $serial1->save();
            return '1空2有';
        }
        if($serial2->tvSerial_id <> null && $serial1->tvSerial_id <> null){
            return '双有';
        }
        return '错误，没有对上';
    }



    public function getMovieYear(){
        if (preg_match('/\(([0-9]+)\)/m', $this->title, $regs)) {
            $result = $regs[1];
        } else {
            $result = "";
        }
        return $result;
    }

    public function getTVserialTitleFromMovie($movie){
        return preg_replace('/(.+)第.+季(.+)Season.+/m', '$1$2',$movie->title);
    }
    private function getSeasonNumberFromMovie($movie){
        return preg_replace('/.+第.+季.+Season\s*([0-9]+)\s*\([0-9]+\)/m', '$1', $movie->title);
    }

}