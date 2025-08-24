<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency_rate}}`.
 */
class m250823_081830_create_currency_rate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currency_rate}}', [
            'currency_from_id' => $this->integer()->notNull()->comment('Исходная валюта'),
            'currency_to_id' => $this->integer()->notNull()->comment('Целевая валюта'),
            'rate' => $this->decimal(18, 6)->notNull()->comment('Курс'),
            'last_updated_at' => $this->timestamp()->notNull()->comment('Актуальная дата курса'),
        ]);

        $this->addPrimaryKey(
            'pk-currency_rate',
            '{{%currency_rate}}',
            ['currency_from_id', 'currency_to_id']
        );

        $this->addForeignKey(
            'fk-currency_rate-currency_from_id-currency-id',
            '{{%currency_rate}}',
            'currency_from_id',
            '{{%currency}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-currency_rate-currency_to_id-currency-id',
            '{{%currency_rate}}',
            'currency_to_id',
            '{{%currency}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createIndex(
            'idx-currency_rate-currency_to_id-currency_from_id',
            '{{%currency_rate}}',
            ['currency_to_id', 'currency_from_id'],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency_rate}}');
    }
}
