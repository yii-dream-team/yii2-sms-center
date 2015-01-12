<?php

use yii\db\Schema;
use yii\db\Migration;

class m150112_180011_install_sms_center extends Migration
{
    public function up()
    {
        $this->createTable('{{%sms_message}}', [
            'id' => Schema::TYPE_PK,
            'status' => Schema::TYPE_INTEGER,
            'priority' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'to' => Schema::TYPE_STRING,
            'text' => Schema::TYPE_STRING,
            'createdAt' => Schema::TYPE_DATETIME,
            'updatedAt' => Schema::TYPE_DATETIME,
        ], 'Engine=InnoDB');
    }

    public function down()
    {
        $this->dropTable('{{%sms_message}}');
    }
}
