<?php

use yii\db\Migration;

class m170820_032232_update_orders_table extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('orders', 'stock_color_id');
        $this->dropColumn('orders', 'stock_color_liters');
        $this->addColumn('orders', 'stock_color_id', $this->text());
        $this->addColumn('orders', 'stock_color_liters', $this->text());
    }

    public function safeDown()
    {
        return true;
    }
}
