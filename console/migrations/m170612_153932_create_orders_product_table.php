<?php

use yii\db\Migration;

/**
 * Handles the creation of table `orders_product`.
 */
class m170612_153932_create_orders_product_table extends Migration
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

        $this->createTable('orders_product', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'product_id' => $this->integer(),
            'name' => $this->string(255),
            'price' => $this->integer()->defaultValue(0),
            'is_constructor' => $this->boolean()->defaultValue(false),
            'front_image' => $this->string(255),
            'back_image' => $this->string(255),
            'count' => $this->integer()->defaultValue(1),
            'size_id' => $this->integer(),
            'color_id' => $this->integer(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('orders_product');
    }
}
