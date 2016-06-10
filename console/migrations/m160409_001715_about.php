<?php

use yii\db\Schema;
use yii\db\Migration;

class m160409_001715_about extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%about}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->unique(),
            'avatar' => $this->string(15),
            'email' => $this->string(255),
            'name' => $this->string(55),
            'short_text' => $this->text(),
            'more_text' => $this->text(),
            'facebook' => $this->string(1000),
            'twitter' => $this->string(1000),
            'googleplus' => $this->string(1000),
            'instagram' => $this->string(1000),
            'linkedin' => $this->string(1000),
        ],$tableOptions);

        $this->addForeignKey('fk_about_user','{{%about}}','user_id','{{%user}}','id','CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%about}}');
    }
}
