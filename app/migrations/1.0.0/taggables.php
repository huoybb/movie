<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class TaggablesMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'taggables',
            array(
            'columns' => array(
                new Column(
                    'tag_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'first' => true
                    )
                ),
                new Column(
                    'taggable_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'tag_id'
                    )
                ),
                new Column(
                    'taggable_type',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'taggable_id'
                    )
                ),
                new Column(
                    'created_at',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'taggable_type'
                    )
                ),
                new Column(
                    'updated_at',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'created_at'
                    )
                ),
                new Column(
                    'id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 10,
                        'after' => 'updated_at'
                    )
                ),
                new Column(
                    'user_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'size' => 255,
                        'after' => 'id'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('taggables_tag_id_index', array('tag_id')),
                new Index('taggables_taggable_id_index', array('taggable_id'))
            ),
            'references' => array(
                new Reference('taggables_tag_id_foreign', array(
                    'referencedSchema' => 'movie',
                    'referencedTable' => 'tags',
                    'columns' => array('tag_id'),
                    'referencedColumns' => array('id')
                ))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '134',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
