<?php

use yii\db\Migration;

class m170607_102000_constructor_categories extends Migration
{
    public function up()
    {
        $this->createTable('constructor_categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'sequence' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_categories');
    }

}
