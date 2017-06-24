<?php

use yii\db\Migration;

class m170623_131347_constructor_product_materials extends Migration
{
    public function up()
    {
        $this->createTable('constructor_product_materials', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_product_materials');
    }

}
