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

    public const CAN_USER_INDEX = "user-index";
    public const CAN_USER_STORE = "user-store";
    public const CAN_USER_UPDATE = "user-update";
    public const CAN_USER_SHOW = "user-show";
    public const CAN_USER_DESTROY = "user-destroy";
    public const CAN_USER_UPDATE_BALANCE = "user-update-balance";
    public const CAN_USER_GET_COMPANIES = "user-get-companies";
    public const CAN_USER_GET_GROUPS = "user-get-groups";

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
    
    public const CAN_PAYMENT_AUTO_RENEW_USER_PAYMENT = "payment-auto_renew_user_payment";
    public const CAN_PAYMENT_AUTO_RECHARGE_PAYMENT = "payment-auto_recharge_payment";
    public const CAN_PAYMENT_IPN = "payment-ipn";
    public const CAN_PAYMENT_ADDPAYMENTMETHOD = "payment-addpaymentmethod";
    public const CAN_PAYMENT_GETMYSTRIPEPROFILE = "payment-getmystripeprofile";
    public const CAN_PAYMENT_GETMYSTRIPEPAYMENTMETHODS = "payment-getmystripepaymentmethods";

    public const CAN_REPORT_DAILY_REPORT = "report-daily_report";
    public const CAN_REPORT_Monthly_REPORT = "report-monthly_report";
    public const CAN_REPORT_Weekly_REPORT = "report-weekly_report";

    public const CAN_RESETPASSWORD_INVOKE = "resetpassword-invoke";
    public const CAN_USERNOTIFICATIONS_GETNOTIFICATIONS = "usersnotifications-getnotifications";
    public const CAN_USERNOTIFICATIONS_GETUSERS = "usersnotifications-getusers";
    public const CAN_USERNOTIFICATIONS_ADDUSERTONOTIFICATION = "usersnotifications-addusertonotification";
    public const CAN_USERNOTIFICATIONS_DELETEUSERFROMNOTIFICATION = "usersnotifications-deleteuserfromnotification";

}