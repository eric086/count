<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "day_log".
 *
 * @property string $id
 * @property string $source_id 公众号标识
 * @property string $day 日期
 * @property int $new_user_all 新增用户
 * @property int $new_user_0 其他合计
 * @property int $new_user_1 公众号搜索
 * @property int $new_user_17 名片分享
 * @property int $new_user_30 扫描二维码
 * @property int $new_user_43 图文页右上角菜单
 * @property int $new_user_51 支付后关注
 * @property int $new_user_57 图文页内公众号名称
 * @property int $new_user_75 公众号文章广告
 * @property int $new_user_78 朋友圈广告
 * @property int $cancel_user 取关用户
 * @property int $session_user 对话用户
 * @property int $cumulate_user 总关注用户
 * @property int $all_user 关注过的总用户
 * @property int $active_user_24 24小时活跃用户
 * @property int $active_user_48 48小时活跃用户
 * @property int $retained_user 留存用户
 * @property string $new_user_open_ids
 * @property string $cancel_user_open_ids
 * @property string $retained_user_percent
 * @property string $new_user_by_table
 * @property string $cancel_user_by_table
 */
class DayLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'day_log';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source_id', 'day', 'new_user_all', 'new_user_0', 'new_user_1', 'new_user_17', 'new_user_30', 'new_user_43', 'new_user_51', 'new_user_57', 'new_user_75', 'new_user_78', 'cancel_user', 'session_user', 'cumulate_user', 'all_user', 'active_user_24', 'active_user_48'], 'required'],
            [['day'], 'safe'],
            [['new_user_all', 'new_user_0', 'new_user_1', 'new_user_17', 'new_user_30', 'new_user_43', 'new_user_51', 'new_user_57', 'new_user_75', 'new_user_78', 'cancel_user', 'session_user', 'cumulate_user', 'all_user', 'active_user_24', 'active_user_48','new_user_by_table','cancel_user_by_table'], 'integer'],
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
            'day' => 'Day',
            'new_user_all' => 'New User All',
            'new_user_0' => 'New User 0',
            'new_user_1' => 'New User 1',
            'new_user_17' => 'New User 17',
            'new_user_30' => 'New User 30',
            'new_user_43' => 'New User 43',
            'new_user_51' => 'New User 51',
            'new_user_57' => 'New User 57',
            'new_user_75' => 'New User 75',
            'new_user_78' => 'New User 78',
            'cancel_user' => 'Cancel User',
            'session_user' => 'Session User',
            'cumulate_user' => 'Cumulate User',
            'all_user' => 'All User',
            'active_user_24' => 'Active User 24',
            'active_user_48' => 'Active User 48',
            'retained_user' => 'Retained User',
            'new_user_open_ids' => 'New User Open Ids',
            'cancel_user_open_ids' => 'Cancel User Open Ids',
            'retained_user_percent' => 'Retained User Percent',
            'new_user_by_table' => 'New User By Table',
            'cancel_user_by_table' => 'Cancel User By Table',
        ];
    }
}
