<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

/**
 * Class WorkersProjectsMigration_100
 */
class WorkersProjectsMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph(): void
    {
    }

    /**
     * Run the migrations
     *
     * @return void
     * @throws \Phalcon\Db\Exception
     */
    public function up(): void
    {
        $this->morphTable(
            'workers_projects',
            [
                'columns' => [
                    new Column(
                        'worker_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'project_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'worker_id'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['worker_id', 'project_id'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
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
    public function down(): void
    {
        self::$_connection->dropTable('workers_projects');
    }
}
