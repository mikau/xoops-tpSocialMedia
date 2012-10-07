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
