<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    /**
     * Returns daily report.
     *
     * @return \Illuminate\Http\Response
     */
    public function daily_report()
    {
        $user_list = DB::table('users')
                        ->join('notifications', 'users.id', '=', 'notifications.user_id')
                        ->where('users.active', '=', 1)
                        ->where('notifications.status', '=', 1)
                        ->select('users.id', 'users.phone', 'users.email')
                        ->get();
        if(is_array($user_list) && count($user_list) > 0) {
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
            foreach ($user_list as $key => $value) {
                $reports = DB::table('sms')
                                ->where('user_id', '=', $value['id'])
                                ->whereBetween('date_added', [$start_date, $end_date])
                                ->get();
                if(is_array($reports) && count($reports)) {
                    $message_count = count($reports);
                    $sum = 0.0;
                    foreach ($reports as $k => $v){
                        $sum = $sum + $v['charge'];
                    }
                    $to = $value['email'];
                    $subject = 'Daily report on messages sent / money spent';
                    $message = "Today's there is ". $message_count ." message sent and total cost for all the message is £". round($sum,2) ."";
                    Mail::to($request->user())->send($subject + '\r\n' + $message);
                }
            }
        }
    }

    public function monthly_report()
    {
        $user_list = DB::table('users')
                        ->join('notifications', 'users.id', '=', 'notifications.user_id')
                        ->where('users.active', '=', 1)
                        ->where('notifications.status', '=', 1)
                        ->select('users.id', 'users.phone', 'users.email')
                        ->get();
        if(is_array($user_list) && count($user_list) > 0) {
            $end_date = date('Y-m-d 23:59:59');
            $start_date = date("Y-m-d 00:00:00", strtotime($end_date . " -30 day"));
            foreach ($user_list as $key => $value) {
                $reports = DB::table('sms')
                                ->where('user_id', '=', $value['id'])
                                ->whereBetween('date_added', [$start_date, $end_date])
                                ->get();
                if(is_array($reports) && count($reports)) {
                    $message_count = count($reports);
                    $sum = 0.0;
                    foreach ($reports as $k => $v){
                        $sum = $sum + $v['charge'];
                    }
                    $to = $value['email'];
                    $subject = 'Monthly report on messages sent / money spent';
                    $message = "Monthly there is ". $message_count ." message sent and total cost for all the message is £". round($sum,2) ."";
                    Mail::to($request->user())->send($subject + '\r\n' + $message);
                }
            }
        }
    }

    public function weekly_report()
    {
        $user_list = DB::table('users')
                        ->join('notifications', 'users.id', '=', 'notifications.user_id')
                        ->where('users.active', '=', 1)
                        ->where('notifications.status', '=', 1)
                        ->select('users.id', 'users.phone', 'users.email')
                        ->get();
        if(is_array($user_list) && count($user_list) > 0) {
            $date = date("Y-m-d");
            $sel_day = date("D", strtotime($date));
            if ($sel_day == "Mon") {
                $sun_textual = "monday " . $date;
            } else {
                $sun_textual = "last monday " . $date;
            }
            $sun_date = date("Y-m-d", strtotime($sun_textual));
            $start_timestamp = date(strtotime($sun_date));
            $end_timestamp = date($start_timestamp + 518400);
            $start_date = date('Y-m-d 00:00:00', $start_timestamp);
            $end_date = date('Y-m-d 23:59:59', $end_timestamp);
            foreach ($user_list as $key => $value) {
                $reports = DB::table('sms')
                                ->where('user_id', '=', $value['id'])
                                ->whereBetween('date_added', [$start_date, $end_date])
                                ->get();
                if(is_array($reports) && count($reports)) {
                    $message_count = count($reports);
                    $sum = 0.0;
                    foreach ($reports as $k => $v){
                        $sum = $sum + $v['charge'];
                    }
                    $to = $value['email'];
                    $subject = 'Weekly report on messages sent / money spent';
                    $message = "Weekly there is ". $message_count ." message sent and total cost for all the message is £". round($sum,2) ."";
                    Mail::to($request->user())->send($subject + '\r\n' + $message);
                }
            }
        }
    }
}
