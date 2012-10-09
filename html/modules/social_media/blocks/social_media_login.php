<?php
function b_social_media_login_show() {
    global $xoopsUser;
	
    if (!$xoopsUser) {
		$root =& XCube_Root::getSingleton();
		$service =& $root->mServiceManager->getService('socialMedia');
		if ($service != null) {
			$client =& $root->mServiceManager->createClient($service);
			$block['socialLoginLinks'] = $client->call('getSocialLoginLinks', null);
		}
		
		return $block;
    }
    return false;
}
?>
