<?php

use yii\db\Migration;

class m170622_205524_constructor_print_sizes extends Migration
{
    public function up()
    {
        $this->createTable('constructor_print_sizes', [
            'id' => $this->primaryKey(),
            'name' => $this->string(10)->notNull(),
            'percent' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_print_sizes');
    }
}
