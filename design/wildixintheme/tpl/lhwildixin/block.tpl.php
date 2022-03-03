<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildix/module','Wildixin');?></h1>

<?php if (isset($errors)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<?php if (isset($updated)) : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('wildix/module','Updated'); ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<form action="<?php echo erLhcoreClassDesign::baseurl('wildixin/block')?>" method="post" class="mb-2" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <input type="hidden" name="doSearch" value="1">
    <input type="hidden" name="ds" value="1">

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Phone');?>*</label>
                <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">+</span>
                    </div>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars((string)$block->phone)?>" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>
        </div><div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','My name');?>*</label>
                <input type="text" name="document_from" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$block->document_from)?>" aria-label="Username" aria-describedby="basic-addon1">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Phone book');?>*</label>
                <select name="phonebook" class="form-control form-control-sm">
                    <option value=""><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Phone book');?></option>
                    <?php foreach (LiveHelperChatExtension\wildixin\providers\WildixinLiveHelperChat::getInstance()->phoneBooks()['items'] as $phoneBook) : ?>
                        <option value="<?php echo $phoneBook['id']?>" <?php if ((string)$block->phonebook == $phoneBook['id']) : ?>selected="selected"<?php endif;?> ><?php echo htmlspecialchars($phoneBook['name'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <button type="submit" name="Block" class="btn btn-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Block');?></button>
    </div>
</form>