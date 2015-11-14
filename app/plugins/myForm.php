<?php
use Phalcon\Forms\Form;

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2015/10/24
 * Time: 11:44
 */
class myForm
{
    public static function buildCommentForm(myModel $object,Comments $comment = null)
    {
        if($comment){
            $form = new Form($comment);
            $form->Url = self::getEditURL($object,$comment);
        }else{
            $form = new Form();
            $form->Url = self::getAddURL($object);
        }
        $form->add(new \Phalcon\Forms\Element\TextArea('content'));
        $form->add(new \Phalcon\Forms\Element\Submit('Add Comment'));
        return $form;
    }

    public static function buildListForm(Movies $movie,Lists $list = null)
    {
        if($list){
            $form = new Form($list);
            $form->Url = '';
        }else{
            $form = new Form();
            $form->Url = \Phalcon\Di::getDefault()->get('url')->get(['for'=>'lists.addToMovie','movie'=>$movie->id]);
//            $form->add(new \Phalcon\Forms\Element\Hidden('movie_id',['value'=>$movie->id]));
        }
        $form->add(new \Phalcon\Forms\Element\Text('name'));
        $form->add(new \Phalcon\Forms\Element\TextArea('description'));
        $form->add(new \Phalcon\Forms\Element\Submit('Add List'));
        return $form;
    }

    public static function buildFormFromModel(myModel $object)
    {
        $form = new Form($object);
        $fields =[];
        foreach($object->columnMap() as $column){
            if(!in_array($column,['created_at','updated_at','id'])){
                $form->add(new \Phalcon\Forms\Element\Text($column));
                $fields[]=$column;
            };
        }
        $form->fields =$fields;
        $form->add(new \Phalcon\Forms\Element\Submit('修改'));
        return $form;
    }





    private static function getEditURL($object, $comment)
    {
        return strtolower(get_class($object)).'/'.$object->id."/comments/".$comment->id."/edit";
    }

    private static function getAddURL($object)
    {
        return strtolower(get_class($object)).'/'.$object->id."/addComment";
    }



}