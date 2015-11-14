<?php

class TvserialController extends myController
{

    public function indexAction($page = 1)
    {
//        $this->updateTVInfo();
        $this->view->page = $this->getPaginator(Tvserials::fetchAllWithSerialCounts(),25,$page);
    }

    public function showAction(Tvserials $tvserial)
    {
        $this->view->TV = $tvserial;
//        foreach($tvserial->getSerialList() as $serial){
//
//            /** @var Serials $serial */
//            $serial->getEpisodes()->delete();
//        }
//        dd('测试');
    }

    public function addLinkAction(Tvserials $tvserial)
    {
        $tvserial->addLinkBy($this->auth,$this->request->getPost()['url']);
        return $this->redirectByRoute(['for'=>'tvserials.show','tvserial'=>$tvserial->id]);
    }


    public function updateEpisodesAction(Tvserials $tvserial,Links $link)
    {
        $tvserial->updateEpisodesInfo($link->url);
        return $this->redirectByRoute(['for'=>'tvserials.show','tvserial'=>$tvserial->id]);
    }

    /**
     *更新电视剧的信息，如果没有并且具有kat链接的那种
     */
    private function updateTVInfo()
    {
        set_time_limit(0);
        foreach(Tvserials::find() as $tv){
            if(!$tv->start and $tv->getFirstKATLink()){
                $tv->updateEpisodesInfo();
                sleep(1);
            }

        }
    }


}

