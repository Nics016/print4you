<?php

use yii\db\Migration;

class m170629_110122_reviews extends Migration
{
    public function safeUp()
    {
        $this->createTable('reviews', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'is_like' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->timestamp()->notNull(),
            'is_published' => $this->boolean()->notNull()->defaultValue(false),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('reviews');
    }

}
