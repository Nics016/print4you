<?php

use yii\db\Migration;

class m170618_133000_user_cart extends Migration
{
    public function up()
    {
        $this->createTable('user_cart', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'cart_data' => $this->text(),
            'expire_in' => $this->timestamp(),
        ]);
    }

    public function down()
    {
       $this->dropTable('user_cart');
    }


}
