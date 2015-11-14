<?php
use Phalcon\Http\Request;

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/10/20
 * Time: 20:03
 */
trait listableTrait
{
    public function createAndAddToListFromRequest(Request $request)
    {
        $data = $request->getPost();
        $list = new Lists();
        $list->save($data);

        $this->addToExitedList($list,$request);
    }
    public function addToExitedList(Lists $list,Request $request)
    {
        $data = $request->getPost();
        $listable = new Listables();
        $listable->list_id = $list->id;
        $listable->user_id = $this->getDI()->getShared('session')->get('auth')['id'];//后面需要替换成登录用户id
        $listable->listable_type = get_class($this);
        $listable->listable_id = $this->id;
        if(isset($data['comment'])) $listable->comment = $data['comment'];
        $listable->save();
    }

    private $relatedLists = null;
    public function getRelatedLists()
    {
        if(null == $this->relatedLists) $this->relatedLists = Lists::query()
            ->leftJoin('Listables','Listables.list_id = Lists.id')
            ->where('Listables.listable_type = :type:',['type'=>get_class($this)])
            ->andWhere('Listables.listable_id = :id:',['id'=>$this->id])
            ->groupBy('Listables.id')
            ->execute();
        return $this->relatedLists;
    }

    private $notRelatedLists = null;
    public function getNotRelatedLists($exluded)
    {
        if(null == $this->notRelatedLists) {
            $query = Lists::query();

            if($exluded->count()){
                $lists = [];
                foreach($exluded as $row){
                    $lists[]=$row->id;
                }
                $query= $query->notInWhere('id',$lists);
            }
//            dd($query);
            $this->notRelatedLists = $query->execute();
        }

        return $this->notRelatedLists;
    }

    /**
     * @param Lists|null $list  如果为空，则清楚所有已有的影单中的该部电影
     * @return $this
     */
    public function deleteFromList(Lists $list = null)
    {
        $query = Listables::query()
            ->Where('listable_type = :type:',['type'=>get_class($this)])
            ->andWhere('listable_id = :id:',['id'=>$this->id]);
        if(null <> $list) $query = $query->andWhere('list_id = :list_id:',['list_id'=>$list->id]);
        $listables =$query->execute();
        foreach($listables as $listable){
            $listable->delete();
        }
        return $this;
    }

    public function deleteFromListHook()
    {
        $this->deleteFromList();
        return $this;
    }






}