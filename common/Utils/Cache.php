<?php

namespace Common\Utils;

use DateInterval;

class Cache
{
    // make key 
    public static function makeKey($query, $params = [], $prefix = "")
    {
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        $key = $sql . '.' . json_encode($bindings) . '.' . json_encode($params);
        $key = md5($key);
        return "sql_cache_" . $prefix . $key;
    }

    public static function forgetSql($query, $params = [], $prefix = "")
    {
        $key = self::makeKey($query, $params, $prefix);
        return cache()->forget($key);
    }

    public static function rememberSql($query, array $params = [], DateInterval $minutes = new DateInterval(1000), $prefix = "", callable $callback = null)
    {
        $key = self::makeKey($query, $params, $prefix);
        return cache()->remember($key, $minutes, $callback);
    }



}