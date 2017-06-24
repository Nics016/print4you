<?php

use yii\db\Migration;

class m170624_145102_constructor_print_prices extends Migration
{
    public function safeUp()
    {
        $this->createTable('constructor_print_prices', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull(), // тип печати
            'material_id' => $this->integer()->notNull(), // от какого материала зависит
            'size_id' => $this->integer(), // размер печати
            'price' => $this->integer()->notNull(), // розничная цена
            'gross_price' => $this->text(), // оптовые цены
            'min_count' => $this->integer()->notNull(), // при каком количестве товара появляется
            'color' => $this->integer(), // цветность
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('constructor_print_prices');
    }
}
