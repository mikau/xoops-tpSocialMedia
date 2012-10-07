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

abstract class SocialMedia_Abstract_Controller extends Pengin_Controller_Abstract
{
	protected $configs = array();

	public function __construct()
	{
		parent::__construct();
		$this->moduleName = $this->root->cms->getThisModuleName();
		$this->output['module_name'] = $this->moduleName;
	}

	public function main()
	{
	}

	
	protected function _registerSession($xoopsUser)
	{
		//
		// Regist to session
		//
		$root =& XCube_Root::getSingleton();
		$root->mSession->regenerate();
		$_SESSION = array();
		$_SESSION['xoopsUserId'] = $xoopsUser->get('uid');
		$_SESSION['xoopsUserGroups'] = $xoopsUser->getGroups();
	}
}
