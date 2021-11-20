<?php

/*
 * Helper buat send response dalam bentuk json
 */
if (!function_exists('convert_to_nominal')) {
    function convert_to_nominal($val)
    {
        $val = str_replace(",",".",$val);
        $val = preg_replace('/\.(?=.*\.)/', '', $val);
        return intval($val);
    }
}
