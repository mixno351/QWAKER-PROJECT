<?php
    $languageID = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $languageTAG = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5);

    $content_default = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/lang/ru.json', false);

    $content_setting = $content_default;
    $content_user_lang = trim(str_replace('/', '', strval(substr($_COOKIE['lang'], 0, 2))));
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/lang/' . $content_user_lang . '.json')) {
        $content_setting = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/assets/lang/' . $content_user_lang . '.json', false);
    }
    
    $string_default = json_decode($content_default, true);
    $string_setting = json_decode($content_setting, true);

    $string = array_merge($string_default, $string_setting);

    $languageTAG = $string['language_tag'];

    $content = json_encode($string);
?>