<?php

use yii\db\Migration;

/**
 * Class m220708_130812_create_table_urls_list
 */
class m220708_130812_create_table_urls_list extends Migration
{
    public function up()
    {
        $this->createTable('urls_list', [
            'id' => $this->primaryKey()->notNull()->defaultValue("nextval('complectations_id_seq'::regclass)")->comment('ID'),
            'link' => $this->string()->notNull()->comment('Ссылка'),
            'date_year_month' => $this->string()->notNull()->comment('Дата год и месяц перехода'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
        ]);

        $this->addCommentOnTable('urls_list', 'Справочник соответствия коротких кодов и ссылок');

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('urls_list');

        return false;
    }
}
