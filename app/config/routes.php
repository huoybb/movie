<?php

use Phalcon\Mvc\Router;

$router = new myRouter(false);

$router->addMiddlewaresForEveryRoute(['IsLoginValidator']);

$router->removeExtraSlashes(true);

$router->add('/','Movies::index');
$router->add('/movies/page/{page:[0-9]+}','Movies::index')->setName('index.page');

$router->add('/movies','Movies::index')->setName('movies.index');
$router->add('/movies/{movie:[0-9]+}','Movies::show')->setName('movies.show');
$router->addx('/movies/{movie:[0-9]+}/edit','Movies::edit',[MovieValidator::class])->setName('movies.edit');
$router->add('/movies/{movie:[0-9]+}/delete','Movies::delete')->setName('movies.delete');
$router->add('/movies/{movie:[0-9]+}/becomeSerial','Movies::becomeSerial')->setName('movies.becomeSerial');

$router->add('/movies/search/{search:[^/]+}','Movies::search')->setName('movies.search');
$router->add('/movies/search/{search:[^/]+}/page/{page:[0-9]+}','Movies::search')->setName('movies.search.page');
//更新episodes的信息，从kat网站抓取数据来更新本季数据,
$router->add('/movies/{movie:[0-9]+}/updateEpisodesInfo','Movies::updateEpisodesInfo')->setName('movies.updateEpisodesInfo');
//链接相关的操作
$router->add('/movies/{movie:[0-9]+}/links','Movies::showLinks')->setName('movies.movieLinks');
$router->add('/movies/{movie:[0-9]+}/links/{link:[0-9]+}/delete','Movies::deleteLinks')->setName('movies.deleteLink');
$router->addx('/movies/{movie:[0-9]+}/addlink','Movies::addLink',[LinkValidator::class])->setName('movies.addLink');
//评论相关的操作
$router->add('/movies/comments','Movies::showCommentList')->setName('movies.showCommentLis');
$router->addx('/movies/{movie:[0-9]+}/addComment','Movies::addComment',[CommentValidator::class])->setName('movies.addComment');
$router->addx('/movies/{movie:[0-9]+}/comments/{comment:[0-9]+}/edit','Movies::editComment',[CommentValidator::class,HasAuthorityValidator::class.':comment'])->setName('movies.editComment');
$router->addx('/movies/{movie:[0-9]+}/comments/{comment:[0-9]+}/delete','Movies::deleteComment',[HasAuthorityValidator::class.':comment'])->setName('movies.deleteComment');
//收藏相关的操作
$router->add('/movies/{movie:[0-9]+}/addFavorite','Movies::addFavorite')->setName('movies.addFavorite');
$router->add('/movies/{movie:[0-9]+}/deleteFavorite','Movies::deleteFavorite')->setName('movies.deleteFavorite');
//与电视剧的标签相关的操作
$router->addx('/movies/{movie:[0-9]+}/addTag','Movies::addTag',[TagValidator::class])->setName('movies.addTag');
$router->add('/movies/{movie:[0-9]+}/tags','Movies::showTags')->setName('movies.showTags');
$router->addx('/movies/{movie:[0-9]+}/taggable/{taggable:[0-9]+}/delete','Movies::deleteTag',[HasAuthorityValidator::class.':taggable'])->setName('movies.deleteTag');

//与电视剧的季数相关的操作，应该是season，这里用serial来表示
$router->add('/movies/{movie:[0-9]+}/addSerialTo/{anotherMovie:[0-9]+}','Movies::addSerialTo')->setName('movies.addSerialTo');
$router->add('/movies/{movie:[0-9]+}/serials','Movies::showSerialMovies')->setName('movies.showSerialMovies');
$router->add('/movies/{movie:[0-9]+}/deleteSerial','Movies::deleteSerial')->setName('movies.deleteSerial');
$router->add('/movies/{movie:[0-9]+}/Serial/{serial:[0-9]+}/edit','Movies::editSerial')->setName('movies.editSerial');

//与Episodes相关的操作
$router->add('/movies/{movie:[0-9]+}/Episodes/{episode:[0-9]+}','Movies::showEpisode')->setName('movies.showEpisode');
$router->add('/movies/{movie:[0-9]+}/Episodes/{episode:[0-9]+}/addlink','Movies::addLinkToEpisode')->setName('movies.addLinkToEpisode');
$router->add('/movies/{movie:[0-9]+}/Episodes/{episode:[0-9]+}/link/{link:[0-9]+}/delete','Movies::deleteLinkFromEpisode')->setName('movies.deleteLinkFromEpisode');

//与电视剧的list表单相关操作，类似豆瓣的豆列一样
$router->add('/movies/{movie:[0-9]+}/addToList','Movies::addToList')->setName('movies.addToList');
$router->add('/movies/{movie:[0-9]+}/addToList/page/{page:[0-9]+}','Movies::addToList')->setName('movies.addToListPaged');//避免列表过长
$router->add('/movies/{movie:[0-9]+}/addTo/List/{list:[0-9]+}','Movies::addToExitedList')->setName('movies.addToExitedList');
$router->add('/movies/{movie:[0-9]+}/deleteFromList/{list:[0-9]+}','Movies::deleteFromList')->setName('movies.deleteFromList');


//从豆瓣获取信息相关路由
$router->add('/movies/getdoubanmovie/{doubanid:[0-9]+}','Movies::getDoubanMovie')->setName('movies.getDoubanMovie');
$router->add('/movies/{movie:[0-9]+}/updateInfoFromDouban','Movies::updateInfoFromDouban')->setName('movies.updateInfoFromDouban');

