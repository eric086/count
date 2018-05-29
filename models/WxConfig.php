<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_flex_uc_v2_wx_config".
 *
 * @property string $id
 * @property string $aes_key
 * @property string $app_id
 * @property string $app_secret
 * @property string $create_at
 * @property string $name
 * @property string $source_id
 * @property string $token
 */
class WxConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 't_flex_uc_v2_wx_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aes_key', 'app_id', 'app_secret', 'name', 'source_id', 'token'], 'required'],
            [['create_at'], 'safe'],
            [['aes_key', 'app_secret'], 'string', 'max' => 128],
            [['app_id', 'name', 'source_id', 'token'], 'string', 'max' => 32],
            [['app_id'], 'unique'],
            [['source_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'aes_key' => 'Aes Key',
            'app_id' => 'App ID',
            'app_secret' => 'App Secret',
            'create_at' => 'Create At',
            'name' => 'Name',
            'source_id' => 'Source ID',
            'token' => 'Token',
        ];
    }
}
