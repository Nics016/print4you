<?php

use yii\db\Migration;

class m170712_122256_constructor_additional_sides extends Migration
{
    public function safeUp()
    {
        $this->createTable('constructor_additional_sides', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('constructor_additional_sides');
    }
}
