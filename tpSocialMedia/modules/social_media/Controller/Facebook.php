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
 
class SocialMedia_Controller_Facebook extends SocialMedia_Abstract_Controller
{
	protected $socialMediaType = 'facebook';
	protected $connect = '';
	
	public function __construct()
	{
		$pengin =& Pengin::getInstance();
		$this->connect = $pengin->cms->moduleUrl.'/'.$pengin->cms->getThisModuleDirname().'/index.php?controller=facebook&action=after_login';
		parent::__construct();
	}

	public function main()
	{
		if($this->action == 'login'){
			$this->_loginAction();
		} else {
			$this->_afterLoginAction();
		}
		
	}

	
	protected function _loginAction()
	{
		$option = $this->_getProviderOption();
		
		//facebookログイン用URLを取得
		$_SESSION['facebook_state'] = md5(uniqid(rand(), TRUE));
		$authorize_url = 'https://www.facebook.com/dialog/oauth?'
			. 'client_id='. $option['key']
			. '&redirect_uri='. rawurlencode($this->connect)
			. '&scope='. rawurlencode('publish_stream')
			. '&state='. $_SESSION['facebook_state'];

		header("location:".$authorize_url);
		die;
	}
	
	protected function _afterLoginAction()
	{
		$option = $this->_getProviderOption();

		if ($_GET['state'] != $_SESSION['facebook_state']){
			redirect_header(XOOPS_URL , 1 , $this->socialMediaType.t('Login failed'));
		}
		
		$tokenUrl = 'https://graph.facebook.com/oauth/access_token'
			. '?client_id='. $option['key']
			. '&redirect_uri='. urlencode($this->connect)
			. '&client_secret='. $option['secret']
			. '&code='. $_GET['code'];
		$response = file_get_contents($tokenUrl);
		$params = null;
		parse_str($response, $params);
		if(isset($params['access_token']) == false){
			redirect_header(XOOPS_URL , 1 , $this->socialMediaType.t('Login failed'));
		}

		//ユーザー情報取得
		$graphUrl = 'https://graph.facebook.com/me?access_token='. $params['access_token'];
		$user = json_decode(file_get_contents($graphUrl));

		if ( $user == false ){
			unset($user);
			redirect_header(XOOPS_URL , 1 , $this->socialMediaType.t('Login failed'));
		}

		// uidを取得する
		$providerUserLinkHandler = $this->root->getModelHandler('ProviderUserLink');
		$providerUserLinkModel = $providerUserLinkHandler->getUidBySocialMedia($this->socialMediaType , $user->id);
		if(is_object($providerUserLinkModel) == false){
			// 新規リンク
			$_SESSION['socialMediaType'] = $this->socialMediaType;
			$_SESSION['socialMediaId'] = $user->id;
			$this->root->location('provider_user_link_add');
		}

		$root =& XCube_Root::getSingleton();

		$handler =& xoops_gethandler('user');
		$xoopsUser =& $handler->get($providerUserLinkModel->get('uid'));
		if($xoopsUser->get('level') == 0){
			redirect_header(XOOPS_URL , 1 , t('Your account has not been activated yet.'));
		}
		
		//
		// Regist to session
		//
		$this->_registerSession($xoopsUser);
		redirect_header(XOOPS_URL,1,t('Thank you for logging in.'));
	}
	
	private function _getProviderOption()
	{
		$providerHandler = $this->root->getModelHandler('Provider');
		$providerModel = $providerHandler->getByName($this->socialMediaType);
		if(is_object($providerModel)==false){
			redirect_header(XOOPS_URL , 1 , t('{1} LOGIN has no settings in this site',$this->socialMediaType));
		}
		
		return $providerModel->getOption();
	}
}
