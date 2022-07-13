<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "short_urls_alias".
 *
 * @property int $id
 * @property string $short_url
 * @property string $url
 */
class ShortUrlsAlias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'short_urls_alias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hash', 'link'], 'required'],

            [['link'], 'url', 'defaultScheme' => 'http'],
            [['hash'], 'string', 'max' => 255],
            [['hash'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hash' => 'Hash Url',
            'link' => 'Link',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ShortUrlsAliasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShortUrlsAliasQuery(get_called_class());
    }

    public function getShort() {
        return 'http://' . $_SERVER['SERVER_NAME'] . '/' . $this->hash;
    }

    public function getHash() {
        return $this->hash;
    }


    public function getId() {
        return $this->id;
    }



    public function getLink() {
        return $this->link;
    }

    public static function createShort($link)
    {
        $newHash = '';
        $byLinkId = null;

        $byLink = ShortUrlsAlias::byLink($link);

        if(!empty($byLink)) {
            $byLinkId = $byLink->getId();
        }

        if(empty($byLinkId)){
            do {
                $newHash = self::genRandString();
                $exist = !self::byHash($newHash);
                // $exist - true совпадений не найдено, $newHash уникальный
            } while ($exist === false);
        } else {
            return $byLink;
        }

        $model = new ShortUrlsAlias();
        $model->link = $link;
        $model->hash = $newHash;

        $validate = $model->validate();
        if($validate){
            $model->save();
        } else {
            var_dump($validate);
            return false;
        }

        return $model;
    }

    public static function byLink($link)
    {
        return ShortUrlsAlias::find()->where(['like', 'link', $link])->one();
    }

    public static function byHash($hash)
    {
        $model = ShortUrlsAlias::find()->where('hash = :hash', ['hash' => $hash])->one();
        return $model;
    }

    private static function genRandString($length = 5)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = strlen($characters);
        $randString = '';
        for ($i = 0; $i < $length; $i++) {
            $randString .= $characters[rand(0, $len - 1)];
        }
        return $randString;
    }


    public function shortHashToUrl($hash)
    {
        $shortUrlsAliasByHash = ShortUrlsAlias::byHash($hash);
        $link = $shortUrlsAliasByHash->getLink();
        return $link;
    }


}
