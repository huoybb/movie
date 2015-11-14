<?php

class Sites extends myModel
{
    use commentableTrait;
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $format;

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


    private $linksInfo = null;
    static private $sitesInfo = null;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'name' => 'name', 
            'format' => 'format', 
            'created_at' => 'created_at', 
            'updated_at' => 'updated_at'
        );
    }

    /**
     * 根据url来获得对应的site，将如果新增的站点的话，则直接转接到此处进行修正名称为好！
     * @param $url
     * @return Sites
     */
    static public function findByUrl($url)
    {
        $format = self::getFormatFromURL($url);
//        dd($format);
        if($format){
            $site = Sites::findFirst(['conditions'=>'format = :format:','bind'=>['format'=>$format]]);
            if($site){
                return $site;
            }else{
                return self::addSite($url);
            }
        }
        return null;
    }

    /**获取url的前缀，也就是format以便与site进行匹配
     * @param $url
     * @return null
     */
    static private function getFormatFromURL($url)
    {
        $urlFormats = [
            '|^http://([^/]+)/.*$|',
            '|^https://([^/]+)/.*$|',
            '|^(magnet):\?xt.*|',
            '|^(ed2k)://\|file\|*|'
        ];
        foreach($urlFormats as $format){
            if(preg_match($format,$url,$match)){
                $site = $match[1];
                return $site;
            }
        }
        return null;
    }

    static private function addSite($url)
    {
        $data =[];
        $data['format'] = self::getFormatFromURL($url);
        $data['name'] = '链接';
        $site = new Sites();
        $site->save($data);
        return $site;
    }

    public function links()
    {
        return Links::find(['conditions'=>'site_id = :site_id:','bind'=>['site_id'=>$this->id]]);
    }

    /**获得site下所有链接，并附上电影抬头信息，避免重复查询导致效率问题
     * 这里有问题，目前的链接是可以链接到movies、tvSerials、Episodes这些需要分开，否则会出现错误信息
     */
    public function getLinksInfo()
    {
        if(null == $this->linksInfo) $this->linksInfo = Links::query()
            ->leftJoin('Movies','Links.linkable_id = Movies.id AND Links.linkable_type = "Movies"')
            ->where('site_id = :site_id:',['site_id'=>$this->id])
            ->columns(['Links.id','Links.url','Links.note','Links.linkable_id AS movie_id','Links.created_at','Movies.title'])
            ->orderBy('Links.updated_at DESC')
            ->execute();
        return $this->linksInfo;
    }

    static public function getSitesInfo()
    {
        if(null == self::$sitesInfo) self::$sitesInfo = self::query()
            ->join('Links','Links.site_id = Sites.id')
            ->orderBy('linkCounts DESC')
            ->groupBy('Sites.id')
            ->columns(['Sites.id','Sites.name','Sites.created_at','Sites.format','linkCounts'=>'COUNT(*)'])
            ->execute();
        return self::$sitesInfo;
    }

    /**
     * @return mixed
     */
    public function getParser()
    {
        $parser = preg_replace('/\./m', '_',$this->format).'_Parser';//后续再思考如何自动的变换这个名字
        if(class_exists($parser)){
            return new $parser;
        }
        dd($parser.'对象不存在');
    }






}
