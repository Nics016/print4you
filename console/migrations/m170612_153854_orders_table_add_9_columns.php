<?php

use yii\db\Migration;

class m170612_153854_orders_table_add_9_columns extends Migration
{
    public function up()
    {
        $this->addColumn('orders', 'client_id', $this->integer());
        $this->addColumn('orders', 'discount_percent', $this->integer()->defaultValue(0));
        $this->addColumn('orders', 'delivery_required', $this->boolean()->defaultValue(false));
        $this->addColumn('orders', 'is_card', $this->boolean()->defaultValue(false));
        $this->addColumn('orders', 'is_gross', $this->boolean()->defaultValue(false));
        $this->addColumn('orders', 'delivery_office_id', $this->integer());
        $this->addColumn('orders', 'address', $this->string(255));
        $this->addColumn('orders', 'phone', $this->string(255));
        $this->addColumn('orders', 'payment_id', $this->integer());
        $this->addColumn('orders', 'delivery_price', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('orders', 'client_id');
        $this->dropColumn('orders', 'discount_percent');
        $this->dropColumn('orders', 'delivery_required');
        $this->dropColumn('orders', 'is_card');
        $this->dropColumn('orders', 'is_gross');
        $this->dropColumn('orders', 'delivery_office_id');
        $this->dropColumn('orders', 'address');
        $this->dropColumn('orders', 'phone');
        $this->dropColumn('orders', 'payment_id');
        $this->dropColumn('orders', 'delivery_price');
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
