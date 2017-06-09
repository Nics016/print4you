<?php

use yii\db\Migration;

class m170607_114608_constructor_product_colors extends Migration
{
    public function up()
    {
        $this->createTable('constructor_product_colors', [
            'product_id' => $this->integer()->notNull(),
            'color_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('constructor_product_colors_pkey', 'constructor_product_colors', ['product_id', 'color_id']);
    }

    public function down()
    {
        $this->dropTable('constructor_product_colors');
    }
}
