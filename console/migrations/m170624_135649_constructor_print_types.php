<?php

use yii\db\Migration;

class m170624_135649_constructor_print_types extends Migration
{
    public function up()
    {
        $this->createTable('constructor_print_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_print_types');
    }
}
