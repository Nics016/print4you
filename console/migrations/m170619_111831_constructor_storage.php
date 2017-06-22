<?php

use yii\db\Migration;

class m170619_111831_constructor_storage extends Migration
{
    public function up()
    {
        $this->createTable('constructor_storage', [
            'id' => $this->primaryKey(),
            'color_id' => $this->integer()->notNull(),
            'size_id' => $this->integer()->notNull(),
            'office_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    public function down()
    {
        $this->dropTable('constructor_storage');
    }
}
