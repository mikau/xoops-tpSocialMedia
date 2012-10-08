<?php
$pengin = Pengin::getInstance();
$pengin->translator->useTranslation('social_media', $pengin->cms->langcode, 'translation');

$adminmenu[] = array(
	'title' => t("provider list"),
	'link'  => 'admin/index.php?controller=provider_list',
	'show'  => true,
);
