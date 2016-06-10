<?php

use yii\db\Migration;

class m160603_124624_visit extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%status}}', [
            'id' => $this->primaryKey(),
            'today_visitors' => $this->integer(11)->defaultValue(0),
            'yesterday_visitors' => $this->integer(11)->defaultValue(0),
            'this_month_visitors' => $this->integer(11)->defaultValue(0),
            'last_month_visitors' => $this->integer(11)->defaultValue(0),
            'total_visitors' => $this->integer(11)->defaultValue(0),
            'current_time' => $this->integer(11)->defaultValue(0),
        ],$tableOptions);
        $this->insert('{{%status}}',['current_time' => time()]);

        $this->createTable('{{%visitors}}', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(20),
            'visit_date' => $this->integer(11),
            'group_date' => $this->integer(11),
            'location' => $this->string(2000),
            'browser' => $this->string(60),
            'os' => $this->string(30)
        ],$tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%status}}');
        $this->dropTable('{{%visitors}}');
    }
}
