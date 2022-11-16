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
}