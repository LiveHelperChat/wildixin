<?php
$tpl = erLhcoreClassTemplate::getInstance('lhwildixin/block.tpl.php');

$block = new stdClass();
$block->phone = '';
$block->phonebook = '';
$block->document_from = '';

if (ezcInputForm::hasPostData()) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('wildixin/block');
        exit;
    }

    $definition = array(
        'phone' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'document_from' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'phonebook' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
        )
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ( $form->hasValidData( 'phone' ) && $form->phone != '') {
        $block->phone = $form->phone;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Please enter a phone!');
    }

    if ( $form->hasValidData( 'document_from' ) && $form->document_from != '') {
        $block->document_from = $form->document_from;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Please enter your name!');
    }

    if ( $form->hasValidData( 'phonebook' )) {
        $block->phonebook = $form->phonebook;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Please choose a phone book!');
    }

    if (count($Errors) == 0) {
        try {
            LiveHelperChatExtension\wildixin\providers\WildixinLiveHelperChat::getInstance()->block($block);
            $tpl->set('updated',true);
        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('block',$block);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('wildixin/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Wildixin')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('wildixin/block'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Block')
    )
);

?>