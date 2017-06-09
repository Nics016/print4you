<?php

use yii\db\Migration;

class m170607_123417_constructor_sizes extends Migration
{
    public function up()
    {
        $this->createTable('constructor_sizes', [
            'id' => $this->primaryKey(),
            'size' => $this->string(10)->notNull(),
            'sequence' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_sizes');
    }

}
