<?php
namespace App\Permissions;

class Permission
{
    public const CAN_ALL = "*";

    public const CAN_VIDEO_INDEX = "video-index";
    public const CAN_VIDEO_STORE = "video-store";
    public const CAN_VIDEO_UPDATE = "video-update";
    public const CAN_VIDEO_SHOW = "video-show";
    public const CAN_VIDEO_DESTROY = "video-destroy";
    public const CAN_VIDEO_UPLOAD = "video-upload";
    public const CAN_VIDEO_STATUS = "video-status";
    public const CAN_VIDEO_THUMBNAILS = "video-thumbnails";
    public const CAN_VIDEO_BYPATH = "video-by-path";

    public const CAN_VIDEOPLAYER_INDEX = "videoplayer-index";
    public const CAN_VIDEOPLAYER_STORE = "videoplayer-store";
    public const CAN_VIDEOPLAYER_UPDATE = "videoplayer-update";
    public const CAN_VIDEOPLAYER_SHOW = "videoplayer-show";
    public const CAN_VIDEOPLAYER_DESTROY = "videoplayer-destroy";

    public const CAN_CONFIGURE_INDEX = "configure-index";
    public const CAN_CONFIGURE_STORE = "configure-store";
    public const CAN_CONFIGURE_UPDATE = "configure-update";
    public const CAN_CONFIGURE_SHOW = "configure-show";
    public const CAN_CONFIGURE_DESTROY = "configure-destroy";

    public const CAN_USER_INDEX = "user-index";
    public const CAN_USER_STORE = "user-store";
    public const CAN_USER_UPDATE = "user-update";
    public const CAN_USER_SHOW = "user-show";
    public const CAN_USER_DESTROY = "user-destroy";
    public const CAN_USER_UPDATE_BALANCE = "user-update-balance";
    public const CAN_USER_GET_COMPANIES = "user-get-companies";
    public const CAN_USER_GET_GROUPS = "user-get-groups";
    public const CAN_USER_GET_BALANCE = "user-get-balance";
    public const CAN_USER_SET_BALANCE = "user-set-balance";

    public const CAN_AUTORENEW_INDEX = "autorenew-index";
    public const CAN_AUTORENEW_STORE = "autorenew-store";
    public const CAN_AUTORENEW_UPDATE = "autorenew-update";
    public const CAN_AUTORENEW_SHOW = "autorenew-show";
    public const CAN_AUTORENEW_DESTROY = "autorenew-destroy";

    public const CAN_BILLING_INDEX = "billing-index";
    public const CAN_BILLING_STORE = "billing-store";
    public const CAN_BILLING_UPDATE = "billing-update";
    public const CAN_BILLING_SHOW = "billing-show";
    public const CAN_BILLING_DESTROY = "billing-destroy";


    public const CAN_COMPANY_INDEX = "company-index";
    public const CAN_COMPANY_STORE = "company-store";
    public const CAN_COMPANY_UPDATE = "company-update";
    public const CAN_COMPANY_SHOW = "company-show";
    public const CAN_COMPANY_DESTROY = "company-destroy";
    public const CAN_COMPANY_GET_USERS = "company-get-users";
    public const CAN_COMPANY_ADD_USER = "company-add-user";
    public const CAN_COMPANY_DELETE_USER = "company-delete-user";
    public const CAN_COMPANY_ADD_GROUP = "company-add-group";

    public const CAN_COUNTRY_INDEX = "country-index";
    public const CAN_COUNTRY_STORE = "country-store";
    public const CAN_COUNTRY_UPDATE = "country-update";
    public const CAN_COUNTRY_SHOW = "country-show";
    public const CAN_COUNTRY_DESTROY = "country-destroy";

    public const CAN_WHITELISTIPS_INDEX = "whitelistips-index";
    public const CAN_WHITELISTIPS_STORE = "whitelistips-store";
    public const CAN_WHITELISTIPS_UPDATE = "whitelistips-update";
    public const CAN_WHITELISTIPS_SHOW = "whitelistips-show";
    public const CAN_WHITELISTIPS_DESTROY = "whitelistips-destroy";

    public const CAN_BLACKLISTIPS_INDEX = "blacklistips-index";
    public const CAN_BLACKLISTIPS_STORE = "blacklistips-store";
    public const CAN_BLACKLISTIPS_UPDATE = "blacklistips-update";
    public const CAN_BLACKLISTIPS_SHOW = "blacklistips-show";
    public const CAN_BLACKLISTIPS_DESTROY = "blacklistips-destroy";

    public const CAN_CRON_INDEX = "cron-index";
    public const CAN_CRON_STORE = "cron-store";
    public const CAN_CRON_UPDATE = "cron-update";
    public const CAN_CRON_SHOW = "cron-show";
    public const CAN_CRON_DESTROY = "cron-destroy";

    public const CAN_EPD_INDEX = "epd-index";
    public const CAN_EPD_STORE = "epd-store";
    public const CAN_EPD_UPDATE = "epd-update";
    public const CAN_EPD_SHOW = "epd-show";
    public const CAN_EPD_DESTROY = "epd-destroy";
    
