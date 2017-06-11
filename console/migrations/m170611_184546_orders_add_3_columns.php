<?php

use yii\db\Migration;
use common\models\Orders;

class m170611_184546_orders_add_3_columns extends Migration
{
    public function up()
    {
        $this->addColumn('orders', 'executor_id', $this->integer());
        $this->addColumn('orders', 'courier_id', $this->integer());
        $this->addColumn('orders', 'location', $this->integer()->defaultValue(Orders::LOCATION_MANAGER_NEW));
    }

    public function down()
    {
        $this->dropColumn('orders', 'executor_id');
        $this->dropColumn('orders', 'courier_id');
        $this->dropColumn('orders', 'location');
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
