<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "t_flex_uc_v2_user_wx_info".
 *
 * @property string $id
 * @property string $create_at
 * @property string $open_id
 * @property string $source_id
 * @property bool $subscribe
 * @property string $subscribe_time
 * @property string $user_id
 * @property string $subscribe_channel
 * @property string $remark
 * @property string $active_time
 * @property string $location
 * @property string $nickname
 * @property int $sex
 *
 * @property TFlexUcV2UserInfo $user
 * @property TFlexUcV2UserWxTags[] $tFlexUcV2UserWxTags
 */
class UserWxInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 't_flex_uc_v2_user_wx_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_at', 'open_id', 'source_id', 'user_id'], 'required'],
            [['create_at', 'subscribe_time', 'active_time'], 'safe'],
            [['subscribe'], 'boolean'],
            [['sex'], 'integer'],
            [['open_id', 'user_id', 'subscribe_channel', 'remark'], 'string', 'max' => 64],
            [['source_id'], 'string', 'max' => 32],
            [['location'], 'string', 'max' => 255],
            [['nickname'], 'string', 'max' => 128],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => TFlexUcV2UserInfo::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_at' => 'Create At',
            'open_id' => 'Open ID',
            'source_id' => 'Source ID',
            'subscribe' => 'Subscribe',
            'subscribe_time' => 'Subscribe Time',
            'user_id' => 'User ID',
            'subscribe_channel' => 'Subscribe Channel',
            'remark' => 'Remark',
            'active_time' => 'Active Time',
            'location' => 'Location',
            'nickname' => 'Nickname',
            'sex' => 'Sex',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(TFlexUcV2UserInfo::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTFlexUcV2UserWxTags()
    {
        return $this->hasMany(TFlexUcV2UserWxTags::className(), ['user_wx_id' => 'id']);
    }
}
