<?php

use Carbon\Carbon;

class Movies extends myModel implements KeywordableInterface
{

    use KeywordableTrait;
    use commentableTrait;
    use taggableTrait;
    use linkableTrait;
    use FavoritableTrait;
    use SerialableTrait;
    use listableTrait;//增加影单的功能
    use WatchlistTrait;//增加观看记录的功能
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
    public $poster;

    /**
     *
     * @var string
     */
    public $director;

    /**
     *
     * @var string
     */
    public $screenwriter;

    /**
     *
     * @var string
     */
    public $casts;

    /**
     *
     * @var string
     */
    public $official_website;

    /**
     *
     * @var string
     */
    public $country;

    /**
     *
     * @var string
     */
    public $language;

    /**
     *
     * @var string
     */
    public $release_time;

    /**
     *
     * @var string
     */
    public $other_names;

    /**
     *
     * @var string
     */
    public $IMDb_link;

    /**
     *
     * @var string
     */
    public $doubanid;

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

    /*
     * 定义几个缓存的变量
     */
//    private $links = null;
//    private $tags = null;
//    private $tagsWithCounts = null;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'title' => 'title', 
            'poster' => 'poster', 
            'director' => 'director', 
            'screenwriter' => 'screenwriter', 
            'casts' => 'casts',
            'official_website' => 'official_website', 
            'country' => 'country', 
            'language' => 'language', 
            'release_time' => 'release_time', 
            'other_names' => 'other_names', 
            'IMDb_link' => 'IMDb_link', 
            'doubanid' => 'doubanid', 
            'created_at' => 'created_at', 
            'updated_at' => 'updated_at'
        );
    }


    public function getHtml($value)
    {
        if($value == 'doubanid') return '<a href="http://movie.douban.com/subject/'.$this->$value.'/" target="_blank">'.$this->$value.'</a>';
        if($value == 'IMDb_link' and $this->$value <> '') return '<a href="http://www.imdb.com/title/'.$this->$value.'/" target="_blank">'.$this->$value.'</a>';
        if($value == 'created_at') return Carbon::parse($this->$value)->diffForHumans();
        if(($value == 'director' or $value == 'screenwriter' or $value == 'casts' or $value == 'other_names') and $this->$value)  {
            $names = explode('/',$this->$value);
            $htmlBit = [];
            foreach($names as $name){
                $name = trim($name);
                $baseurl = $this->getDI()->get('url')->getBaseUri();
                $htmlBit[]= '<a href="'.$baseurl.'movies/search/'.$name.'/" >'.$name.'</a>';
            }
            return implode(' / ',$htmlBit);
        }
        return $this->$value;
    }


    public function search($search)
    {
        return $this->query()
            ->where('title like :search:',['search'=>'%'.$search.'%'])
            ->orWhere('director like :search:',['search'=>'%'.$search.'%'])
            ->orWhere('screenwriter like :search:',['search'=>'%'.$search.'%'])
            ->orWhere('casts like :search:',['search'=>'%'.$search.'%'])
            ->orWhere('other_names like :search:',['search'=>'%'.$search.'%'])
            ->orderBy('created_at DESC')
            ->execute();
    }

    public function getNext()
    {
        $rowset = Movies::query()
            ->where('id > :id:',['id'=>$this->id])
            ->orderBy('id ASC')
            ->limit(1)
            ->execute();
        if($rowset->count()){
            return $rowset[0];
        }else{
            return Movies::findFirst();
        }
    }

    public function getPrevious()
    {
        $rowset = Movies::query()
            ->where('id < :id:',['id'=>$this->id])
            ->orderBy('id DESC')
            ->limit(1)
            ->execute();
        if($rowset->count()){
            return $rowset[0];
        }else{
            return Movies::findFirst(['order'=>'id DESC']);
        }
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getUrlLink()
    {
        return '<a href="'.$this->getDI()->getShared('url')->get(['for'=>'movies.show','movie'=>$this->id]).'">'.$this->__toString().'</a>';
    }

    public function isUpdateable()
    {
        return $this->isAiring() AND $this->isSerialable() AND $this->isLastSeason();
    }





}
