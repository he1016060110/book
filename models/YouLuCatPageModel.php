<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "youlu_cat_page".
 *
 * @property int $id
 * @property int $cat_id
 * @property string $url
 * @property int $created_at
 * @property int $updated_at
 */
class YouLuCatPageModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'youlu_cat_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'created_at', 'updated_at'], 'integer'],
            [['url'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_id' => 'Cat ID',
            'url' => 'Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
