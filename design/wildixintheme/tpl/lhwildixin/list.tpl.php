<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Wildixin');?></h1>

<form action="<?php echo erLhcoreClassDesign::baseurl('wildixin/list')?>" method="get" class="mb-2" ng-non-bindable>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>
    <input type="hidden" name="doSearch" value="1">
    <input type="hidden" name="ds" value="1">
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Keyword');?></label>
                <input type="text" name="keyword" class="form-control form-control-sm" value="<?php echo htmlspecialchars((string)$input->keyword)?>" aria-label="Username" aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
        <button type="submit" name="doSearch" class="btn btn-secondary"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Search');?></button>
    </div>
</form>


<?php if (isset($items)) : ?>
    <table cellpadding="0" cellspacing="0" class="table table-sm" width="100%" ng-non-bindable>
        <thead>
        <tr>
            <th width="1%"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','ID');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Name');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','By');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Type');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Mobile');?></th>
            <th><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Extension');?></th>
            <th width="1%"></th>
        </tr>
        </thead>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td nowrap="">
                    <b><?php echo htmlspecialchars($item['id']) ?></b> <?php echo $item['last_update'];?>
                </td>
                <td>
                    <?php echo htmlspecialchars((string)$item['name'])?>
                </td>
                <td>
                    <?php echo htmlspecialchars((string)$item['document_from'])?>
                </td>
                <td>
                    <?php echo htmlspecialchars((string)$item['type'])?>
                </td>
                <td>
                    <?php echo htmlspecialchars($item['mobile'])?>
                </td>
                <td>
                    <?php echo htmlspecialchars($item['extension'])?>
                </td>
                <td>
                    <a class="csfr-required csfr-post material-icons text-danger" data-trans="delete_confirm" title="<?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Delete contact');?>" href="<?php echo erLhcoreClassDesign::baseurl('wildixin/deletecontact')?>/<?php echo htmlspecialchars($item['id']) ?>">delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/secure_links.tpl.php')); ?>

    <?php if (isset($pages)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
    <?php endif;?>
<?php endif; ?>