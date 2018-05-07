<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

/**
 * Class HistoryMigration_100
 */
class HistoryMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
    }

    /**
     * Run the migrations
     *
     * @return void
     * @throws \Phalcon\Db\Exception
     */
    public function up()
    {
        $this->morphTable(
            'history',
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'worker_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'project_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'worker_id'
                        ]
                    ),
                    new Column(
                        'date_start',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => null,
                            'size' => 10,
                            'unsigned' => true,
                            'after' => 'project_id'
                        ]
                    ),
                    new Column(
                        'date_end',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => null,
                            'size' => 10,
                            'unsigned' => true,
                            'after' => 'date_start'
                        ]
                    ),
                    new Column(
                        'reason',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'date_end'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ],
            ]
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
    }
}
