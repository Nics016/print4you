<?php

use yii\db\Migration;

class m170725_214242_pages_seo extends Migration
{
    public function safeUp()
    {
        $this->createTable('pages_seo', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->notNull()->unique(),
            'title' => $this->string(255),
            'keywords' => $this->string(255),
            'description' => $this->string(255),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('pages_seo');
    }
}
