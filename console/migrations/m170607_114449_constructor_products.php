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
            'full_image' => $this->string(255)->notNull(),
            'small_image' => $this->string(255)->notNull(),
            'img_alt' => $this->string(255),
            'material_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'print_offset_x' => $this->integer()->notNull(),
            'print_offset_y' => $this->integer()->notNull(),
            'print_width' => $this->integer()->notNull(),
            'print_height' => $this->integer()->notNull(),
            'is_published' => $this->boolean()->notNull()->defaultValue(false),
            'seo_title' => $this->string(255),
            'seo_description' => $this->string(255),
            'seo_keywords' => $this->string(255),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_products');
    }
}
