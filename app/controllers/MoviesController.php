<?php

use Carbon\Carbon;
use Phalcon\Forms\Form;
use Phalcon\Http\Request;

class MoviesController extends myController
{

    public function indexAction($page=1)
    {
        $this->view->page =$this->getPaginator(Movies::find(['order'=>'id DESC']),10,$page);
    }

    public function showAction(Movies $movie)
    {
//        $kat = new KickAssTorrent();
//        dd($kat->parseTVSerialsinfo('https://kat.cr/the-blacklist-tv16791/'));
//        dd($movie->getEpisodes()->toArray());

//        dd($movie->getTVSerial()->id);

//        $links = Links::find();
//        foreach($links as $link){
//            $link->linkable_type = get_class($movie);
//            $link->save();
//        }

//        dd(Carbon::createFromTimestamp(strtotime('Tuesday, Oct 27 2015'))->isTuesday());

        //如何将两个collection合并起来呢？
//        $episodes = $movie->getEpisodes();
//        $ids = [];
//        foreach($episodes as $epi){
//            $ids[]=$epi->id;
//        }
////        dd($ids);
//        $comments = Comments::query()
//            ->where('commentable_type = :type:',['type'=>'Episodes'])
//            ->inWhere('commentable_id',$ids)
//            ->execute();
////        iterator_to_array($comments)
////        dd($comments);
//        dd($movie->getEpisodesComments()->toArray());

        //为什么不能够直接在这个网站上进行命令行的操作呢？laravel做到了，能够让人很方便的将最近的一些数据对象操作起来
//        $movies = Movies::query()
//            ->where('title like :keyword:',['keyword'=>'%七年%'])
//            ->execute();
//
////        dd($movies->toArray());
//        foreach($movies as $m){
//            /** @var Movies $m */
//            $m->addTag('人生七年');
//        }
//        dd($movie->getEpisodeNextOnTV());
//        dd($movie->getWatchedRecords());
        $this->view->movie = $movie;
        $this->view->form = myForm::buildCommentForm($movie);
        $this->view->comments = $movie->comments();
    }
    public function editAction(Request $request,Movies $movie)
    {
        if($request->isPost()){
            $data = $request->getPost();
            if (preg_match('%^https?://%m', $data['poster'])) {
                $data['poster']=(new getpic())->get($data['poster']);
            }
            $movie->update($data);
            return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
        }else{
            $this->view->form = $this->buildFormFromModel($movie);
            $this->view->movie = $movie;
        }
    }

    public function deleteAction(Movies $movie)
    {
        if($this->request->isPost()){
            $movie->delete();
            return $this->redirectByRoute(['for'=>'movies.index']);
        }else{
            $this->view->movie = $movie;
        }
    }

    public function searchAction($search,$page = 1,Movies $movie)
    {
        //1、这个搜索应该设置中间件来对关键词进行过滤的！直接的路由正则过滤是否就足够了？
        //2、是否需要对search关键词进行保存，以便将来进行行为分析，看看都搜索过哪些关键词？这个也许是将来的一个数据挖掘的要点，当然也可以用log的形式来做记录和分析
        //3、需要增加多个关键词的搜索情况，比如多个关键词都存在的情况下如何检索，这个可以在Model中增加处理逻辑
        $page = $this->getPaginator($movie->search($search),25,$page);
        $this->view->page = $page;
        $this->view->search =$search;
    }


    public function showCommentListAction()
    {
        $comments = Comments::query()
            ->orderBy('updated_at Desc')
            ->execute();
        foreach($comments as $c){
            echo $c->created_at.' - '.$c->content.'<br>';
        }
        die();
    }


    public function addCommentAction(Movies $movie)
    {
//        dd($movie);
        $movie->addCommentBy($this->auth,$this->request->getPost());
        return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
    }

