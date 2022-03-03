<?php

$Module = array( "name" => "Wildixin" );

$ViewList = array();

$ViewList['index'] = array(
    'params' => array(),
    'functions' => array('use_admin'),
);

$ViewList['block'] = array(
    'params' => array(),
    'functions' => array('use_admin'),
);

$ViewList['list'] = array(
    'params' => array(),
    'uparams' => array('keyword'),
    'functions' => array('use_admin'),
);

$ViewList['deletecontact'] = array(
    'params' => array('id'),
    'functions' => array('use_admin'),
);

$FunctionList['use_admin'] = array('explain' => 'Allow operator to configure Wildixin');
