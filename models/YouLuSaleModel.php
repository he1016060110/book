<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "youlu_sale".
 *
 * @property int $id
 * @property int $book_id
 * @property int $stock_num
 */
class YouLuSaleModel extends \yii\db\ActiveRecord
{
    private static $_date;
    /**
     * @inheritdoc
     */
    public static function tableName($date = null)
    {
        self::$_date = self::$_date ?:date("Ymd");
        return "youlu_sale_" . self::$_date;
    }

    public static function setTableNameByDate($date)
    {
        self::$_date = $date;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['book_id'], 'required'],
            [['book_id', 'stock_num'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'stock_num' => 'Stock Num',
        ];
    }
}
