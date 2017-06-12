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
            'full_front_image' => $this->string(255),
            'full_back_image' => $this->string(255),
            'small_front_image' => $this->string(255),
            'small_back_image' => $this->string(255),
            'product_id' => $this->integer()->notNull(),
            'is_published' => $this->boolean()->notNull()->defaultValue(false),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_colors');
    }
}
