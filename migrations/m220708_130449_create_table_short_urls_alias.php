<?php

use yii\db\Migration;

/**
 * Class m220708_130449_create_table_short_urls_alias
 */
class m220708_130449_create_table_short_urls_alias extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('short_urls_alias', [
            'id' => $this->primaryKey()->notNull()->defaultValue("nextval('complectations_id_seq'::regclass)")->comment('ID'),
            'hash' => $this->string()->notNull()->comment('Короткий хеш код ссылки'),
            'link' => $this->string()->notNull()->comment('Ссылка'),
        ]);

        $this->addCommentOnTable('short_urls_alias', 'Справочник соответствия коротких кодов и ссылок');

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('short_urls_alias');

        return false;
    }
}
