<?php

use yii\db\Migration;

class m170624_210554_constructor_print_price_attendance extends Migration
{
    public function safeUp()
    {
        $this->createTable('constructor_print_price_attendance', [
            'price_id' => $this->integer()->notNull(),
            'attendance_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('constructor_print_price_attendances_pkey', 'constructor_print_price_attendance', ['price_id', 'attendance_id']);
    }

    public function safeDown()
    {
        $this->dropPrimaryKey('constructor_print_price_attendances_pkey');
        $this->dropTable('constructor_print_price_attendance');
    }
}
