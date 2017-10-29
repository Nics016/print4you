<?php

use yii\db\Migration;

use common\models\Orders;

/**
 * Handles the creation of table `orders`.
 */
class m170415_161527_create_orders_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'order_status' => $this->string(32)->notNull()->defaultValue("new"),
            'price' => $this->integer()->notNull()->defaultValue(0),
            'manager_id' => $this->integer(),
            'comment' => $this->string(1000)->notNull()->defaultValue(""),
            'courier_comment' => $this->string(1000)->notNull()->defaultValue(""),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'executor_id' => $this->integer(),
            'courier_id' => $this->integer(),
            'location' => $this->integer()->defaultValue(Orders::LOCATION_MANAGER_NEW),
            'client_id' => $this->integer(),
            'discount_percent' => $this->integer()->defaultValue(0),
            'delivery_required' => $this->boolean()->defaultValue(false),
            'is_card' => $this->boolean()->defaultValue(false),
            'is_gross' => $this->boolean()->defaultValue(false),
            'office_id' => $this->integer(),
            'delivery_office_id' => $this->integer(),
            'address' => $this->string(255),
            'phone' => $this->string(255),
            'payment_id' => $this->integer(),
            'delivery_price' => $this->integer()->defaultValue(0),
            'client_name' => $this->string(),
            'stock_color_id' => $this->integer(),
            'stock_color_liters' => $this->decimal()->notNull()->defaultValue(0),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('orders');
    }
}
