<?php

use yii\db\Migration;

class m170607_123408_constructor_color_sizes extends Migration
{
    public function up()
    {
        $this->createTable('constructor_color_sizes', [
            'color_id' => $this->integer()->notNull(),
            'size_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('constructor_color_sizes_pkey', 'constructor_color_sizes', ['color_id', 'size_id']);
    }

    public function down()
    {   $this->dropPrimaryKey('constructor_color_sizes_pkey');
        $this->dropTable('constructor_color_sizes');
    }
}
