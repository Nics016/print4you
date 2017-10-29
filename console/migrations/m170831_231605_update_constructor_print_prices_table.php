<?php

use yii\db\Migration;

class m170831_231605_update_constructor_print_prices_table extends Migration
{
    public function safeUp()
    {
        // gross price for white textile only
        $this->addColumn('constructor_print_prices', 'gross_price_white', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn('constructor_print_prices', 'gross_price_white');
    }
}
