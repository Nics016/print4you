<?php

use yii\db\Migration;

class m170607_114449_constructor_products extends Migration
{
    public function up()
    {
        $this->createTable('constructor_products', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'image' => $this->string(255)->notNull(),
            'price' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_products');
    }
}
