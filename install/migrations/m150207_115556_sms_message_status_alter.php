<?php

use yii\db\Schema;
use yii\db\Migration;

class m150207_115556_sms_message_status_alter extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%sms_message}}', 'status', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $this->alterColumn('{{%sms_message}}', 'status', Schema::TYPE_INTEGER);
    }
}
