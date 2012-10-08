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

require '../../../mainfile.php';
require_once PENGIN_PATH.'/Pengin.php';

require_once XOOPS_ROOT_PATH.'/header.php';

if ( isset($_GET['controller']) === false )
{
	$_GET['controller'] = 'provider_list';
}

$pengin =& Pengin::getInstance();
$pengin->main('admin');

require_once XOOPS_ROOT_PATH.'/footer.php';
