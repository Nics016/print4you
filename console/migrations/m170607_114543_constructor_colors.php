<?php

use yii\db\Migration;

class m170607_114543_constructor_colors extends Migration
{
    public function up()
    {
        $this->createTable('constructor_colors', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'color_value' => $this->string(50)->notNull(),
            'front_image' => $this->string(255)->notNull(),
            'back_image' => $this->string(255)->notNull(),
            'product_id' => $this->integer()->notNull(),
            'sizes' => $this->string(255)->notNull(),
            'is_published' => $this->boolean()->notNull()->defaultValue(false),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_colors');
    }
}
