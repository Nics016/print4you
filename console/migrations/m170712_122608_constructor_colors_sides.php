<?php

use yii\db\Migration;

class m170712_122608_constructor_colors_sides extends Migration
{
    public function safeUp()
    {
        $this->createTable('constructor_colors_sides', [
            'id' => $this->primaryKey(),
            'color_id' => $this->integer()->notNull(),
            'side_id' => $this->integer()->notNull(),
            'small_image' => $this->string(255),
            'full_image' => $this->string(255),
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('constructor_colors_sides');
    }

}
