<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhwildixin','use_admin')) : ?>
<li class="nav-item"><a class="nav-link" href="<?php echo erLhcoreClassDesign::baseurl('wildixin/index')?>"><i class="material-icons">phone_callback</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('wildixin/module','Wildixin');?></a></li>
<?php endif; ?>