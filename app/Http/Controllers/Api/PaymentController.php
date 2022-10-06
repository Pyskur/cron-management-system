<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class PaymentController extends Controller
{
    public function auto_renew_user_payment($user_id) {
        $user_data = User::where('id', $user_id)->first();
        // return $user_data->auto_renew->auto_renew_min_amt;
        // if (is_array($user_data) && count($user_data) > 0) {
          if ($user_data->balance > $user_data->auto_renew->auto_renew_min_amt) {
            $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
            \Stripe\Stripe::setMaxNetworkRetries(2);
            $metadata['line1'] = '';
            $metadata['city'] = '';
            $metadata['country'] = '';
    
            $user_metadata = array();
            $user_metadata['first_name'] = $user_data->first_name;
            $user_metadata['last_name'] = $user_data->last_name;
            $user_metadata['address'] = '';
            $user_metadata['address2'] = '';
            $user_metadata['city'] = '';
            $user_metadata['country'] = '';
            try {
              $charge = $stripe->charges->create([
                'customer' => $user_data->stripe_cust_id,
                'amount' => $user_data->auto_renew->auto_renew_amt * 100,
                'currency' => 'gbp',
                'description' => 'Auto renew aupdate',
                'receipt_email' => $user_data->email,
                'statement_descriptor' => 'Auto renew aupdate',
              ]);
              $result['charge'] = $charge;
              $result['type'] = "success";
            }
            // Handling Stripe api errors in Try catch block.
            catch (\Stripe\Error\Card $e) {
              $result['type'] = $this->getStripeErrorMessage($e);
            }
            catch (\Stripe\Error\InvalidRequest $e) {
              $result['type'] = $this->getStripeErrorMessage($e);
            }
            catch (\Stripe\Error\Authentication $e) {
              $result['type'] = $this->getStripeErrorMessage($e);
            }
            catch (\Stripe\Error\ApiConnection $e) {
              $result['type'] = $this->getStripeErrorMessage($e);
            }
            catch (\Stripe\Error\Base $e) {
              $result['type'] = $this->getStripeErrorMessage($e);
            }
            catch (Exception $e) {
              $result['type'] = $this->getStripeErrorMessage($e);
            }
    
            if ($result['type'] !='success') {
              echo "Error";
              exit;
            }
            else {
              $data = [];
              $data['user_id'] = $user_data->id;
              $data['uniqueref'] = $result['charge']->id;
              $data['client_id'] = $user_data->stripe_cust_id;
              $data['amount'] = $user_data->auto_renew->auto_renew_amt;
              $data['payment_method'] = $result['charge']->payment_method_details['type'];
              $data['card_type'] = $result['charge']->payment_method_details['card']->brand;
              $data['card_digit'] = $result['charge']->payment_method_details['card']->last4;
              $data['cvv'] = $user_data->cvv;

              // $insert_id = $this->insert_model->receipt($data);
    
              // User Data Update
              $total_balance = $user_data->balance + $user_data->auto_renew->auto_renew_amt;
              $balance_update = number_format(round((float) $total_balance, 2), 2);
              $update_array = array(
                'balance' => $balance_update
              );
              User::where('id', $user_data->id)->update($update_array);
              
            //   send mail to user
            //   $subject='Balance update successfully.';
            //   $msg_text= 'Balance update successfully. Your current balance is ' . $balance_update;
            //   $rdata=$this->select_model->send_mail_to_user($user_data['email'],$subject,$user_data,'',$msg_text);
            }
            echo "done";
            exit;
          }
          else {
            echo "Minimum balance available";
            exit;
          }
        // }
    }

    public function test()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
        // return $stripe->balance->retrieve();
        $stripe->customers->createSource(
            'cus_MYtwirDZebxJ4A',
            ['source' => 'tok_1LpuUBDSwfRjFIm0gqS2oNg8']
        );          
    }

    public function temp()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
        // return $stripe->paymentMethods->all([
        //     'customer' => 'cus_MWdeTyUEEYqeOT',
        //     'type' => 'card',
        // ]);

        // return $stripe->paymentMethods->create([
        //     'type' => 'card',
        //     'card' => [
        //       'number' => '4242424242424242',
        //       'exp_month' => 10,
        //       'exp_year' => 2023,
        //       'cvc' => '314',
        //     ],
        // ]); // pm_1LpWJUDSwfRjFIm0HsAxm1e9

        // return $stripe->paymentMethods->attach(
        //     'pm_1LpWJUDSwfRjFIm0HsAxm1e9',
        //     ['customer' => 'cus_MWdeTyUEEYqeOT']
        // );

        // return $stripe->charges->create([
        //     'amount' => 2000,
        //     'currency' => 'usd',
        //     'source' => 'tok_visa',
        //     'description' => 'My First Test Charge (created for API docs at https://www.stripe.com/docs/api)',
        // ]);
        // return $stripe->products->create([
        //     'name' => 'Gold Special',
        // ]);
        // return $stripe->prices->create([
        //     'unit_amount' => 1000,
        //     'currency' => 'usd',
        //     'product' => 'prod_MYeW1Kx61pIrlM',
        // ]);
        // return $stripe->invoiceItems->create([
        //     'customer' => 'cus_MWdeTyUEEYqeOT',
        //     'price' => 'price_1LpXEdDSwfRjFIm0ILK64VST',
        // ]);
        return $stripe->invoices->create([
            'customer' => 'cus_MWdeTyUEEYqeOT',
        ]);
    }

    public function pay()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
        // return $stripe->customers->retrieve(
        //     'cus_MWdeTyUEEYqeOT',
        //     []
        // );
        // return $stripe->customers->createBalanceTransaction(
        //     'cus_MWdeTyUEEYqeOT',
        //     ['amount' => -1200, 'currency' => 'usd']
        // );
        return $stripe->customers->create([
            'description' => 'My First Test Customer (created for API docs at https://www.stripe.com/docs/api)',
        ]);
    }
}
