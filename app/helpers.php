<?php
/**
 * Created by PhpStorm.
 * User: hugh
 * Date: 2019/11/10
 * Time: 12:15
 */

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}