    public const CAN_GROUP_INDEX = "group-index";
    public const CAN_GROUP_STORE = "group-store";
    public const CAN_GROUP_UPDATE = "group-update";
    public const CAN_GROUP_SHOW = "group-show";
    public const CAN_GROUP_DESTROY = "group-destroy";
    public const CAN_GROUP_GET_USERS = "group-get-users";
    public const CAN_GROUP_ADD_USER = "group-add-user";
    public const CAN_GROUP_DELETE_USER = "group-delete-user";

    public const CAN_HTTPSETTING_INDEX = "httpsetting-index";
    public const CAN_HTTPSETTING_STORE = "httpsetting-store";
    public const CAN_HTTPSETTING_UPDATE = "httpsetting-update";
    public const CAN_HTTPSETTING_SHOW = "httpsetting-show";
    public const CAN_HTTPSETTING_DESTROY = "httpsetting-destroy";

    public const CAN_KEY_INDEX = "key-index";
    public const CAN_KEY_STORE = "key-store";
    public const CAN_KEY_UPDATE = "key-update";
    public const CAN_KEY_SHOW = "key-show";
    public const CAN_KEY_DESTROY = "key-destroy";

	public const CAN_KEYSCODE_INDEX = "keyscode-index";
    public const CAN_KEYSCODE_STORE = "keyscode-store";
    public const CAN_KEYSCODE_UPDATE = "keyscode-update";
    public const CAN_KEYSCODE_SHOW = "keyscode-show";
    public const CAN_KEYSCODE_DESTROY = "keyscode-destroy";

    public const CAN_KEYSREF_INDEX = "keysref-index";
    public const CAN_KEYSREF_STORE = "keysref-store";
    public const CAN_KEYSREF_UPDATE = "keysref-update";
    public const CAN_KEYSREF_SHOW = "keysref-show";
    public const CAN_KEYSREF_DESTROY = "keysref-destroy";

    public const CAN_KEYSSMS_INDEX = "keyssms-index";
    public const CAN_KEYSSMS_STORE = "keyssms-store";
    public const CAN_KEYSSMS_UPDATE = "keyssms-update";
    public const CAN_KEYSSMS_SHOW = "keyssms-show";
    public const CAN_KEYSSMS_DESTROY = "keyssms-destroy";

    public const CAN_LIMIT_INDEX = "limit-index";
    public const CAN_LIMIT_STORE = "limit-store";
    public const CAN_LIMIT_UPDATE = "limit-update";
    public const CAN_LIMIT_SHOW = "limit-show";
    public const CAN_LIMIT_DESTROY = "limit-destroy";

    public const CAN_NOTIFICATION_INDEX = "notification-index";
    public const CAN_NOTIFICATION_STORE = "notification-store";
    public const CAN_NOTIFICATION_UPDATE = "notification-update";
    public const CAN_NOTIFICATION_SHOW = "notification-show";
    public const CAN_NOTIFICATION_DESTROY = "notification-destroy";

    public const CAN_ORDER_INDEX = "order-index";
    public const CAN_ORDER_STORE = "order-store";
    public const CAN_ORDER_UPDATE = "order-update";
    public const CAN_ORDER_SHOW = "order-show";
    public const CAN_ORDER_DESTROY = "order-destroy";

    public const CAN_SMS_INDEX = "sms-index";
    public const CAN_SMS_STORE = "sms-store";
    public const CAN_SMS_UPDATE = "sms-update";
    public const CAN_SMS_SHOW = "sms-show";
    public const CAN_SMS_DESTROY = "sms-destroy";
    public const CAN_SMS_SENDUSERVERIFICATIONMESSAGE = "sms-senduserverificationmessage";
    public const CAN_SMS_SENDMESSAGE = "sms-sendmessage";
    
    public const CAN_CODECHECK_INVOKE = "codecheck-invoke";
        
    public const CAN_FORGOTPASSWORD_INVOKE = "forgotpassword-invoke";
    
    public const CAN_PAYMENT_AUTO_RENEW_USER_PAYMENT = "payment-auto-renew-user-payment";
    public const CAN_PAYMENT_AUTO_RECHARGE_PAYMENT = "payment-auto-recharge-payment";
    public const CAN_PAYMENT_IPN = "payment-ipn";
    public const CAN_PAYMENT_ADDPAYMENTMETHOD = "payment-addpaymentmethod";
    public const CAN_PAYMENT_GETMYSTRIPEPROFILE = "payment-getmystripeprofile";
    public const CAN_PAYMENT_GETMYSTRIPEPAYMENTMETHODS = "payment-getmystripepaymentmethods";

    public const CAN_REPORT_DAILY_REPORT = "report-daily-report";
    public const CAN_REPORT_Monthly_REPORT = "report-monthly-report";
    public const CAN_REPORT_Weekly_REPORT = "report-weekly-report";

    public const CAN_RESETPASSWORD_INVOKE = "resetpassword-invoke";
    public const CAN_USERNOTIFICATIONS_GETNOTIFICATIONS = "usersnotifications-getnotifications";
    public const CAN_USERNOTIFICATIONS_GETUSERS = "usersnotifications-getusers";
    public const CAN_USERNOTIFICATIONS_ADDUSERTONOTIFICATION = "usersnotifications-addusertonotification";
    public const CAN_USERNOTIFICATIONS_DELETEUSERFROMNOTIFICATION = "usersnotifications-deleteuserfromnotification";

    public const CAN_AUTH_USER = "auth-user";
}