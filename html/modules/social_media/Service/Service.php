<?php
/**
 * A_simple_description_for_this_script.
 *
 * @package    Service
 * @author     Suin <suinyeze@gmail.com>
 * @copyright  2011 Suin
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2
 *
 */

class SocialMedia_Service_Service extends XCube_Service
{
	public $mServiceName = 'SocialMedia_Service_Service';
	public $mNameSpace   = 'SocialMedia';
	public $mClassName   = 'SocialMedia_Service_Service';
	
	public function prepare()
	{
		$this->addFunction(S_PUBLIC_FUNC('array getSocialLoginLinks()'));
	}

	public function getSocialLoginLinks()
	{
		$links = array();
		// テーブルを参照する
		$pengin =& Pengin::getInstance();
		$pengin->path(TP_MODULE_PATH.'/social_media');
		$providerHandler = $pengin->getModelHandler('Provider','social_media');
		$criteria = new Pengin_Criteria();
		$providerModel = $providerHandler->find($criteria, 'weight');
		foreach($providerModel as $provider){
			if($provider->get('option') != ''){
				$link['name'] = $provider->get('name');
				$link['title'] = t('Login by {1}',$provider->get('name'));
				$link['url'] = sprintf(XOOPS_URL.'/modules/social_media/index.php?controller=%s&action=login',$provider->get('name'));
				$link['image'] = sprintf(XOOPS_URL.'/modules/social_media/public/images/%s_login.png',$provider->get('name'));
				$links[] = $link;
			}
		}

		return $links;
	}
}
