<?php

class Links extends myModel
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
    public $url;

    /**
     *
     * @var string
     */
    public $note;

    /**
     *
     * @var integer
     */
    public $site_id;

    /**
     *
     * @var string
     */
    public $linkable_type;
    /**
     *
     * @var integer
     */
    public $linkable_id;

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
     * Independent Column Mapping.
     */

    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'url' => 'url', 
            'note' => 'note', 
            'site_id' => 'site_id', 
            'linkable_type' => 'linkable_type',
            'linkable_id' => 'linkable_id',
            'user_id' => 'user_id',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }


    private $site =  null;
    public function site()
    {
        if($this->site == null) $this->site = Sites::findFirst($this->site_id);
        return $this->site;
    }
    private $linkable = null;
    public function getLinkable()
    {
        if(null == $this->movie) {
            $type = $this->linkable_type;
            $this->movie = $type::findFirst($this->linkable_id);
        }
        return $this->movie;
    }
    private $user = null;
    public function user()
    {
        if(null == $this->user) $this->user = Users::findFirst($this->user_id);
        return $this->user;
    }




}
