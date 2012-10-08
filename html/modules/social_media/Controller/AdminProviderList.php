<?php

class SocialMedia_Controller_AdminProviderList extends SocialMedia_Abstract_Controller
{
	protected $useModels = array('Provider');
	protected $providerHandler,$providerModel;
	
	protected $errors = array();
	protected $providerList = array();
	
	protected $getItems = array();
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function main()
	{
		if(isset($_GET['update']) == true) {
			$this->_updateAction();
		}
		$this->_default();
	}

	protected function _default()
	{
		$criteria = new Pengin_Criteria();

		if(isset($_GET['update']) == false) {
			// 初期画面の時　テーブルを見る
			$this->providerHandler = $this->root->getModelHandler('Provider');
			$this->providerModel = $this->providerHandler->find($criteria, 'weight');
			$i = 0;
			foreach($this->providerModel as $provider){
				$this->providerList[$i]['id'] = $provider->get('id');
				$this->providerList[$i]['name'] = $provider->get('name');
				if($provider->get('option') != ''){
					$option = json_decode($provider->get('option'), true);
					$this->providerList[$i]['key'] = $option['key'];
					$this->providerList[$i]['secret'] = $option['secret'];
				} else {
					$this->providerList[$i]['key'] = '';
					$this->providerList[$i]['secret'] = '';
				}
				$i++;
			}
		} else {
			// 更新の時
			$this->providerHandler = $this->root->getModelHandler('Provider');
			$i = 0;
			foreach($this->providerList as $provider){
				$id = $this->providerList[$i]['id'];
				$this->providerList[$i]['name'] = $this->providerHandler->getNameById($id);
				$i++;
			}
		}
		$this->output['providerList'] = $this->providerList;
		$this->output['has_error'] = $this->_hasError();
		$this->output['errors'] = $this->errors;
		$this->_view();
	}
	
	protected function _updateAction()
	{
		$criteria = new Pengin_Criteria();
		// 入力値チェック
		$this->_formValidate();
		if($this->_hasError() == true){
			return;
		}
		
		$this->providerHandler = $this->root->getModelHandler('Provider');
		$providerUserLinkHandler = $this->root->getModelHandler('ProviderUserLink');
		try {
			// トランザクション開始
			$this->root->cms->database()->queryF('BEGIN');

			// Provider全件更新
			if ($this->providerHandler->updateProviders($_GET) == false) {
				throw new Exception(t('Database error'));
			}
			// すべての入力値が空だったとき、ユーザーとのリンク情報を削除する
			foreach($this->providerList as $list){
				if(($list['key'] == "") and ($list['secret'] == "")){
					$provider_name = $this->providerHandler->getNameById($list['id']);
					$criteria->add('social_media_type', $provider_name);
					$providerUserLinkHandler->deleteAll($criteria);
				}
			}
			// コミット
			$this->root->cms->database()->queryF('COMMIT');
			// 画面遷移
			redirect_header(XOOPS_URL.'/modules/social_media/admin/index.php?controller=provider_list',1,t('provider information is updated'));
		} catch (Exception $e){
			$this->errors[] = $e->getMessage();
			$this->root->cms->database()->queryF('ROLLBACK');
		}
	}
	
	
	protected function _formValidate()
	{
		
		if($this->_explodeGetString('ids') == true){
			$ids = explode(',',substr($_GET['ids'], 0, -1));
		} else {
			$this->errors[] = t('parameter error');
			return false;
		}
		if($this->_explodeGetString('keys') == true){
			$keys = explode(',',substr($_GET['keys'], 0, -1));
		} else {
			$this->errors[] = t('parameter error');
			return false;
		}
		if($this->_explodeGetString('secrets') == true){
			$secrets = explode(',',substr($_GET['secrets'], 0, -1));
		} else {
			$this->errors[] = t('parameter error');
			return false;
		}
		
		$i = 0;

		foreach($ids as $value){
			// 
			$this->providerList[$i]['id'] = $ids[$i];
			$this->providerList[$i]['key'] = $keys[$i];
			$this->providerList[$i]['secret'] = $secrets[$i];
			// チェック
			// すべてが空ならOK
			if(($keys[$i] == '') and ($secrets[$i] == '')){
				// OK
			} else {
				// Key
				if($keys[$i] == ''){
					$this->errors[] = t('{1} is required. at line {2}.',t('Key'),$i+1);
				}
				// Secret
				if($secrets[$i] == ''){
					$this->errors[] = t('{1} is required. at line {2}.',t('Secret'),$i+1);
				}
			}
			$i++;
		}
		
	}
	
	// ?name=a,b,c set $this->getItems[$name] = ("a","b","c")
	private function _explodeGetString($name)
	{
		if(isset($_GET[$name]) == true){
			$this->getItems[$name] = explode(',',substr($_GET[$name], 0, -1));
			return true;
		} else {
			return false;
		}
	}
	
	
	private function _hasError()
	{
		if(count($this->errors) > 0){
			return true;
		}
		return false;
	}
}