    public function editCommentAction(Movies $movie,Comments $comment)
    {
        //这里应该用中间件将这种验证抽出去
//        if(!$this->auth->has($comment)) dd('你没有去权利修改该评论，只有原作者：'.$comment->user()->name.'才能修改');

//        dd($comment->getEditURL());
        if($this->request->isPost()){
            $data = $this->request->getPost();
            $comment->update($data);
            return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
        }

        $this->view->movie = $movie;
        $this->view->form = myForm::buildCommentForm($movie,$comment);
    }




    public function deleteCommentAction(Movies $movie,Comments $comment)
    {
        if(!$this->auth->has($comment)) dd('你没有权利删除该评论,只有原作者:'.$comment->user()->name.'才能删除');

        if($this->request->isPost()){
            $comment->delete();
            return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
        }

        $this->view->comment = $comment;
    }



    
    public function showLinksAction(Movies $movie)
    {

        $this->view->movie = $movie;

    }

    public function deleteLinksAction(Movies $movie,Links $link)
    {
        if(!$this->auth->has($link)) dd('你没有权利删除此链接，只有原作者可以删除');
        $link->delete();
        return $this->redirectByRoute(['for'=>'movies.movieLinks','movie'=>$movie->id]);
    }

    public function addLinkAction(Movies $movie)
    {
        $movie->addLinkBy($this->auth,$this->request->getPost()['url']);
        return $this->redirectByRoute(['for'=>'movies.movieLinks','movie'=>$movie->id]);
    }

    public function addTagAction(Request $request,Movies $movie)
    {
        $data = $request->getPost();

        $movie->addTag(trim($data['tagName']),$this->auth);

        return $this->redirectByRoute(['for'=>'movies.showTags','movie'=>$movie->id]);
    }

    public function showTagsAction(Movies $movie)
    {

//        $this->view->auth = Users::findFirst($this->session->get('auth')['id']);
        $this->view->movie = $movie;
    }

    public function deleteTagAction(Movies $movie,Taggables $taggable)
    {
        $taggable->delete();
        return $this->redirectByRoute(['for'=>'movies.showTags','movie'=>$movie->id]);
    }





    public function getDoubanMovieAction($doubanid)
    {
        $movie = Movies::findFirst(['conditions'=>'doubanid = :doubanid:','bind'=>['doubanid'=>$doubanid]]);
        if(!$movie){
            $data = (new doubanmovie())->getinfo($doubanid);
            //异常处理如何做？

//            var_dump($data);die();
            $movie = new Movies();
            $movie->save($data);
        }
        return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
    }

    public function updateInfoFromDoubanAction(Movies $movie)
    {
        //1、获取数据
        $data = (new doubanmovie())->getinfo($movie->doubanid);
        //@todo 图片的重新下载问题？
        //2、异常如何处理？

        //3、更新数据库数据
        $movie->update($data);

        //4、转接到更新后的book页面
        return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
    }


    public function addFavoriteAction(Movies $movie)
    {
        $movie->addFavoriteBy($this->auth);
        echo json_encode(['status'=>'success']);
        return false;
    }

    public function deleteFavoriteAction(Movies $movie)
    {
//        if ($this->auth->deleteFavorite($movie)) {
//            echo json_encode(['status'=>'success']);
//        }else{
//            echo json_encode(['status'=>'failed']);
//        }
        $movie->removeFavoriteBy($this->auth);
        echo json_encode(['status'=>'success']);
        return false;
    }

    public function becomeSerialAction(Movies $movie)
    {
        $movie->changeToSerial();
        return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
    }
    public function addSerialToAction(Movies $movie,Movies $anotherMovie)
    {
        $movie->combineToSerialWith($anotherMovie);
        return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
    }
    public function showSerialMoviesAction(Movies $movie)
    {
        $this->view->movies = $movie->getSerialMovies();
        if(!$this->view->movies) $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
        $this->view->movie = $movie;
    }
    public function deleteSerialAction(Movies $movie)
    {
        $movie->deleteSerial();
        return $this->redirectBack();
    }

