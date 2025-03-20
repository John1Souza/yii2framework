<?php

use yii\base\Security;
use yii\db\Migration;

class m250319_163227_insert_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->up();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250319_163227_insert_admin_user cannot be reverted.\n";

        return false;
    }
    public function up()
    {
        $security = new Security();
        $passwordHash = $security->generatePasswordHash('123456');
        $authKey = $security->generateRandomString(32);
        $time = time();

        $this->insert('{{%user}}', [
            'username' => 'admin',
            'auth_key' => $authKey,
            'password_hash' => $passwordHash,
            'email' => 'admin@exemplo.com',
            'status' => 10,
            'created_at' => $time,
            'updated_at' => $time,
        ]);
    }

    public function down()
    {
        $this->delete('{{%user}}', ['username' => 'admin']);
    }
}
