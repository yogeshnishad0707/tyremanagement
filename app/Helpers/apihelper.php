<?php

use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;

if (!function_exists('getval')) {
    function getval($table, $key, $id, $value)
    {
        $value = DB::table("$table")->where("$key", "$id")->pluck("$value")->first();
        return $value;
    }
}
if (!function_exists('getvalaggregate')) {
    function getvalaggregate($table, $key, $id, $value, $type)
    {
        $value = DB::table("$table")->where("$key", "$id")->$type("$value");
        return $value;
    }
}