<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/10/20
 * Time: 5:26
 */
interface KeywordableInterface
{
    public function keywords();
    public function updateKeyWords($keywords);
    public function createKeyWords();
    public function deleteKeywords();
    public static function findWithKeywords($id);

    public function getRelatedMovies();// @todo 耦合问题

}