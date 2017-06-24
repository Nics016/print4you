<?php

use yii\db\Migration;

class m170624_210539_constructor_print_attendance extends Migration
{
    public function safeUp()
    {
        $this->createTable('constructor_print_attendance', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'percent' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('constructor_print_attendance');
    }

}
