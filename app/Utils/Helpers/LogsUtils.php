<?php

namespace App\Utils\Helpers;

//use App\Jobs\AdsSmsVendorLogJob;
//use App\Jobs\DataSponsorDetailLogJob;
//use App\Jobs\KpiLogJob;
//use App\Jobs\MmsDetailLogJob;
//use App\Jobs\NotificationJob;
//use App\Jobs\ProcessBccsLog;
//use App\Jobs\ProcessLog;
//use App\Jobs\RedirectDetailLogJob;
//use App\Jobs\SmsDetailLogJob;
//use App\Models\Ads;
//use App\User;
//use Carbon\Carbon;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogsUtils
{
    public static function fireEventUserActionLog($actionType, $objectType, $objectId, $data = null,
                                                  $reason = null, $status = 'success', $userId = null)
    {
//        if (is_array($data) && isset($data['password'])) {
//            $data['password'] = HIDDEN_PASSWORD;
//        }
//        $log = [
//            'user_id' => $userId ? $userId : auth()->id(),
//            "object_id" => $objectId,
//            "object_type" => $objectType,
//            "action_type" => $actionType,
//            'created_at' => Carbon::now()->format(DATE_TIME_FORMAT),
//            "data" => $data,
//            "reason" => $reason,
//            "status" => $status,
//            'ip' => RequestUtils::getIp(),
//        ];
//        ProcessLog::dispatch($log);
        Log::log('info', 'fireEventUserActionLog');
    }

    public static function fireLogNotification($objectType, $objectId, $actionType,
                                               $receiverId, $extraCode = null, $data = null, $distinctKey = null)
    {
//        $notification = [];
//        $notification["object_type"] = $objectType;
//        $notification["object_id"] = $objectId;
//        $notification["action_type"] = $actionType;
//        if ($extraCode != null) {
//            $notification["pattern_alias"] = $objectType . '-' . $actionType . '-' . $extraCode;
//        } else {
//            $notification["pattern_alias"] = $objectType . '-' . $actionType;
//        }
//        $notification["owner_id"] = auth()->id();
//        $notification["data"] = $data;
//        $notification["status"] = NOTI_NOT_VIEWED_YET;
//        if ($distinctKey !== null) {
//            $notification["distinct_key"] = $distinctKey;
//        }
//        NotificationJob::dispatch($notification, is_array($receiverId) ? $receiverId : [$receiverId]);
        Log::log('info', 'fireLogNotification');
    }

    public static function fireLogNotificationWithoutOwner($objectType, $objectId, $actionType,
                                                           $receiverId, $alias, $data = null, $distinctKey = null)
    {
//        $notification = [];
//        $notification["object_type"] = $objectType;
//        $notification["object_id"] = $objectId;
//        $notification["action_type"] = $actionType;
//        $notification["pattern_alias"] = $alias;
//        $notification["data"] = $data;
//        $notification["status"] = NOTI_NOT_VIEWED_YET;
//        if ($distinctKey !== null) {
//            $notification["distinct_key"] = $distinctKey;
//        }
//        NotificationJob::dispatch($notification, is_array($receiverId) ? $receiverId : [$receiverId]);
        Log::log('info', 'fireLogNotificationWithoutOwner');
    }

    public static function fireLogNotificationToAdmin($objectType, $objectId, $actionType,
                                                      $extraCode = null, $data = null, $distinctKey = null)
    {
//        $adminIds = User::whereNull(DELETED_AT)
//            ->hasStatus(ACTIVE)
//            ->whereHas('roles', function ($q) {
//                $q->whereIn(NAME, ROLE_IS_ADMIN);
//            })
//            ->pluck(ID)->toArray();
//        self::fireLogNotification($objectType, $objectId, $actionType, $adminIds, $extraCode, $data, $distinctKey);
        Log::log('info', 'fireLogNotificationToAdmin');
    }

    public static function adsDetailLogJobDispatch($adsType, $adsId)
    {
//        $job = new MmsDetailLogJob($adsId);
//        switch ($adsType) {
//            case Ads::ADS_REDIRECT_TYPE:
//                $job = new RedirectDetailLogJob($adsId);
//                break;
//            case Ads::ADS_SMS_TYPE:
//                $job = new SmsDetailLogJob($adsId);
//                break;
//            case Ads::ADS_DATA_SPONSOR:
//                $job = new DataSponsorDetailLogJob($adsId);
//                break;
//            case Ads::ADS_SMS_VENDOR:
//                $job = new AdsSmsVendorLogJob($adsId);
//                break;
//        }
//
//        dispatch($job);
        Log::log('info', 'adsDetailLogJobDispatch');
    }

    public static function fireBccsLog($userId, $method, $duration, $endpoint, $reason,
                                       $status, $request, $response, $createdAt)
    {
//        $log = [
//            'user_id' => $userId ? $userId : auth()->id(),
//            "method" => $method,
//            'created_at' => $createdAt,
//            "duration" => $duration,
//            "endpoint" => $endpoint,
//            "reason" => $reason,
//            "status" => $status,
//            "request" => (array)($request),
//            "response" => (array)($response)
//        ];
//
//        $job = new ProcessBccsLog($log);
//        dispatch($job);
        Log::log('info', 'fireBccsLog');
    }

    public static function getModifyLatestLogs($object_type, $object_id, $limit, $startDate = null, $endDate = null)
    {
//        $result = DB::table('user_action_logs')->where('object_id', $object_id)
//            ->where('object_type', $object_type)
//            ->where(function ($query) {
//                $query->whereIn('action_type', LOGS_UTILS_FIELD);
//            })
//            ->where('status', 'success')
//            ->orderByDesc('created_at');
//
//        if ($limit != 0) {
//            $result = $result->limit($limit);
//        }
//
//        if ($startDate != null && $endDate != null) {
//            $result = $result->whereBetween('created_at', [$startDate, $endDate]);
//        }
//
//        return $result->get()->toArray();
        Log::log('info', 'getModifyLatestLogs');
    }

    public static function writeKpiLog($data) {
//        $data['thread_id'] = getmypid();
//        $data['session_id'] = request()->bearerToken() ?? request()->header('key') ?? '';
//        $data['request_content'] = request()->toArray();
//        $data['start_time'] = LARAVEL_START;
//        $data['end_time'] = microtime(true);
//        $data['client_ip'] = RequestUtils::getIp();
//
//
//        KpiLogJob::dispatch($data);
        Log::log('info', 'writeKpiLog');
    }
}
