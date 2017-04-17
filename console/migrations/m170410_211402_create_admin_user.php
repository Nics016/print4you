<?php

use yii\db\Migration;
use backend\models\User;

class m170410_211402_create_admin_user extends Migration
{
    public function up()
    {
        $testUser = new User();
        $testUser->username = 'admin';
        $testUser->email = 'nics009@yandex.ru';
        $testUser->role = User::ROLE_ADMIN;
        $testUser->generatePasswordHash('333777');
        $testUser->generateAuthKey();
        $testUser->save();

        $testUser = new User();
        $testUser->username = 'manager';
        $testUser->email = 'nics016@yandex.ru';
        $testUser->role = User::ROLE_MANAGER;
        $testUser->generatePasswordHash('333777');
        $testUser->generateAuthKey();
        $testUser->save();
    }

    public function down()
    {
        User::findByUsername('admin')->delete();
        User::findByUsername('client')->delete();
        return true;
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
