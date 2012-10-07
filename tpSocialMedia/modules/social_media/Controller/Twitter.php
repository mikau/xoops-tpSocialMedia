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
 
// twitteroauth.phpを読み込む
require_once(XOOPS_TRUST_PATH."/vendor/twitteroauth/twitteroauth.php");

class SocialMedia_Controller_Twitter extends SocialMedia_Abstract_Controller
{
	protected $socialMediaType = 'twitter';
	protected $connect = '';
	
	public function __construct()
	{
		$pengin =& Pengin::getInstance();
		$this->connect = $pengin->cms->moduleUrl.'/'.$pengin->cms->getThisModuleDirname().'/index.php?controller=twitter&action=after_login';
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

		// セッションにアクセストークンがなかったらloginページに飛ぶ
		// サインインしていてもログイン状態でなければSESSIONを消す
		$pengin =& Pengin::getInstance();
		if($pengin->cms->isUser() == false){
			unset($_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
		}

		if((isset($_SESSION['oauth_token']) == true) and (isset($_SESSION['oauth_token_secret']) == true)){
			if($_SESSION['oauth_token']===NULL && $_SESSION['oauth_token_secret']===NULL){
				$tokenExist = false;
			} else {
				$tokenExist = true;
			}
		} else {
			$tokenExist = false;
		}
		if($tokenExist == false){

			// OAuthオブジェクト生成
			$to = new TwitterOAuth($option['key'],$option['secret']);

			// callbackURLを指定してRequest tokenを取得
			$tok = $to->getRequestToken($this->connect);

			// セッションに保存
			$_SESSION['request_token']=$token=$tok['oauth_token'];
			$_SESSION['request_token_secret'] = $tok['oauth_token_secret'];

			// サインインするためのURLを取得
			$url = $to->getAuthorizeURL($token);
			header("location:".$url);
			die;

		}else{
			//サインインしていればヘッダーを出力
			//include("user_header.php");
		}
		
		
	}
	
	protected function _afterLoginAction()
	{

		$option = $this->_getProviderOption();
		
		// パラメータからoauth_verifierを取得
		$verifier = $_GET['oauth_verifier'];

		// OAuthオブジェクト生成
		if(isset($_SESSION['request_token']) == false){
			$_SESSION['request_token'] = '';
		}
		if(isset($_SESSION['request_token_secret']) == false){
			$_SESSION['request_token_secret'] = '';
		}
		$to = new TwitterOAuth($option['key'],$option['secret'],$_SESSION['request_token'],$_SESSION['request_token_secret']);

		// oauth_verifierを使ってAccess tokenを取得
		$access_token = $to->getAccessToken($verifier);

		// token keyとtoken secret, user_id, screen_nameをセッションに保存
		$_SESSION['oauth_token'] = $access_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $access_token['oauth_token_secret'];

		//TwitterのID(数値です)
		$_SESSION['user_id'] = $access_token['user_id'];

		//スクリーンネーム(いわゆる、アドレスバーに表示される部分です)
		$_SESSION['screen_name'] = $access_token['screen_name'];

		//pen_dump($_SESSION);
		// uidを取得する
		$providerUserLinkHandler = $this->root->getModelHandler('ProviderUserLink');
		$providerUserLinkModel = $providerUserLinkHandler->getUidBySocialMedia($this->socialMediaType , $_SESSION['user_id']);
		if(is_object($providerUserLinkModel) == false){
			// 新規リンク
			$_SESSION['socialMediaType'] = $this->socialMediaType;
			$_SESSION['socialMediaId'] = $_SESSION['user_id'];
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
