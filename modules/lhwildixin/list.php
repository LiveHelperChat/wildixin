<?php

$tpl = erLhcoreClassTemplate::getInstance('lhwildixin/list.tpl.php');

if (isset($_GET['doSearch'])) {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/wildixin/classes/filter/list.php','format_filter' => true, 'use_override' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = true;
} else {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/wildixin/classes/filter/list.php','format_filter' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = false;
}

$append = erLhcoreClassSearchHandler::getURLAppendFromInput($filterParams['input_form']);

$data = LiveHelperChatExtension\wildixin\providers\WildixinLiveHelperChat::getInstance()->searchContact(
    $filterParams['filter'],
    erLhcoreClassURL::getInstance()->getParam('page')
);

$pages = new lhPaginator();
$pages->items_total = $data['total'];
$pages->translationContext = 'chat/activechats';
$pages->serverURL = erLhcoreClassDesign::baseurl('wildixin/list').$append;
$pages->paginate();
$tpl->set('pages',$pages);

if ($pages->items_total > 0) {
    $tpl->set('items', $data['items']);
}

$filterParams['input_form']->form_action = erLhcoreClassDesign::baseurl('wildixin/list');
$tpl->set('input',$filterParams['input_form']);
$tpl->set('inputAppend',$append);

$Result['content'] = $tpl->fetch();

$Result['path'] =array(
    array(
        'url' => erLhcoreClassDesign::baseurl('wildixin/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Wildixin')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('wildixin/block'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','List')
    )
)

?>