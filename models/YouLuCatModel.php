<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "youlu_cat".
 *
 * @property int $id
 * @property string $url
 * @property int $num
 * @property string $big_cat_name 大分类
 * @property string $name 名称
 * @property int $created_at
 * @property int $updated_at
 */
class YouLuCatModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'youlu_cat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['url'], 'string', 'max' => 256],
            [['big_cat_name', 'name'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'num' => 'Num',
            'big_cat_name' => 'Big Cat Name',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
