<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/10/17
 * Time: 16:49
 */
trait linkableTrait
{
    private $links = null;
    public function links()
    {
        if(null == $this->links) $this->links = Links::query()
            ->where('linkable_type = :type:',['type'=>get_class($this)])
            ->andWhere('linkable_id = :id:',['id'=>$this->id])
            ->execute();
        return $this->links;
    }

    public function deleteLinks()
    {
        $this->links()->delete();
        return $this;
    }
    public function deleteLinksHook()
    {
        $this->deleteLinks();
        return $this;
    }

    public function addLinkBy(Users $byUser,$url)
    {
        $data = array(
            'url'=>$url,
            'linkable_id'=>$this->id,
            'linkable_type'=>get_class($this),
            'site_id'=>Sites::findByUrl($url)->id,
            'user_id'=>$byUser->id
        );
        $link = new Links();
        $link->save($data);
        return $this;
    }

    private $FirstTTMJLink = null;

    /**
     * @return Links
     */
    public function getFirstTTMJLink()
    {
        if(null == $this->FirstTTMJLink){
            $links = $this->links();
            foreach($links as $link){
                if(preg_match('%www.ttmeiju.com/meiju%m', $link->url)){
                    $this->FirstTTMJLink = $link;
                    break;
                }
            }
        }

        return $this->FirstTTMJLink;
    }
    private $FirstKATLink = null;

    /**
     * @return Links
     */
    public function getFirstKATLink()
    {
        if(null == $this->FirstKATLink){
            $links = $this->links();
            foreach($links as $link){
                if(preg_match('%kat.cr%m', $link->url)){
                    $this->FirstKATLink = $link;
                    break;
                }
            }
        }

        return $this->FirstKATLink;
    }




}