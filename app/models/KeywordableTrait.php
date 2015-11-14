<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/10/17
 * Time: 12:57
 */
trait KeywordableTrait
{
    private $keywords = null;

    /**
     * @return Keywords
     */
    public function keywords()
    {
        if(null == $this->keywords) $this->keywords = Keywords::query()
            ->where('keywordable_type = :type:',['type'=>get_class($this)])
            ->andWhere('keywordable_id = :id:',['id'=>$this->id])
            ->execute()->getFirst();
        return $this->keywords;
    }
    
    public function updateKeyWords($keywords)
    {
        $this->keywords()->updateKeyWords($keywords);
    }
    public function getRelatedMovies()// @todo 耦合问题
    {
        $ExcludedMovies = [];
        $rows = null;
        if(method_exists($this,'getSerialMovieList')) $rows = $this->getSerialMovieList();//此处与SerialableTrait耦合，将来可能会有问题，接口的问题应该考虑了
        if($rows){
            foreach($rows as $row){
                $ExcludedMovies[]=$row->movie_id;
            }
        }

        return $this->keywords()->relatedMovies($ExcludedMovies);
    }
    public function getRelatedComments()
    {
        return $this->keywords()->relatedComments();
    }

    public function createKeyWords()
    {
        $keywords = (null <> $this->keywords()) ? $this->keywords() :  new Keywords();//判断是否已经存在索引对象，如果存在则是更新，否则新建
        return $keywords->createFulltextIndex($this);
    }
    public function afterSaveCreateOrUpdateKeyWords()
    {
        $this->createKeyWords();
    }


    public function deleteKeywords()
    {
        if(is_a($this->keywords(),'Keywords')) $this->keywords()->delete();
        return $this;
    }
    public function deleteKeywordsHook()
    {
        $this->deleteKeywords();
        return $this;
    }
    

    //-------为了减少查询的次数优化------
    public static function findWithKeywords($id)
    {
        $type = __CLASS__;//获取当前类名称
        $data = self::query()
            ->where($type.'.id = :id:',['id'=>$id])
            ->leftJoin('Keywords','keywordable_id = '.$type.'.id AND keywordable_type = "'.$type.'"')
            ->columns([$type.'.*','Keywords.*'])
            ->execute()
            ->getFirst();
        $result = $data->{strtolower($type)};
        $result->keywords = $data->keywords;
        return $result;
    }

}