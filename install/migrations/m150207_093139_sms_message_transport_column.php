<?php

use yii\db\Schema;
use yii\db\Migration;

class m150207_093139_sms_message_transport_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%sms_message}}', 'transport', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('{{%sms_message}}', 'transport');
    }
}
