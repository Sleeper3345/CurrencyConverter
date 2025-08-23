<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency}}`.
 */
class m250823_075246_create_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currency}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(3)->notNull()->unique()->comment('Код'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->defaultExpression('NOW()')->notNull()->comment('Дата обновления'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency}}');
    }
}
