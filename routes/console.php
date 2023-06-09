<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Configure;
use App\Models\Group;
use App\Models\GeoGroup;
use App\Models\AwsCloudfrontDistribution;
use App\Models\Country;
use App\Models\Billing;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('db:setup', function () {
    $setupDone = Configure::where('name', 'databaseSetupDone')->first();
    if($setupDone != null && $setupDone->enabled == 1){
        $this->comment("Already Set up");
    }
    else{
        //Add Groups
        $general_permissions = [
            "video-store",
            "video-update",
            "video-show",
            "video-destroy",
            "video-upload",
            "video-status",
            "video-thumbnails",
            "video-by-path",
            "videoplayer-store",
            "videoplayer-update",
            "videoplayer-show",
            "videoplayer-destroy",
            "user-store",
            "user-update",
            "user-show",
            "user-destroy",
            "user-update-balance",
            "user-get-companies",
            "user-get-groups",
            "user-get-balance",
            "user-set-balance",
            "autorenew-store",
            "autorenew-update",
            "autorenew-show",
            "autorenew-destroy",
            "billing-index",
            "billing-store",
            "billing-update",
            "billing-show",
            "billing-destroy",
            "configure-index",
            "configure-store",
            "configure-update",
            "configure-show",
            "configure-destroy",
            "company-store",
            "company-update",
            "company-show",
            "company-destroy",
            "company-get-users",
            "country-index",
            "country-store",
            "country-update",
            "country-show",
            "country-destroy",
            "whitelistips-index",
            "whitelistips-store",
            "whitelistips-update",
            "whitelistips-show",
            "whitelistips-destroy",
            "blacklistips-index",
            "blacklistips-store",
            "blacklistips-update",
            "blacklistips-show",
            "blacklistips-destroy",
            "cron-store",
            "cron-update",
            "cron-show",
            "cron-destroy",
            "epd-store",
            "epd-update",
            "epd-show",
            "epd-destroy",
            "group-store",
            "group-update",
            "group-show",
            "group-destroy",
            "group-get-users",
            "httpsetting-index",
            "httpsetting-store",
            "httpsetting-update",
            "httpsetting-show",
            "httpsetting-destroy",
            "key-store",
            "key-update",
            "key-show",
            "key-destroy",
            "keyscode-store",
            "keyscode-update",
            "keyscode-show",
            "keyscode-destroy",
            "keysref-store",
            "keysref-update",
            "keysref-show",
            "keysref-destroy",
            "keyssms-store",
            "keyssms-update",
            "keyssms-show",
            "keyssms-destroy",
            "limit-store",
            "limit-update",
            "limit-show",
            "limit-destroy",
            "notification-store",
            "notification-update",
            "notification-show",
            "notification-destroy",
            "order-store",
            "order-update",
            "order-show",
            "order-destroy",
            "sms-store",
            "sms-update",
            "sms-show",
            "sms-destroy",
            "sms-sendmessage",
            "codecheck-invoke",
            "forgotpassword-invoke",
            "payment-auto-renew-user-payment",
            "payment-auto-recharge-payment",
            "payment-ipn",
            "payment-addpaymentmethod",
            "payment-getmystripeprofile",
            "payment-getmystripepaymentmethods",
            "report-daily-report",
            "report-monthly-report",
            "report-weekly-report",
            "resetpassword-invoke",
            "usersnotifications-getnotifications",
            "usersnotifications-getusers",
            "usersnotifications-addusertonotification",
            "usersnotifications-deleteuserfromnotification",
            "auth-user"
        ];
        Group::create(['name'=>'developers', 'description' =>'developers', "permissions" => $general_permissions]);
        Group::create(['name'=>'administrators', 'description' =>'administrators', "permissions" => ["*"]]);
        Group::create(['name'=>'finance_staff', 'description' =>'finance staff', "permissions" => $general_permissions]);
        Group::create(['name'=>'support_agents ', 'description' =>'support agents ', "permissions" => $general_permissions]);
        Group::create(['name'=>'customers', 'description' =>'customers', "permissions" => $general_permissions]);
        Group::create(['name'=>'3rd_party_developers', 'description' =>'3rd party developers', "permissions" => $general_permissions]);
        $this->info("--Group Table Done");

        //Add Video GeoGroups
        $globalGeoGroup = GeoGroup::where('is_global', true)->first();
        if($globalGeoGroup){
            $this->comment("--There is already global GeoGroup table.");
        }
        else{
            $data = [
                'dist_id' => env('AWS_CLOUDFRONT_DISTRIBUTION_GLOBAL_ID'),
                'description' => env('AWS_CLOUDFRONT_DISTRIBUTION_GLOBAL_DESCRIPTION'),
                'domain_name' => env('AWS_CLOUDFRONT_DISTRIBUTION_GLOBAL_DOMAINNAME'),
                'alt_domain_name' => env('AWS_CLOUDFRONT_DISTRIBUTION_GLOBAL_ALTDOMAINNAME'),
                'origin' => env('AWS_CLOUDFRONT_DISTRIBUTION_GLOBAL_ORIGIN'),
            ]; 
            $newAwsCloudfrontDistribution = AwsCloudfrontDistribution::create($data);

            $newGeoGroup = GeoGroup::create([
                'is_blacklist' => false,
                'is_global' => true,
                'uuid'=> (string) Str::uuid(),
                'aws_cloudfront_distribution_id' =>$newAwsCloudfrontDistribution->id
            ]);
            $this->info("--AwsCloudfrontDistribution Table Done");
            $this->info("--GeoGroup Table Done");
        }
        //Add Countries
        $countries = [
            ["name"=>"Afghanistan", "code"=>"AF"],
            ["name"=>"Albania", "code"=>"AL"],
            ["name"=>"Algeria", "code"=>"DZ"],
            ["name"=>"American Samoa", "code"=>"AS"],
            ["name"=>"Andorra", "code"=>"AD"],
            ["name"=>"Angola", "code"=>"AO"],
            ["name"=>"Anguilla", "code"=>"AI"],
            ["name"=>"Antarctica", "code"=>"AQ"],
            ["name"=>"Antigua and Barbuda", "code"=>"AG"],
            ["name"=>"Argentina", "code"=>"AR"],
            ["name"=>"Armenia", "code"=>"AM"],
            ["name"=>"Aruba", "code"=>"AW"],
            ["name"=>"Australia", "code"=>"AU"],
            ["name"=>"Austria", "code"=>"AT"],
            ["name"=>"Azerbaijan", "code"=>"AZ"],
            ["name"=>"Bahamas (the)", "code"=>"BS"],
            ["name"=>"Bahrain", "code"=>"BH"],
            ["name"=>"Bangladesh", "code"=>"BD"],
            ["name"=>"Barbados", "code"=>"BB"],
            ["name"=>"Belarus", "code"=>"BY"],
            ["name"=>"Belgium", "code"=>"BE"],
            ["name"=>"Belize", "code"=>"BZ"],
            ["name"=>"Benin", "code"=>"BJ"],
            ["name"=>"Bermuda", "code"=>"BM"],
            ["name"=>"Bhutan", "code"=>"BT"],
            ["name"=>"Bolivia (Plurinational State of)", "code"=>"BO"],
            ["name"=>"Bonaire, Sint Eustatius and Saba", "code"=>"BQ"],
            ["name"=>"Bosnia and Herzegovina", "code"=>"BA"],
            ["name"=>"Botswana", "code"=>"BW"],
            ["name"=>"Bouvet Island", "code"=>"BV"],
            ["name"=>"Brazil", "code"=>"BR"],
            ["name"=>"British Indian Ocean Territory (the)", "code"=>"IO"],
            ["name"=>"Brunei Darussalam", "code"=>"BN"],
            ["name"=>"Bulgaria", "code"=>"BG"],
            ["name"=>"Burkina Faso", "code"=>"BF"],
            ["name"=>"Burundi", "code"=>"BI"],
            ["name"=>"Cabo Verde", "code"=>"CV"],
            ["name"=>"Cambodia", "code"=>"KH"],
            ["name"=>"Cameroon", "code"=>"CM"],
            ["name"=>"Canada", "code"=>"CA"],
            ["name"=>"Cayman Islands (the)", "code"=>"KY"],
            ["name"=>"Central African Republic (the)", "code"=>"CF"],
            ["name"=>"Chad", "code"=>"TD"],
            ["name"=>"Chile", "code"=>"CL"],
            ["name"=>"China", "code"=>"CN"],
            ["name"=>"Christmas Island", "code"=>"CX"],
            ["name"=>"Cocos (Keeling) Islands (the)", "code"=>"CC"],
            ["name"=>"Colombia", "code"=>"CO"],
            ["name"=>"Comoros (the)", "code"=>"KM"],
            ["name"=>"Congo (the Democratic Republic of the)", "code"=>"CD"],
            ["name"=>"Congo (the)", "code"=>"CG"],
            ["name"=>"Cook Islands (the)", "code"=>"CK"],
            ["name"=>"Costa Rica", "code"=>"CR"],
            ["name"=>"Croatia", "code"=>"HR"],
            ["name"=>"Cuba", "code"=>"CU"],
            ["name"=>"Curaçao", "code"=>"CW"],
            ["name"=>"Cyprus", "code"=>"CY"],
            ["name"=>"Czechia", "code"=>"CZ"],
            ["name"=>"Côte d'Ivoire", "code"=>"CI"],
            ["name"=>"Denmark", "code"=>"DK"],
            ["name"=>"Djibouti", "code"=>"DJ"],
            ["name"=>"Dominica", "code"=>"DM"],
            ["name"=>"Dominican Republic (the)", "code"=>"DO"],
            ["name"=>"Ecuador", "code"=>"EC"],
            ["name"=>"Egypt", "code"=>"EG"],
            ["name"=>"El Salvador", "code"=>"SV"],
            ["name"=>"Equatorial Guinea", "code"=>"GQ"],
            ["name"=>"Eritrea", "code"=>"ER"],
            ["name"=>"Estonia", "code"=>"EE"],
            ["name"=>"Eswatini", "code"=>"SZ"],
            ["name"=>"Ethiopia", "code"=>"ET"],
            ["name"=>"Falkland Islands (the) [Malvinas]", "code"=>"FK"],
            ["name"=>"Faroe Islands (the)", "code"=>"FO"],
            ["name"=>"Fiji", "code"=>"FJ"],
            ["name"=>"Finland", "code"=>"FI"],
            ["name"=>"France", "code"=>"FR"],
            ["name"=>"French Guiana", "code"=>"GF"],
            ["name"=>"French Polynesia", "code"=>"PF"],
            ["name"=>"French Southern Territories (the)", "code"=>"TF"],
            ["name"=>"Gabon", "code"=>"GA"],
            ["name"=>"Gambia (the)", "code"=>"GM"],
            ["name"=>"Georgia", "code"=>"GE"],
            ["name"=>"Germany", "code"=>"DE"],
            ["name"=>"Ghana", "code"=>"GH"],
            ["name"=>"Gibraltar", "code"=>"GI"],
            ["name"=>"Greece", "code"=>"GR"],
            ["name"=>"Greenland", "code"=>"GL"],
            ["name"=>"Grenada", "code"=>"GD"],
            ["name"=>"Guadeloupe", "code"=>"GP"],
            ["name"=>"Guam", "code"=>"GU"],
            ["name"=>"Guatemala", "code"=>"GT"],
            ["name"=>"Guernsey", "code"=>"GG"],
            ["name"=>"Guinea", "code"=>"GN"],
            ["name"=>"Guinea-Bissau", "code"=>"GW"],
            ["name"=>"Guyana", "code"=>"GY"],
            ["name"=>"Haiti", "code"=>"HT"],
            ["name"=>"Heard Island and McDonald Islands", "code"=>"HM"],
            ["name"=>"Holy See (the)", "code"=>"VA"],
            ["name"=>"Honduras", "code"=>"HN"],
            ["name"=>"Hong Kong", "code"=>"HK"],
            ["name"=>"Hungary", "code"=>"HU"],
            ["name"=>"Iceland", "code"=>"IS"],
            ["name"=>"India", "code"=>"IN"],
            ["name"=>"Indonesia", "code"=>"ID"],
            ["name"=>"Iran (Islamic Republic of)", "code"=>"IR"],
            ["name"=>"Iraq", "code"=>"IQ"],
            ["name"=>"Ireland", "code"=>"IE"],
            ["name"=>"Isle of Man", "code"=>"IM"],
            ["name"=>"Israel", "code"=>"IL"],
            ["name"=>"Italy", "code"=>"IT"],
            ["name"=>"Jamaica", "code"=>"JM"],
            ["name"=>"Japan", "code"=>"JP"],
            ["name"=>"Jersey", "code"=>"JE"],
            ["name"=>"Jordan", "code"=>"JO"],
            ["name"=>"Kazakhstan", "code"=>"KZ"],
            ["name"=>"Kenya", "code"=>"KE"],
            ["name"=>"Kiribati", "code"=>"KI"],
            ["name"=>"Korea (the Democratic People's Republic of)", "code"=>"KP"],
            ["name"=>"Korea (the Republic of)", "code"=>"KR"],
            ["name"=>"Kuwait", "code"=>"KW"],
            ["name"=>"Kyrgyzstan", "code"=>"KG"],
            ["name"=>"Lao People's Democratic Republic (the)", "code"=>"LA"],
            ["name"=>"Latvia", "code"=>"LV"],
            ["name"=>"Lebanon", "code"=>"LB"],
            ["name"=>"Lesotho", "code"=>"LS"],
            ["name"=>"Liberia", "code"=>"LR"],
            ["name"=>"Libya", "code"=>"LY"],
            ["name"=>"Liechtenstein", "code"=>"LI"],
            ["name"=>"Lithuania", "code"=>"LT"],
            ["name"=>"Luxembourg", "code"=>"LU"],
            ["name"=>"Macao", "code"=>"MO"],
            ["name"=>"Madagascar", "code"=>"MG"],
            ["name"=>"Malawi", "code"=>"MW"],
            ["name"=>"Malaysia", "code"=>"MY"],
            ["name"=>"Maldives", "code"=>"MV"],
            ["name"=>"Mali", "code"=>"ML"],
            ["name"=>"Malta", "code"=>"MT"],
            ["name"=>"Marshall Islands (the)", "code"=>"MH"],
            ["name"=>"Martinique", "code"=>"MQ"],
            ["name"=>"Mauritania", "code"=>"MR"],
            ["name"=>"Mauritius", "code"=>"MU"],
            ["name"=>"Mayotte", "code"=>"YT"],
            ["name"=>"Mexico", "code"=>"MX"],
            ["name"=>"Micronesia (Federated States of)", "code"=>"FM"],
            ["name"=>"Moldova (the Republic of)", "code"=>"MD"],
            ["name"=>"Monaco", "code"=>"MC"],
            ["name"=>"Mongolia", "code"=>"MN"],
            ["name"=>"Montenegro", "code"=>"ME"],
            ["name"=>"Montserrat", "code"=>"MS"],
            ["name"=>"Morocco", "code"=>"MA"],
            ["name"=>"Mozambique", "code"=>"MZ"],
            ["name"=>"Myanmar", "code"=>"MM"],
            ["name"=>"Namibia", "code"=>"NA"],
            ["name"=>"Nauru", "code"=>"NR"],
            ["name"=>"Nepal", "code"=>"NP"],
            ["name"=>"Netherlands (the)", "code"=>"NL"],
            ["name"=>"New Caledonia", "code"=>"NC"],
            ["name"=>"New Zealand", "code"=>"NZ"],
            ["name"=>"Nicaragua", "code"=>"NI"],
            ["name"=>"Niger (the)", "code"=>"NE"],
            ["name"=>"Nigeria", "code"=>"NG"],
            ["name"=>"Niue", "code"=>"NU"],
            ["name"=>"Norfolk Island", "code"=>"NF"],
            ["name"=>"Northern Mariana Islands (the)", "code"=>"MP"],
            ["name"=>"Norway", "code"=>"NO"],
            ["name"=>"Oman", "code"=>"OM"],
            ["name"=>"Pakistan", "code"=>"PK"],
            ["name"=>"Palau", "code"=>"PW"],
            ["name"=>"Palestine, State of", "code"=>"PS"],
            ["name"=>"Panama", "code"=>"PA"],
            ["name"=>"Papua New Guinea", "code"=>"PG"],
            ["name"=>"Paraguay", "code"=>"PY"],
            ["name"=>"Peru", "code"=>"PE"],
            ["name"=>"Philippines (the)", "code"=>"PH"],
            ["name"=>"Pitcairn", "code"=>"PN"],
            ["name"=>"Poland", "code"=>"PL"],
            ["name"=>"Portugal", "code"=>"PT"],
            ["name"=>"Puerto Rico", "code"=>"PR"],
            ["name"=>"Qatar", "code"=>"QA"],
            ["name"=>"Republic of North Macedonia", "code"=>"MK"],
            ["name"=>"Romania", "code"=>"RO"],
            ["name"=>"Russian Federation (the)", "code"=>"RU"],
            ["name"=>"Rwanda", "code"=>"RW"],
            ["name"=>"Réunion", "code"=>"RE"],
            ["name"=>"Saint Barthélemy", "code"=>"BL"],
            ["name"=>"Saint Helena, Ascension and Tristan da Cunha", "code"=>"SH"],
            ["name"=>"Saint Kitts and Nevis", "code"=>"KN"],
            ["name"=>"Saint Lucia", "code"=>"LC"],
            ["name"=>"Saint Martin (French part)", "code"=>"MF"],
            ["name"=>"Saint Pierre and Miquelon", "code"=>"PM"],
            ["name"=>"Saint Vincent and the Grenadines", "code"=>"VC"],
            ["name"=>"Samoa", "code"=>"WS"],
            ["name"=>"San Marino", "code"=>"SM"],
            ["name"=>"Sao Tome and Principe", "code"=>"ST"],
            ["name"=>"Saudi Arabia", "code"=>"SA"],
            ["name"=>"Senegal", "code"=>"SN"],
            ["name"=>"Serbia", "code"=>"RS"],
            ["name"=>"Seychelles", "code"=>"SC"],
            ["name"=>"Sierra Leone", "code"=>"SL"],
            ["name"=>"Singapore", "code"=>"SG"],
            ["name"=>"Sint Maarten (Dutch part)", "code"=>"SX"],
            ["name"=>"Slovakia", "code"=>"SK"],
            ["name"=>"Slovenia", "code"=>"SI"],
            ["name"=>"Solomon Islands", "code"=>"SB"],
            ["name"=>"Somalia", "code"=>"SO"],
            ["name"=>"South Africa", "code"=>"ZA"],
            ["name"=>"South Georgia and the South Sandwich Islands", "code"=>"GS"],
            ["name"=>"South Sudan", "code"=>"SS"],
            ["name"=>"Spain", "code"=>"ES"],
            ["name"=>"Sri Lanka", "code"=>"LK"],
            ["name"=>"Sudan (the)", "code"=>"SD"],
            ["name"=>"Suriname", "code"=>"SR"],
            ["name"=>"Svalbard and Jan Mayen", "code"=>"SJ"],
            ["name"=>"Sweden", "code"=>"SE"],
            ["name"=>"Switzerland", "code"=>"CH"],
            ["name"=>"Syrian Arab Republic", "code"=>"SY"],
            ["name"=>"Taiwan (Province of China)", "code"=>"TW"],
            ["name"=>"Tajikistan", "code"=>"TJ"],
            ["name"=>"Tanzania, United Republic of", "code"=>"TZ"],
            ["name"=>"Thailand", "code"=>"TH"],
            ["name"=>"Timor-Leste", "code"=>"TL"],
            ["name"=>"Togo", "code"=>"TG"],
            ["name"=>"Tokelau", "code"=>"TK"],
            ["name"=>"Tonga", "code"=>"TO"],
            ["name"=>"Trinidad and Tobago", "code"=>"TT"],
            ["name"=>"Tunisia", "code"=>"TN"],
            ["name"=>"Turkey", "code"=>"TR"],
            ["name"=>"Turkmenistan", "code"=>"TM"],
            ["name"=>"Turks and Caicos Islands (the)", "code"=>"TC"],
            ["name"=>"Tuvalu", "code"=>"TV"],
            ["name"=>"Uganda", "code"=>"UG"],
            ["name"=>"Ukraine", "code"=>"UA"],
            ["name"=>"United Arab Emirates (the)", "code"=>"AE"],
            ["name"=>"United Kingdom of Great Britain and Northern Ireland (the)", "code"=>"GB"],
            ["name"=>"United States Minor Outlying Islands (the)", "code"=>"UM"],
            ["name"=>"United States of America (the)", "code"=>"US"],
            ["name"=>"Uruguay", "code"=>"UY"],
            ["name"=>"Uzbekistan", "code"=>"UZ"],
            ["name"=>"Vanuatu", "code"=>"VU"],
            ["name"=>"Venezuela (Bolivarian Republic of)", "code"=>"VE"],
            ["name"=>"Viet Nam", "code"=>"VN"],
            ["name"=>"Virgin Islands (British)", "code"=>"VG"],
            ["name"=>"Virgin Islands (U.S.)", "code"=>"VI"],
            ["name"=>"Wallis and Futuna", "code"=>"WF"],
            ["name"=>"Western Sahara", "code"=>"EH"],
            ["name"=>"Yemen", "code"=>"YE"],
            ["name"=>"Zambia", "code"=>"ZM"],
            ["name"=>"Zimbabwe", "code"=>"ZW"],
            ["name"=>"Åland Islands", "code"=>"AX"]
        ];
        foreach ($countries as $key => $country) {
            Country::create($country);
        }
        $this->info("--Country Table Done");

        //DynamoDB
        $dynamodbClient = \AWS::createClient('DynamoDB');
        $table_name = 'users_api_histories';
        $tables = $dynamodbClient->listTables();

        if(in_array($table_name, $tables['TableNames']))
        {
            $this->comment("--There is already DynamoDB user_api_histories table");
        } else {
            try {
                $dynamodbClient->createTable([
                    'TableName' => $table_name,
                    'AttributeDefinitions' => [
                        [
                            'AttributeName' => 'id',
                            'AttributeType' => 'S'
                        ],
                    ],
                    'KeySchema' => [
                        [
                            'AttributeName' => 'id',
                            'KeyType'       => 'HASH'
                        ],
                    ],
                    'ProvisionedThroughput' => [
                        'ReadCapacityUnits'  => 10,
                        'WriteCapacityUnits' => 20,
                    ],
                ]);
            } catch (AwsException $e) { 
                return [
                    'Error' => 'Error: ' . $e->getAwsErrorMessage()
                ];
            }    
            $this->info("--DynamoDB Done");
        }

        //Add Billings
        Billing::create(['type' => 'Storage', 'amount' => 1]);
        Billing::create(['type' => 'Bandwidth', 'amount' => 0.15]);
        Billing::create(['type' => 'Otp', 'amount' => 0.06]);
        Billing::create(['type' => 'Cron', 'amount' => 0.0000002]);
        $this->info("--Billing Table Done");

        //Mark setup is done
        if($setupDone == null){
            Configure::create([
                'name' => 'databaseSetupDone',
                'enabled' => true
            ]);
        }
        else{
            $setupDone->update(['enabled' => true]);
        }

        $this->info("Successfully initialized default database records.");

    }
})->purpose('Initialize default Database records.');