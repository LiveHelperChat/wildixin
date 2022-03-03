<?php

$currentUser = erLhcoreClassUser::instance();

if (!isset($_SERVER['HTTP_X_CSRFTOKEN']) || !$currentUser->validateCSFRToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
    die('Invalid CSRF Token');
    exit;
}

LiveHelperChatExtension\wildixin\providers\WildixinLiveHelperChat::getInstance()->deleteContact($Params['user_parameters']['id']);

echo "ok";
exit;

?>