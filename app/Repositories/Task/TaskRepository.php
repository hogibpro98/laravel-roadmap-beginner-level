<?php

namespace App\Repositories\Task;

//use App\Exceptions\ApplicationException;
//use App\Helpers;
//use App\Jobs\UserServiceWalletLogJob;
//use App\Models\Service;
//use App\Models\UserKey;
//use App\Models\UserServiceWallet;
//use App\Models\UserTelcoMsgQuota;
use App\Repositories\BaseRepository;
//use App\User;
use App\Utils\Helpers\LogsUtils;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;

class TaskRepository extends BaseRepository
{

    public function model()
    {
        return \App\Models\Task::class;
    }

    /**
     * @throws ValidationException
     */
    public function getList($keyword = null,
                            $filter = [],
                            $counting = false,
                            $limit = 10,
                            $offset = 0,
                            $orderBy = [],
                            $orderType = [])
    {
        $query = $this->model->newQuery();

        if ($keyword != null) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('code', 'LIKE', "%$keyword%");
            });
        }
//        RequestUtils::filterFromParams($filter, $query);

        return $this->getData($query, $limit, $offset, $orderBy, $orderType);
    }

//    public function update($arr)
//    {
//        $configs = $arr['quota_configs'];
//        $userId = $arr['id'];
//        try {
//            $newConfigs = array_map(function ($e) use ($userId) {
//                $newElement = collect($e)->only('telco_code', 'quota_month',
//                    'quota_year', 'warning_threshold');
//                $newElement['updated_at'] = now();
//                $newElement['user_id'] = $userId;
//                return $newElement->toArray();
//            }, $configs);
//            $telcoCodes = array_map(function ($e) {
//                return $e['telco_code'];
//            }, $newConfigs);
//
//            DB::beginTransaction();
//            UserTelcoMsgQuota::where('user_id', $userId)->whereNotIn('telco_code', $telcoCodes)->delete();
//            $rate = UserTelcoMsgQuota::EDIT_QUOTA_MIN_RATE;
//            $percentRate = $rate * 100 . "%";
//            $errBags = [];
//            foreach ($newConfigs as $index => $config) {
//                $quota = UserTelcoMsgQuota::where('user_id', $userId)->where('telco_code', $config['telco_code'])->first();
//                if ($quota) {
//                    if ($config['quota_month'] < ($quota->current_month * UserTelcoMsgQuota::EDIT_QUOTA_MIN_RATE)) {
//                        $errBags["quota_configs.$index.quota_month"] = "Giá trị không được nhỏ hơn $percentRate số tin đã gửi";
//                    }
//                    if ($config['quota_year'] < ($quota->current_year * UserTelcoMsgQuota::EDIT_QUOTA_MIN_RATE)) {
//                        $errBags["quota_configs.$index.quota_year"] = "Giá trị không được nhỏ hơn $percentRate số tin đã gửi";
//                    }
//                }
//            }
//            if (count($errBags)) {
//                throw ValidationException::withMessages($errBags);
//            }
//            $result = UserTelcoMsgQuota::upsert($newConfigs, ['user_id', 'telco_code']);
//            DB::commit();
//            $redis = Cache::connection();
//            //$redis->del($redis->keys('{user-telco-msg-quota*'));
//            try {
//                CommonUtils::redisDelKeys('{user-telco-msg-quota*');
//            } catch (\Exception $e) {
//                Log::error($e);
//            }
//            LogsUtils::fireEventUserActionLog('edit', USER_TELCO_MSG_QUOTA, $userId, $newConfigs);
//            return count($newConfigs) ? $newConfigs : true;
//        } catch (ValidationException $e) {
//            throw $e;
//        } catch (\Exception $e) {
//            DB::rollBack();
//            LogsUtils::fireEventUserActionLog('edit', USER_TELCO_MSG_QUOTA, $userId,
//                $newConfigs, $e->getMessage(), 'fail');
//        }
//
//        return false;
//    }
}
