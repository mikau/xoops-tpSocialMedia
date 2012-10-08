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

if ( !defined('PENGIN_PATH') ) die;

$dirname = basename(dirname(dirname(__FILE__)));

require_once PENGIN_PATH.'/Pengin.php';

$pengin =& Pengin::getInstance();
$installer = $pengin->cms->getInstaller();
$installer->prepareAPI($dirname);


class InstallerExtend
{
	var $module;
	public function __construct($module)
	{
		$this->module = $module;
		$this->_groupPermission();
	}	
	private function _groupPermission()
	{
		// ログインモジュールなのでゲストにアクセス権限を与える
		$moduleId = $this->module->getVar('mid');
		
		$gperm_handler = xoops_gethandler('groupperm');

		$gperm =& $gperm_handler->create();
		$gperm->setVar("gperm_groupid", XOOPS_GROUP_ANONYMOUS);
		$gperm->setVar("gperm_name", "module_read");
		$gperm->setVar("gperm_modid", 1);
		$gperm->setVar("gperm_itemid", $moduleId );
		$gperm_handler->insert($gperm) ;
		unset($gperm);
	}
}