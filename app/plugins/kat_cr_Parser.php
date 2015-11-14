<?php

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/10/25
 * Time: 9:37
 */
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
class kat_cr_Parser implements parserInterface
{
    function __construct()
    {
        $this->client = new Client();
    }

    public function parseTVSerialsinfo($url)
    {
        $crawler = $this->client->request('get',$url);//这步骤为什么有问题呢？怎么获得一部电视剧的每一集的清单呢？后来发现是什么证书的事情


        //获取电视剧信息播放信息
//        $(".torrentMediaInfo .dataList li").filter(function(){return $(this).html().match(/Original run/)}).html()
//        "<strong>Original run: </strong>13 June 2005 — 12 December 2010"
        $dom0 = $crawler->filter(".torrentMediaInfo .dataList li");
        $info = [];
        $dom0->each(function($node) use(&$info){
            if (preg_match('%<strong>Original run: </strong>(.+) — (.+)%m', $node->html(), $regs)) {
                $info = [
                    'start'=>$regs[1],
                    'end'=>$regs[2]
                ] ;
            }
        });


        //获取剧集信息
        $dom = $crawler->filter('h3');

        //获取季度的相应html数据
        $seasons = [];
        $dom->each(function($node) use(&$seasons){
            $season = $node->html();
            if(preg_match('|Season|',$season)){
                $seasons[$season]=$node->nextAll()->html();
            }
        });

        //抽取出其中的一些epesode数据
        foreach($seasons as $season=>$row){
            preg_match_all('%<span class="versionsEpNo">(.+)\s</span>\s*<span class="versionsEpName">(.+)</span>\s*<section>\s*<span class="versionsEpDate">(.*)</span>%m', $row, $result, PREG_PATTERN_ORDER);
            $num = $result[1];
            $title = $result[2];
            $date = $result[3];
            $result = [];
            foreach($num as $key=>$value){
                $result[]=[
                    'num'=>(int)preg_replace('|Episode\s+([0-9]+)|','$1',$num[$key]),
                    'title'=>$title[$key],
                    'date'=>$date[$key]
                ];
            }

            $seasons[(int) preg_replace('|^Season\s+([0-9]+)$|','$1',$season)]=$result;

        }
        return ['info'=>$info,'seasons'=>$seasons];
    }

}