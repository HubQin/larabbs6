<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $hash_prefix  = 'last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        // Redis hash key
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());
        // field name
        $field = $this->getHashField();

        // 当前时间，如：2017-10-21 08:35:15
        $now = Carbon::now()->toDateTimeString();

        // 写入Redis
        \RedisManager::hset($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        // Redis hash key
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        // 从 Redis 中获取所有哈希表里的数据
        $dates = \RedisManager::hGetAll($hash);

        // 遍历，并同步到数据库中
        foreach ($dates as $user_id => $actived_at) {
            // 会将 `user_1` 转换为 1
            $user_id = str_replace($this->field_prefix, '', $user_id);

            // 只有当用户存在时才更新到数据库中
            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }

        // 同步之后删除
        \RedisManager::del([$hash]);
    }

    public function getHashFromDateString($date)
    {
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
        return $this->field_prefix . $this->id;
    }

    /**
     * @param $value
     * @return Carbon
     * @throws \Exception
     */
    public function getLastActivedAtAttribute($value)
    {
        // 获取今日对应的哈希表名称
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        // 字段名称，如：user_1
        $field = $this->getHashField();

        // 三元运算符，优先选择 Redis 的数据，否则使用数据库中
        $datetime = \RedisManager::hGet($hash, $field) ? : $value;

        // 如果存在的话，返回时间对应的 Carbon 实体
        if ($datetime) {
            return new Carbon($datetime);
        } else {
            // 否则使用用户注册时间
            return $this->created_at;
        }
    }
}
