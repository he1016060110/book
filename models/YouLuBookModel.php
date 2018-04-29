<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "youlu_book".
 *
 * @property int $id
 * @property int $cat_id
 * @property string $url
 * @property string $name
 * @property string $isbn
 * @property string $desc
 * @property string $title
 * @property string $author
 * @property int $created_at
 * @property int $updated_at
 */
class YouLuBookModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'youlu_book';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id', 'created_at', 'updated_at'], 'integer'],
            [['url', 'name', 'title'], 'string', 'max' => 256],
            [['isbn'], 'string', 'max' => 18],
            [['desc'], 'string', 'max' => 512],
            [['author'], 'string', 'max' => 128],
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
            'name' => 'Name',
            'isbn' => 'Isbn',
            'desc' => 'Desc',
            'title' => 'Title',
            'author' => 'Author',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
