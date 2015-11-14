<?php

use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
class Keywords extends myModel
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $keywordable_type;

    /**
     *
     * @var integer
     */
    public $keywordable_id;

    /**
     *
     * @var string
     */
    public $keywords;
    public $created_at;
    public $updated_at;

//事件处理的函数
    public function beforeSaveEncodeKeywords(){
        $this->keywords = $this->keywords_encode($this->keywords);
    }
    public function afterFetchDecodeKeywords(){
        $this->keywords = $this->keywords_decode($this->keywords);
    }



    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'keywords';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Keywords[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Keywords
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
            'keywordable_type' => 'keywordable_type',
            'keywordable_id' => 'keywordable_id',
            'keywords' => 'keywords',
            'updated_at'=>'updated_at',
            'created_at'=>'created_at'
        );
    }

    public function createFulltextIndex($object)
    {
        $this->keywordable_type = get_class($object);
        $this->keywordable_id = $object->id;
        $text2index = $this->getText2index($object);
        $this->keywords = self::wordsplit($text2index);
        return $this->save();
    }

    public function updateKeyWords($keywords)
    {
        $this->keywords = self::wordsplit($keywords);
        return $this->save();
    }

    public function __toString()
    {
        return $this->keywords;
    }



    public function relatedMovies($Excluded = [])
    {
        return $this->searchByType($this->keywords,$Excluded,'Movies');

    }

    public function relatedComments($Excluded = [])
    {
        return $this->searchByType($this->keywords,$Excluded,'Comments');
    }


    public function searchMovies($words)
    {
        return $this->searchByType($words);
    }





    /**根据关键词，返回对应的影片数组
     * @param string $text
     * @param array $Excluded
     * @param string $type
     * @return array
     */
    private function searchByType($text,$Excluded = [],$type = 'Movies'){
        $query =  Keywords::query()
            ->where('MATCH(keywords) AGAINST("'.$this->keywords_encode($text).'")')
            ->andWhere('Keywords.keywordable_type = "'.$type.'"')
            ->leftJoin($type,$type.'.id = Keywords.keywordable_id AND Keywords.keywordable_type = "'.$type.'"')
            ->columns([$type.'.*','Keywords.*'])
            ->limit(10);
        if($Excluded <> []) $query = $query->notInWhere($type.'.id',$Excluded);

        $rows = $query->execute();
        $result = [];
        foreach($rows as $row){
            $result[] = $row->{strtolower($type)};
        }

        return $result;
    }








    public static function wordsplit($s)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'http://www.xunsearch.com/scws/api.php', [
            'form_params' => [
                'data'=>$s,
                'charset'=>'utf8',
                'multi'=>3,//复合分词等级，默认为0，可以设置0-15；可以设置为3，会把排在前三名的都罗列出来
                'ignore'=>'yes'//是否忽略标点符号
            ]
        ]);

        $results = unserialize($response->getBody()->__toString());
        $words = [];
        foreach($results['words'] as $row){
            //    echo iconv('utf-8','gbk',$row['word']).'-'.$row['idf'].'-'.$row['attr'].'-'.$row['off'].PHP_EOL;
            $word = $row['word'];
            if (preg_match('%\w%', $word)) //如果为英文单词，则小写
            {
                $word = strtolower($word);
            } else {     //如果为中文词语，则正常
                if (strlen($word) <= 3) $word = '';
            }

            if(''<>$word) $words[]=$word;
        }
        return implode(' ', $words);

    }

    public static function encodeKeywords($keywords){
        $keywords=explode(' ',trim($keywords));
        $words='';
        foreach($keywords as $item){
            $words.=base64_encode($item).' ';
        }
        return trim($words);
    }
    private function keywords_encode($keywords){
        $keywords=explode(' ',trim($keywords));
        $words='';
        foreach($keywords as $item){
            $words.=base64_encode($item).' ';
        }
        return trim($words);
    }
    private function keywords_decode($keywords){//针对'图书 电影'，等中间带空格的字符串，变成六十四位的编码，这里英文有一个问题，需要修改
        $keywords=explode(' ',$keywords);
        $words='';
        foreach($keywords as $item){
            $words.=base64_decode($item).' ';
        }
        return trim($words);
    }

    /*
     * 需要进一步的将这个函数变成一个通用的，比如可以将对象的__toString()返回的字符串进行分词，这样也就能够比较简单的定义了！
     */
    private function getText2index($object)
    {
        $fullTextConfig = [
            'Movies'=>['title','director','screenwriter','casts','other_names','IMDb_link','doubanid'],
            'Comments'=>['content']
        ];
        $type = get_class($object);

        $text ='';
        if(!isset($fullTextConfig[$type])) die('抛出异常！需要在Keywords.php中定义相关的索引项目,对象:Keywords,方法： getText2index');
        foreach($fullTextConfig[$type] as $field){
            $text .=' '.$object->$field;
        }
        return $text;
    }

}
