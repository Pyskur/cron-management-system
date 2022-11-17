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

}