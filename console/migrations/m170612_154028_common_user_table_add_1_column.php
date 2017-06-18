<?php

use yii\db\Migration;

class m170612_154028_common_user_table_add_1_column extends Migration
{
    public function up()
    {
        $this->addColumn('common_user', 'discount_card_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('common_user', 'discount_card_id');
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
