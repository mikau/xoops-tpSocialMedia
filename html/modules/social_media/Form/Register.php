<?php
class SocialMedia_Form_Register extends Pengin_Form
{
	protected $checksXSRF = false; // XSRFをチェックするかどうか
	protected $userModuleConfig = array();
	
	public function setUpForm()
	{
		$configHandler =& xoops_gethandler('config');
		$this->userModuleConfig = $configHandler->getConfigsByDirname('user');
	}
	
	public function setUpProperties()
	{
		$this->add('uname', 'Text')
			->label('User Name')
			->required()
			->shortest($this->userModuleConfig['minuname'])
			->longest(min(25,$this->userModuleConfig['maxuname']))
			;
		$this->add('email', 'Text')
			->label('Email')
			->longest(60)
			->required();
	}
	
	public function validateUname($property)
	{
		$value = $property->getValue();
		if($value == ''){
			return;
		}

		//
		// uname unique check
		//
		$userHandler=&xoops_gethandler('user');
		$criteria =& new CriteriaCompo(new Criteria('uname', $value));
		if ($userHandler->getCount($criteria) > 0) {
			$this->addError(t('Username has been taken.'));
		}

		//
		// Check allow uname string pattern.
		//
		$regex="";
		switch($this->userModuleConfig['uname_test_level']) {
			case 0:
				$regex="/[^a-zA-Z0-9\_\-]/";
				break;

			case 1:
				$regex="/[^a-zA-Z0-9\_\-\<\>\,\.\$\%\#\@\!\\\'\"]/";
				break;

			case 2:
				$regex='/[\x0-\x20\x7F]/';
				break;
		}
		if (($regex !== '') and preg_match($regex, $value)) {
			$this->addError(t('ERROR: Invalid Username.'));
		}

		//
		// Check bad uname patterns.
		//
		foreach($this->userModuleConfig['bad_unames'] as $t_uname) {
			if(!empty($t_uname) && preg_match("/${t_uname}/i", $value)) {
				$this->addError(t('ERROR: Username is reserved.'));
				break;
			}
		}
	}
	
	public function validateEmail($property)
	{
		$value = $property->getValue();
		if($value == ''){
			return;
		}

		if(preg_match("/^[_a-z0-9\-+!#$%&'*\/=?^`{|}~]+(\.[_a-z0-9\-+!#$%&'*\/=?^`{|}~]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i", $value) == false){
			$this->addError(t('Invalid email'));
			return;
		}

		foreach ($this->userModuleConfig['bad_emails'] as $t_email) {
			if (!empty($t_email) && preg_match("/${t_email}/i", $value)) {
				$this->addError(t('Invalid email'));
				return;
			}
		}
		
		//
		// email unique check
		//
		$userHandler=&xoops_gethandler('user');
		$criteria =& new CriteriaCompo(new Criteria('email', $value));
		if ($userHandler->getCount($criteria) > 0) {
			$this->addError(t('Email has been taken.'));
		}
	}

	
}