    public function editSerialAction(Movies $movie,Serials $serial)
    {
        if($this->request->isPost()){
            $serial->update($this->request->getPost());
            $tvserial = $serial->getTVSerial();
            return $this->redirectByRoute(['for'=>'tvserials.show','tvserial'=>$tvserial->id]);
        }
        $this->view->serial=$serial;
        $this->view->form = myForm::buildFormFromModel($serial);
        $this->view->movie = $movie;

    }


    public function showEpisodeAction(Movies $movie,Episodes $episode)
    {
//        dd($movie->getEpisodes());
//        dd((new Carbon())->createFromTimestamp(strtotime($episode->date))->format('l, M d Y'));
//        $episodes = Episodes::find();
//        foreach($episodes as $e){
//            $e->date=(new Carbon())->createFromTimestamp(strtotime($e->date))->toDateTimeString();
//            $e->save();
//        };
        $this->view->movie = $movie;
        $this->view->episode = $episode;
        $this->view->form = myForm::buildCommentForm($episode);
        $this->view->comments = $episode->comments();
    }

    public function addLinkToEpisodeAction($movie,Episodes $episode)
    {
        $episode->addLinkBy($this->auth,$this->request->getPost()['url']);
        return $this->redirectByRoute(['for'=>'movies.showEpisode','movie'=>$movie,'episode'=>$episode->id]);
    }

    public function deleteLinkFromEpisodeAction($movie,$episode,Links $link)
    {
        $link->delete();
        return $this->redirectByRoute(['for'=>'movies.showEpisode','movie'=>$movie,'episode'=>$episode]);
    }





    public function addToListAction(Movies $movie,$page=0)
    {
        $this->view->movie = $movie;
        $this->view->page = $this->getPaginator(Lists::find(),25,$page);
        $this->view->listForm = myForm::buildListForm($movie);
        $this->view->relatedLists = $movie->getRelatedLists();
        $this->view->lists = $movie->getNotRelatedLists($this->view->relatedLists);

    }

    public function addToExitedListAction(Movies $movie,Lists $list,Request $request)
    {
        $movie->addToExitedList($list,$request);
        return $this->redirectByRoute(['for'=>'movies.addToList','movie'=>$movie->id]);
    }

    public function deleteFromListAction(Movies $movie,Lists $list)
    {
        $movie->deleteFromList($list);
        return $this->redirectByRoute(['for'=>'movies.addToList','movie'=>$movie->id]);

    }

    public function updateEpisodesInfoAction(Movies $movie)
    {
//        dd($movie);
        $movie->getSerial()->getTVSerial()->updateEpisodesInfo(null,$movie->getSerial()->serial_num);
        return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
    }

    public function addToWatchListAction(Movies $movie)
    {
        $movie->addToWatchListBy($this->auth);
        return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
    }
    public function closeWatchListAction(Movies $movie,Watchlist $watchList)
    {
//        dd($watchList);
        $watchList->status = 'done';
        $watchList->save();
        return $this->redirectByRoute(['for'=>'movies.show','movie'=>$movie->id]);
    }
    public function updateWatchListAction($movie,$episode,Watchlist $watchList)
    {
//        dd($watchList);
        $watchList->currentEpisode_id = $episode;
        $watchList->status = 'doing';
        $watchList->save();
        return $this->redirectByRoute(['for'=>'movies.showEpisode','movie'=>$movie,'episode'=>$episode]);
    }


















    /**
     *将电影变成internet链接，以便于用everything软件来快速的检索到，这个将来可以将图书也弄成这种形式，方便检索
     */
    private function dumpMoves2internetLinks()
    {
        $movies = Movies::find();
        foreach($movies as $movie){
            $title = $movie->title;
            $url = $this->url->get(['for'=>'movies.show','movie'=>$movie->id]);
            echo $title.'-'.$url.'<br>';
            $data = '[InternetShortcut]
URL='.$url;

            $filename = 'temp/'.$title.'.url';
            $filename =  iconv('UTF-8','GBK//IGNORE',preg_replace('|:|',' ',$filename));
            file_put_contents($filename,$data);
        }
    }




}

