<?php

use yii\db\Migration;

class m170607_102000_constructor_categories extends Migration
{
    public function up()
    {
        $this->createTable('constructor_categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'alias' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'img' => $this->string(255)->notNull(),
            'img_alt' => $this->string(255),
            'sequence' => $this->integer()->notNull(),
            'seo_title' => $this->string(255),
            'seo_description' => $this->string(255),
            'seo_keywords' => $this->string(255),
            'h1_tag_title' => $this->string(255)->notNull(),
            'menu_title' => $this->string(255)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_categories');
    }

}
