<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.0 or Upper version
 *
 * @package    SocialMedia
 * @author     Hidehito NOZAWA aka Suin <http://ryus.co.jp>
 * @copyright  2010 Hidehito NOZAWA
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2
 *
 */

class SocialMedia_Controller_ProviderUserLinkAdd extends SocialMedia_Abstract_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->_setUpForm();
	}

	public function main()
	{
		if(isset($_POST['register']) == true) {
			$this->_registerAction();
		}
				
		$this->_defaultAction();
	}

	protected function _setUpForm()
	{
		$this->form = new SocialMedia_Form_Register();
	}

	protected function _defaultAction()
	{
		$this->output['form'] = $this->form;
		$this->_view();
	}
		
	protected function _registerAction()
	{
		$this->form->fetchInput()->validate();

		if ( $this->form->hasError() === true ) {
			return;
		}
		$memberHandler =& xoops_gethandler('member');
		$newUser =& $memberHandler->createUser();
		$this->_update($newUser);

		try {
			$this->root->cms->database()->queryF('BEGIN');

			if ($memberHandler->insertUser($newUser) == false) {
				throw new Exception(t('Database error 111'));
			}

			if ($memberHandler->addUserToGroup(XOOPS_GROUP_USERS, $newUser->get('uid')) == false) {
				throw new Exception(t('Database error 222'));
			}
			
			$providerUserLinkHandler = $this->root->getModelHandler('ProviderUserLink' , 'social_media');

			$result = $providerUserLinkHandler->addProviderUserLink($_SESSION['socialMediaType'],$_SESSION['socialMediaId'],$newUser->get('uid'));
			if($result == false){
				throw new Exception(t('Database error 333'));
			}
			$this->root->cms->database()->queryF('COMMIT');
			unset($_SESSION['socialMediaType'],$_SESSION['socialMediaId']);
			$this->_registerSession($newUser);
			redirect_header(XOOPS_URL,1,t('Thank you for your registeration. You now logged in.'));
		} catch (Exception $e){
			$this->form->addError($e->getMessage());
			$this->root->cms->database()->queryF('ROLLBACK');
		}
	}
	
	protected function _update($obj)
	{
		$obj->set('uname', $this->post('uname'));
		$obj->set('email', $this->post('email'));
		$obj->set('user_viewemail', 0);
		$obj->set('url', '');
		$obj->set('user_avatar','blank.gif');
		
		$root =& XCube_Root::getSingleton();
		$obj->set('timezone_offset', $root->mContext->getXoopsConfig('default_TZ'));
		$obj->set('pass', '');
		$obj->set('user_mailok', 1);

		$obj->set('actkey','');
		$obj->set('user_regdate',time());
		
		$obj->set('uorder', $root->mContext->getXoopsConfig('com_order'));
		$obj->set('umode', $root->mContext->getXoopsConfig('com_mode'));

		$obj->set('level', 1);
	}

}
