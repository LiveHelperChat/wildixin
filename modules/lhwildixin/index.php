<?php
$tpl = erLhcoreClassTemplate::getInstance('lhwildixin/index.tpl.php');

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('wildixin/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Wildixin')
    )
);

?>