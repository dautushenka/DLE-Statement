<?php

function t($text)
{
    static $translate = null;

    if ($translate === null)
    {
        if (file_exists(ST_DIR . "/Resources/language/" . $GLOBALS['config']['langs'] . ".lng"))
        {
            $translate = include(ST_DIR . "/Resources/language/" . $GLOBALS['config']['langs'] . ".lng");
        }
        else
        {
            $translate = array();
        }
    }

    return isset($translate[$text])?$translate[$text]:$text;
}

function toUTF8($text)
{
    if (strtoupper(mb_internal_encoding()) !== 'UTF-8')
    {
        return mb_convert_encoding($text, 'UTF-8');
    }
}

?>
