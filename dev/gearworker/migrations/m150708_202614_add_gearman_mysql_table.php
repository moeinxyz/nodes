<?php

use yii\db\Schema;
use yii\db\Migration;

class m150708_202614_add_gearman_mysql_table extends Migration
{
    public function up()
    {
        $this->createTable('gearman_queues', [
            'unique_key'    =>      'VARCHAR(64) DEFAULT NULL',
            'function_name' =>      'VARCHAR(255) DEFAULT NULL',
            'priority'      =>      'INT DEFAULT NULL',
            'data'          =>      'LONGBLOB',
            'when_to_run'   =>      'BIGINT DEFAULT NULL',
        ],'ENGINE=InnoDB  DEFAULT CHARSET=utf8');
        $this->createIndex('unique_key', 'gearman_queues', ['unique_key','function_name'], true);
    }

    public function down()
    {
        $this->dropIndex('unique_key', 'gearman_queues');
        $this->dropTable('gearman_queues');
    }
}
