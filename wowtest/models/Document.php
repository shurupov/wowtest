<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property string $id
 * @property string $name
 * @property integer $pages_count
 * @property integer $created
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'pages_count', 'created'], 'required'],
            [['pages_count', 'created'], 'integer'],
            [['id'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'pages_count' => 'Pages Count',
            'created' => 'Created',
        ];
    }
}
