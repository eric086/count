<?php

namespace app\models;

use Yii;
use callmez\wechat;

/**
 * This is the model class for table "t_flex_uc_v2_user_wx_event".
 *
 * @property string $id
 * @property string $location_latitude
 * @property string $location_longitude
 * @property string $location_precision
 * @property string $create_at
 * @property string $event_key
 * @property string $event_type
 * @property string $open_id
 * @property string $source_id
 * @property string $ticket
 */
class UserWxEvent extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 't_flex_uc_v2_user_wx_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'create_at', 'open_id', 'source_id'], 'required'],
            [['create_at'], 'safe'],
            [['id', 'open_id'], 'string', 'max' => 64],
            [['location_latitude', 'location_longitude', 'location_precision'], 'string', 'max' => 16],
            [['event_key', 'event_type'], 'string', 'max' => 255],
            [['source_id'], 'string', 'max' => 32],
            [['ticket'], 'string', 'max' => 128],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'location_latitude' => 'Location Latitude',
            'location_longitude' => 'Location Longitude',
            'location_precision' => 'Location Precision',
            'create_at' => 'Create At',
            'event_key' => 'Event Key',
            'event_type' => 'Event Type',
            'open_id' => 'Open ID',
            'source_id' => 'Source ID',
            'ticket' => 'Ticket',
        ];
    }
    
    //获取时间段活跃用户
    public static function getActiveUser($source_id = '', $start = '', $end = '', $hour = 24) {
        $start = $start ? $start : date('Y-m-d', strtotime('-1 day'));
        $end = $end ? $end :$start;
        if ($hour == 24) {
            $start = date('Y-m-d', strtotime($start) - 24 * 3600) . ' 00:00:00';
        } elseif ($hour == 48) {
            $start = date('Y-m-d', strtotime($start) - 48 * 3600) . ' 00:00:00';
        } else {
            $start = $date . ' 00:00:00';
        }
        $end = $end . ' 23:59:59';

        $data = static::getTimeUser($source_id, $start, $end);
        $open_ids = $data['subscribe_open_ids'];
        return static::find()
                        ->select('create_at,event_type,source_id,open_id')
                        ->where(['not in', 'event_type', ['subscribe', 'unsubscribe', 'MASSSENDJOBFINISH']])
                        ->andWhere(['between', 'create_at', $start, $end])
                        ->andWhere(['in', 'open_id', $open_ids])
                        ->groupBy('open_id')
                        ->count();
    }
    
    //获取一天的用户数据
    public static function getDayUser($source_id = '', $date = '') {
        $date = $date ? $date : date('Y-m-d', strtotime('-1 day'));
        $key = $source_id .$date;
        $day = Yii::$app->cache->get($key);
        //$day = DayLog::find()->where(['source_id' => $source_id, 'day' => $date])->asArray()->one();
        if ($day) {
            return $day;
        }

        $wechat = self::getWechat($source_id);
        if (!$wechat) {
            return -1;
        }

        $new_user[0] = 0;
        $new_user[1] = 0;
        $new_user[17] = 0;
        $new_user[30] = 0;
        $new_user[43] = 0;
        $new_user[51] = 0;
        $new_user[57] = 0;
        $new_user[75] = 0;
        $new_user[78] = 0;
        $new_user_all = 0;
        $cancel_user = 0;
        $session_user = 0;
        $active_user_24 = self::getActiveUser($source_id, $date, 24);
        $active_user_48 = self::getActiveUser($source_id, $date, 48);
        $cumulate_user = 0;
        $all_user = UserWxInfo::find()->count();

        $cumulate_list = $wechat->getDataCube()->getUserCumulate(['begin_date' => $date, 'end_date' => $date]);

        if ($cumulate_list) {
            $cumulate_user = $cumulate_list[0]['cumulate_user'];
        }

        $user_list = $wechat->getDataCube()->getUserSummary(['begin_date' => $date, 'end_date' => $date]);
        if ($user_list) {
            foreach ($user_list as $v) {
                $cancel_user += $v['cancel_user'];
                if ($v['new_user']) {
                    $new_user[$v['user_source']] += $v['new_user'];
                }
            }
            $new_user_all = array_sum($new_user);
        }

        $msg_list = $wechat->getDataCube()->getUpStreamMessage(['begin_date' => $date, 'end_date' => $date]);
        if ($msg_list) {
            foreach ($msg_list as $v) {
                $session_user += $v['msg_user'];
            }
        }
        
        $start = $date;
        $end = $date;

        $last_start = date('Y-m-d', strtotime($start) - 24 * 3600);
        $last_end = date('Y-m-d', strtotime($start) - 24 * 3600);


        $last_users_all = self::getTimeUser($source_id, $last_start, $last_end . ' 23:59:59');
        $last_users_new = $last_users_all['subscribe_open_ids'];
        $last_new_user_num = count($last_users_new);

        $users = self::getTimeUser($source_id, $start, $end . ' 23:59:59');

        $retained_user = $last_new_user_num - count(array_intersect($users['unsubscribe_open_ids'], $last_users_new));

        $day = new DayLog();
        $day->day = $date;
        $day->new_user_0 = $new_user[0];
        $day->new_user_1 = $new_user[1];
        $day->new_user_17 = $new_user[17];
        $day->new_user_30 = $new_user[30];
        $day->new_user_43 = $new_user[43];
        $day->new_user_51 = $new_user[51];
        $day->new_user_57 = $new_user[57];
        $day->new_user_75 = $new_user[75];
        $day->new_user_78 = $new_user[78];
        $day->new_user_all = $new_user_all;
        $day->cancel_user = $cancel_user;
        $day->active_user_24 = $active_user_24;
        $day->active_user_48 = $active_user_48;
        $day->cumulate_user = $cumulate_user;
        $day->all_user = $all_user;
        $day->session_user = $session_user;
        $day->source_id = $source_id;
        $day->new_user_by_table = count($users['subscribe_open_ids']);
        $day->cancel_user_by_table = count($users['unsubscribe_open_ids']);
        $day->retained_user = $retained_user;
        $day->retained_user_percent = $last_new_user_num ? bcdiv($retained_user, $last_new_user_num,4) * 100 : 0;
        if ($day->save()) {
            $data = DayLog::find()
                    ->select('`source_id`,`day`,`new_user_all`,`new_user_0`,`new_user_1`,`new_user_17`,`new_user_30`,`new_user_43`,`new_user_51`,`new_user_57`,`new_user_75`,`new_user_78`,`cancel_user`,`session_user`,`cumulate_user`,`all_user`,`active_user_24`,`active_user_48`,`retained_user`,`retained_user_percent`')
                    ->where(['source_id' => $source_id, 'day' => $date])
                    ->asArray()
                    ->one();
            Yii::$app->cache->set($key,$data);
            return $data;
        }
    }

    //时间段关注和取关的用户
    public static function getTimeUser($source_id = '', $start_time, $end_time) {
        $data = static::find()
                ->select('create_at,event_type,source_id,open_id')
                ->where(['in', 'event_type', ['subscribe', 'unsubscribe']])
                ->andWhere(['between', 'create_at', $start_time, $end_time])
                ->asArray()
                ->orderBy('create_at asc')
                ->all();
               //->createCommand()->getRawSql();echo $data;exit;
        $rs = [];
        foreach ($data as $v) {
            $rs[$v['open_id']] = $v;
        }
        
        $users['subscribe_open_ids'] = [];
        $users['unsubscribe_open_ids'] = [];
        foreach ($rs as $k => $v) {
            if ($v['event_type'] == 'subscribe') {
                $users['subscribe_open_ids'][] = $k;
            }elseif ($v['event_type'] == 'unsubscribe') {
                $users['unsubscribe_open_ids'][] = $k;
            }
        }
        
        return $users;
    }
    
    //周留存用户
    public static function getWeekUser($source_id,$date = ''){
        $date = $date ? $date : date('Y-m-d');
        $start = \app\extensions\Date::getLastWeekDays($date)[0];
        $end = \app\extensions\Date::getLastWeekDays($date)[1];
        
        $last_start =  \app\extensions\Date::getLastWeekDays($start)[0];
        $last_end =  \app\extensions\Date::getLastWeekDays($start)[1];
        
        $week = WeekLog::find()
                ->select('source_id,start_day,end_day,new_user,cancel_user,retained_user,retained_user_percent')
                ->where(['source_id' => $source_id, 'start_day' => $start,'end_day' => $end])
                ->asArray()
                ->one();
        if ($week) {
            return $week;
        }
        
       
        $last_users_all = self::getTimeUser($source_id, $last_start, $last_end . ' 23:59:59');
        $last_users_new = $last_users_all['subscribe_open_ids'];
        $last_new_user_num = count($last_users_new);

        $users = self::getTimeUser($source_id,$start,$end . ' 23:59:59');
        
        $retained_user = $last_new_user_num - count(array_intersect($users['unsubscribe_open_ids'], $last_users_new));

        $week = new WeekLog();
        $week->source_id = $source_id;
        $week->start_day = $start;
        $week->end_day = $end;
        $week->new_user = count($users['subscribe_open_ids']);
        $week->cancel_user = count($users['unsubscribe_open_ids']);;
        $week->retained_user = $retained_user;
        $week->retained_user_percent = $last_new_user_num ? bcdiv($retained_user, $last_new_user_num,4) * 100 : 0;
        
         if ($week->save()) {
            return WeekLog::find()
                    ->select('source_id,start_day,end_day,new_user,cancel_user,retained_user,retained_user_percent')
                    ->where(['source_id' => $source_id, 'start_day' => $start,'end_day' => $end])
                    ->asArray()
                    ->one();
        }
    }
    
     //月留存用户
    public static function getMonthUser($source_id,$date = ''){
        $date = $date ? $date : date('Y-m-d');
        $start = \app\extensions\Date::getlastMonthDays($date)[0];
        $end = \app\extensions\Date::getlastMonthDays($date)[1];
        
        $last_start =  \app\extensions\Date::getlastMonthDays($start)[0];
        $last_end =  \app\extensions\Date::getlastMonthDays($start)[1];
        
        $month = MonthLog::find()
                ->select('source_id,start_day,end_day,new_user,cancel_user,retained_user,retained_user_percent')
                ->where(['source_id' => $source_id, 'start_day' => $start,'end_day' => $end])
                ->asArray()
                ->one();
        if ($month) {
            return $month;
        }
        
        $users = self::getTimeUser($source_id,$start,$end . ' 23:59:59');
        
        $last_month = MonthLog::find()
                ->where(['source_id' => $source_id, 'start_day' => $last_start,'end_day' => $last_end])
                ->asArray()
                ->one();
       
        $last_users_all = self::getTimeUser($source_id, $last_start, $last_end . ' 23:59:59');
        $last_users_new = $last_users_all['subscribe_open_ids'];
        $last_new_user_num = count($last_users_new);

        $retained_user = $last_new_user_num - count(array_intersect($users['unsubscribe_open_ids'], $last_users_new));
        
        
        $month = new MonthLog();
        $month->source_id = $source_id;
        $month->start_day = $start;
        $month->end_day = $end;
        $month->new_user = count($users['subscribe_open_ids']);
        $month->cancel_user = count($users['unsubscribe_open_ids']);
        $month->retained_user = $retained_user;
        $month->retained_user_percent = $last_new_user_num ? bcdiv($retained_user, $last_new_user_num,4) * 100 : 0;
        
         if ($month->save()) {
            return MonthLog::find()
                    ->select('source_id,start_day,end_day,new_user,cancel_user,retained_user,retained_user_percent')
                    ->where(['source_id' => $source_id, 'start_day' => $start,'end_day' => $end])
                    ->asArray()
                    ->one();
        }else{
            print_r($month->getErrors());
        }
    }
    


    

    public static function getWechat($source_id) {
        $wechat_config = WxConfig::find()->where(['source_id' => $source_id])->one();

        if (!$wechat_config) {
            return false;
        }

        return Yii::createObject([
                    'class' => 'callmez\wechat\sdk\MpWechat',
                    'appId' => $wechat_config->app_id,
                    'appSecret' => $wechat_config->app_secret,
                    'token' => $wechat_config->token,
                    'encodingAesKey' => ''
        ]);
    }

}
