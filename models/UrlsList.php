<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "urls_list".
 *
 * @property int $id
 * @property string $link
 * @property string $date_year_month
 * @property string $created_at
 */
class UrlsList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'urls_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link', 'date_year_month'], 'required'],
            [['link'], 'string', 'min' => 2, 'max' => 255],
            ['link', 'url', 'pattern'=>'/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(?::\d{1,5})?(?:$|[?\/#])/i', 'message' => 'неверный формат url'],
            [['date_year_month', 'created_at'], 'safe'],
            [['link'], 'string', 'max' => 255],
        ];
    }

    public function validateUrl($attribute, $params)
    {
        if (filter_var($this->$attribute, FILTER_VALIDATE_URL) === false) {
            $this->addError($attribute, 'Ошибка валидации формата url');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Url',
            'date_year_month' => 'Date Year Month',
            'created_at' => 'Created At',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * {@inheritdoc}
     * @return UrlsListQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UrlsListQuery(get_called_class());
    }
}
