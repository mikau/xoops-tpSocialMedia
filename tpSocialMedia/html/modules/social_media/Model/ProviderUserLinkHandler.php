<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.0 or Upper version
 *
 * @package    SocialMedia
 * @author     umoto <http://ryus.co.jp>
 * @copyright  2010 umoto
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2
 *
 */

class SocialMedia_Model_ProviderUserLinkHandler extends Pengin_Model_AbstractHandler
{
	public function getUidBySocialMedia($socialMediaProviderUserLink , $socialMediaId)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('social_media_type' , $socialMediaProviderUserLink);
		$criteria->add('social_media_id' , $socialMediaId);

		return $this->find($criteria , null , null , 1);
	}

	public function addProviderUserLink($socialMediaProviderUserLink , $socialMediaId , $uid)
	{
		$model = $this->create();
		$model->set('social_media_type' ,$socialMediaProviderUserLink);
		$model->set('social_media_id' ,$socialMediaId);
		$model->set('uid' ,$uid);

		return $this->save($model);
	}
}
