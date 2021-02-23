<?php
// TODO: maybe remove it to app folder with constants

if ( ! function_exists('constants'))
{
    function constants($key)
    {
        return config('constants.' . $key);
    }
}