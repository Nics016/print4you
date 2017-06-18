<?php

use yii\db\Migration;

class m170618_152249_orders_add_1_column extends Migration
{
    public function up()
    {
        $this->addColumn('orders', 'client_name', $this->string());
    }

    public function down()
    {
        $this->dropColumn('orders', 'client_name');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