//watchList相关操作
$router->add('/movies/{movie:[0-9]+}/addToWatchList','Movies::addToWatchList')->setName('movies.addToWatchList');
$router->add('/movies/{movie:[0-9]+}/closeWatchList/{watchList:[0-9]+}','Movies::closeWatchList')->setName('movies.closeWatchList');
$router->add('/movies/{movie:[0-9]+}/updateWatchList/{watchList:[0-9]+}/episode/{episode:[0-9]+}','Movies::updateWatchList')->setName('movies.updateWatchList');

$router->add('/tags/page/{page:[0-9]+}','Tags::index')->setName('tags.index');
$router->add('/tags','Tags::index')->setName('tags.index');
$router->add('/tags/{tag:[0-9]+}','Tags::show')->setName('tags.show');
$router->add('/tags/{tag:[0-9]+}/edit','Tags::edit')->setName('tags.edit');
$router->add('/tags/{tag:[0-9]+}/page/{page:[0-9]+}','Tags::show')->setName('tags.show.page');
$router->add('/tags/{tag:[0-9]+}/addComment','Tags::addComment')->setName('tags.addComment');

$router->add('/sites/page/{page:[0-9]+}','Sites::index')->setName('sites.index');
$router->add('/sites','Sites::index')->setName('sites.index');
$router->add('/sites/{site:[0-9]+}','Sites::show')->setName('sites.show');
$router->add('/sites/{site:[0-9]+}/page/{page:[0-9]+}','Sites::show')->setName('sites.show.page');
$router->add('/sites/{site:[0-9]+}/edit','Sites::edit')->setName('sites.edit');
$router->add('/sites/{site:[0-9]+}/addComment','Sites::addComment')->setName('sites.addComment');
$router->add('/sites/{site:[0-9]+}/comments/{comment:[0-9]+}/edit','Sites::editComment')->setName('sites.editComment');
$router->add('/sites/{site:[0-9]+}/comments/{comment:[0-9]+}/delete','Sites::deleteComment')->setName('sites.deleteComment');

//验证、权限相关操作
$router->add('/auth/resource2roles','Auth::resource2roles')->setName('auth.resource2roles');
$router->addx('/auth/login','Auth::login',[loginValidator::class])->setName('auth.login');
$router->addx('/auth/logout','Auth::logout',[loginValidator::class])->setName('auth.logout');

//UserController相关路由
$router->add('/users','Users::index')->setName('users.index');
$router->add('/users/{user:[0-9]+}','Users::show')->setName('users.show');
$router->add('/users/{user:[0-9]+}/edit','Users::edit')->setName('users.edit');

$router->add('/users/{user:[0-9]+}/comments','Users::showComments')->setName('users.showComments');

$router->add('/users/{user:[0-9]+}/tags/page/{page:[0-9]+}','Users::showTags')->setName('users.showTags.page');
$router->add('/users/{user:[0-9]+}/tags','Users::showTags')->setName('users.showTags');

$router->addx('/users/{user:[0-9]+}/tags/{tag:[0-9]+}/delete','Users::deleteTag',[HasAuthorityValidator::class.':tag'])->setName('users.deleteTag');

$router->add('/users/{user:[0-9]+}/links/{page:[0-9]+}','Users::showLinks')->setName('users.showLinks');
$router->add('/users/{user:[0-9]+}/links','Users::showLinks')->setName('users.showLinks');
$router->addx('/users/{user:[0-9]+}/links/{link:[0-9]+}/delete','Users::deleteLink',[HasAuthorityValidator::class.':link'])->setName('users.deleteLink');

$router->add('/users/{user:[0-9]+}/favorites/page/{page:[0-9]+}','Users::showFavorites')->setName('users.showFavorites');
$router->add('/users/{user:[0-9]+}/favorites','Users::showFavorites')->setName('users.showFavorites');

//vote相关的操作
$router->add('/users/vote/comment/{comment:[0-9]+}/{YesOrNo:yes|no}','Users::voteForComment')->setName('users.voteForComment');


$router->add('/lists/{list:[0-9]+}','Lists::show')->setName('lists.show');
$router->add('/lists/add','Lists::add')->setName('lists.add');
$router->add('/lists/addToMovie/{movie:[0-9]+}','Lists::addToMovie')->setName('lists.addToMovie');


$router->add('/tvserials','Tvserial::index')->setName('tvserials.index');
$router->add('/tvserials/page/{page:[0-9]+}','Tvserial::index')->setName('tvserials.index.page');
$router->add('/tvserials/{tvserial:[0-9]+}','Tvserial::show')->setName('tvserials.show');
$router->add('/tvserials/{tvserial:[0-9]+}/addlink','Tvserial::addLink')->setName('tvserials.addLink');
$router->add('/tvserials/{tvserial:[0-9]+}/updateEpisodes/{link:[0-9]+}','Tvserial::updateEpisodes')->setName('tvserials.updateEpisodes');

$router->addx('/episodes/{episode:[0-9]+}/addComment','Episodes::addComment',[CommentValidator::class])->setName('episodes.addComment');
$router->add('/episodes/{episode:[0-9]+}/comments/{comment:[0-9]+}/edit','Episodes::editComment')->setName('episodes.editComment');
$router->add('/episodes/{episode:[0-9]+}/comments/{comment:[0-9]+}/delete','Episodes::deleteComment')->setName('episodes.deleteComment');

$router->add('/calendar/latest','Calendar::latest')->setName('calendar.latest');
$router->add('/calendar/MyLatest','Calendar::MyLatest')->setName('calendar.MyLatest');
$router->add('/calendar/MyWatchList','Calendar::MyWatchList')->setName('calendar.MyWatchList');
return $router;