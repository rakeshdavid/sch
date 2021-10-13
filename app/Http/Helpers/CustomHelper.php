<?php

function mysql_date()
{
    return date('Y-m-d H:i:s');
}

function detectPlatform()
{
    return explode('.', request()->getHost());
}

function unlink_if_exist($file_path)
{
    if(file_exists($file_path)){
        unlink($file_path);
    }
}