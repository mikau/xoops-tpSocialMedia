<?php
//                 ProviderUserLinkAddExistUser.class
class Social_media_ProviderUserLinkAddExistUser extends XCube_ActionFilter
{
	protected $socialMediaType = '';
	protected $socialMediaId = '';
	protected $pengin = null;
	
	public function preBlockFilter()
	{
		$this->mRoot->mDelegateManager->add('Site.CheckLogin.Success' , array($this , 'checkLoginSuccess'));
		$this->mRoot->mDelegateManager->add('Site.CheckLogin.Fail' , array($this , 'checkLoginFail'));
		$this->mRoot->mDelegateManager->add('Site.CheckLogin' , array($this , 'checkLogin'), -1);
		
	}
	
	public function checkLoginSuccess($xoopsUser)
	{
		if($this->socialMediaType == ''){
			return;
		}
		$this->_loadPengin();
		$providerUserLinkHandler = $this->pengin->getModelHandler('ProviderUserLink' , 'social_media');
		$result = $providerUserLinkHandler->addProviderUserLink($this->socialMediaType,$this->socialMediaId,$xoopsUser->get('uid'));
		if($result == false){
			$_SESSION = array();
			$this->_backToAddTypeController(t('Database error'));
		}
	}
	
	public function checkLoginFail($xoopsUser)
	{
		if($this->socialMediaType == ''){
			return;
		}
		$this->_loadPengin();
		$this->_backToAddTypeController(t('Login failed'));
	}
	
	public function checkLogin($xoopsUser)
	{
		if($this->_hasSession() == false){
			return;
		}
		$this->socialMediaType = $_SESSION['socialMediaType'];
		$this->socialMediaId = $_SESSION['socialMediaId'];
	}
	
	protected function _hasSession()
	{
		if((isset($_SESSION['socialMediaType']) == true) and (isset($_SESSION['socialMediaId']) == true)){
			return true;
		}
		return false;
	}
	
	protected function _backToAddTypeController($message)
	{
		$_SESSION['socialMediaType'] = $this->socialMediaType;
		$_SESSION['socialMediaId'] = $this->socialMediaId;
		redirect_header(XOOPS_URL.'/modules/social_media/index.php?controller=provider_user_link_add', 1 , $message);
	}
	
	protected function _loadPengin()
	{
		$pengin = Pengin::getInstance();
		$pengin->path(XOOPS_ROOT_PATH.'/modules/social_media');
		$pengin->translator->useTranslation('social_media', $pengin->cms->langcode, 'translation');
		$this->pengin = $pengin;
	}
}
