<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "week_log".
 *
 * @property int $id
 * @property string $source_id 公众号标识
 * @property string $start_day 开始日期
 * @property string $end_day 结束日期
 * @property int $new_user 新增用户
 * @property int $cancel_user 取关用户
 * @property int $retained_user 留存用户
 * @property string $new_user_open_ids 周关注open_id集合
 * @property string $cancel_user_open_ids 周取关open_id集合
 * @property string $retained_user_percent
 */
class WeekLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'week_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source_id', 'start_day', 'end_day', 'new_user', 'cancel_user', 'retained_user', 'new_user_open_ids', 'cancel_user_open_ids', 'retained_user_percent'], 'required'],
            [['start_day', 'end_day'], 'safe'],
            [['new_user', 'cancel_user', 'retained_user'], 'integer'],
            [['new_user_open_ids', 'cancel_user_open_ids'], 'string'],
            [['retained_user_percent'], 'number'],
            [['source_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'source_id' => 'Source ID',
            'start_day' => 'Start Day',
            'end_day' => 'End Day',
            'new_user' => 'New User',
            'cancel_user' => 'Cancel User',
            'retained_user' => 'Retained User',
            'new_user_open_ids' => 'New User Open Ids',
            'cancel_user_open_ids' => 'Cancel User Open Ids',
            'retained_user_percent' => 'Retained User Percent',
        ];
    }
}
