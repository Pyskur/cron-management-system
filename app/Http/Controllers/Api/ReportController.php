<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Permissions\Permission;
use Illuminate\Support\Facades\Auth;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

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
                    Mail::to($request->user())->queue($subject + '\r\n' + $message);
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
                    Mail::to($request->user())->queue($subject + '\r\n' + $message);
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
                    Mail::to($request->user())->queue($subject + '\r\n' + $message);
                }
            }
        }
    }

    public function generate_invoice()
    {
        $client = new Party([
            'name'          => 'Roosevelt Lloyd',
            'phone'         => '(520) 318-9486',
            'custom_fields' => [
                'note'        => 'IDDQD',
                'business id' => '365#GG',
            ],
        ]);

        $customer = new Party([
            'name'          => 'Ashley Medina',
            'address'       => 'The Green Street 12',
            'code'          => '#22663214',
            'custom_fields' => [
                'order number' => '> 654321 <',
            ],
        ]);

        $items = [
            (new InvoiceItem())
                ->title('Service 1')
                ->description('Your product or service description')
                ->pricePerUnit(47.79)
                ->quantity(2)
                ->discount(10),
            (new InvoiceItem())->title('Service 2')->pricePerUnit(71.96)->quantity(2),
            (new InvoiceItem())->title('Service 3')->pricePerUnit(4.56),
            (new InvoiceItem())->title('Service 4')->pricePerUnit(87.51)->quantity(7)->discount(4)->units('kg'),
            (new InvoiceItem())->title('Service 5')->pricePerUnit(71.09)->quantity(7)->discountByPercent(9),
            (new InvoiceItem())->title('Service 6')->pricePerUnit(76.32)->quantity(9),
            (new InvoiceItem())->title('Service 7')->pricePerUnit(58.18)->quantity(3)->discount(3),
            (new InvoiceItem())->title('Service 8')->pricePerUnit(42.99)->quantity(4)->discountByPercent(3),
            (new InvoiceItem())->title('Service 9')->pricePerUnit(33.24)->quantity(6)->units('m2'),
            (new InvoiceItem())->title('Service 11')->pricePerUnit(97.45)->quantity(2),
            (new InvoiceItem())->title('Service 12')->pricePerUnit(92.82),
            (new InvoiceItem())->title('Service 13')->pricePerUnit(12.98),
            (new InvoiceItem())->title('Service 14')->pricePerUnit(160)->units('hours'),
            (new InvoiceItem())->title('Service 15')->pricePerUnit(62.21)->discountByPercent(5),
            (new InvoiceItem())->title('Service 16')->pricePerUnit(2.80),
            (new InvoiceItem())->title('Service 17')->pricePerUnit(56.21),
            (new InvoiceItem())->title('Service 18')->pricePerUnit(66.81)->discountByPercent(8),
            (new InvoiceItem())->title('Service 19')->pricePerUnit(76.37),
            (new InvoiceItem())->title('Service 20')->pricePerUnit(55.80),
        ];

        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make('receipt')
            ->series('BIG')
            // ability to include translated invoice status
            // in case it was paid
            ->status(__('invoices::invoice.paid'))
            ->sequence(667)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date(now()->subWeeks(3))
            ->dateFormat('m/d/Y')
            ->payUntilDays(14)
            ->currencySymbol('$')
            ->currencyCode('USD')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            ->notes($notes)
            // You can additionally save generated invoice to configured disk
            ->save('public');

        $link = $invoice->url();
        // Then send email to party with link

        // And return invoice itself to browser or have a different view
        return $invoice->stream();
    }
}
