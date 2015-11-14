<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Users extends myModel
{

    use FavoritableTraitForUser;
    use authTrait;
    use WatchlistTraitForUser;
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
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $remember_token;

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
     * 缓冲变量，避免重复查询
     */

    /**
     * @param \Phalcon\Http\Cookie $auth
     * @return Users
     */
    public static function findByCookieAuth(\Phalcon\Http\Cookie $auth)
    {
        $data = explode('::',$auth->getValue());
        return self::findFirst($data[1]);
    }

    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Articles', 'user_id', array('alias' => 'Articles'));
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'name' => 'name', 
            'email' => 'email', 
            'password' => 'password', 
            'remember_token' => 'remember_token', 
            'created_at' => 'created_at', 
            'updated_at' => 'updated_at'
        );
    }

    public function comments()
    {
        if (null == $this->comments) {
            $this->comments = Comments::query()
                ->where('user_id = :user:',['user'=>$this->id])
                ->orderBy('created_at DESC')
                ->execute();
        }
        return $this->comments;
    }

    private $links = null;
    public function links()
        //这里的效率比下面的tags()要高不少，看起来还是值得将来参考的！
    {
        if (null == $this->links) {
            $this->links = Links::query()
                ->where('user_id = :user:',['user'=>$this->id])
                ->leftJoin('Movies','Movies.id = Links.movie_id')
                ->leftJoin('Sites','Sites.id = Links.site_id')
                ->columns([
                    'Links.id',
                    'Links.url',
                    'Links.created_at',
                    'Links.user_id',
                    'Sites.name AS siteName',
                    'Sites.id AS siteId',
                    'Movies.title AS movieTitle',
                    'Movies.id AS movieId'
                ])
                ->orderBy('Links.created_at DESC')
                ->execute();
        }
        return $this->links;
    }

    private $tags = null;
    public function tags()
    {
        if(null == $this->tags){
            $this->tags = Tags::query()
                ->join('Taggables','Taggables.tag_id = Tags.id')
                ->where('Taggables.user_id = :user:',['user'=>$this->id])
                ->groupBy('Tags.id')
                ->execute();
        }
        return $this->tags;
    }

    private $taglogs = null;
    public function taglogs()
    {
        if (null == $this->taglogs) {
            $this->taglogs = Taggables::query()
                ->leftJoin('Tags','Tags.id = Taggables.tag_id')
                ->where('Taggables.user_id = :user:',['user'=>$this->id])
                ->columns([
                    'Taggables.*',
                    'Taggables.id',
                    'Taggables.user_id',
                    'Taggables.tag_id',
                    'Taggables.created_at',
                    'Taggables.taggable_type',
                    'Taggables.taggable_id',
                    'Tags.name AS tag_name'
                ])
                ->orderBy('Taggables.created_at DESC')
                ->execute();
        }
        return $this->taglogs;
    }

    public function getStatistics()
    {
        return [
            'comments'  => Comments::count('user_id = "'.$this->id.'"'),
            'tags'      => Taggables::count('user_id = "'.$this->id.'"'),
            'links'     => Links::count('user_id = "'.$this->id.'"'),
            'favorites' => Favorites::count('user_id = "'.$this->id.'"')
        ];
    }



}
