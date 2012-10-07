<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.0 or Upper version
 *
 * @package    SocialMedia
 * @author     Mika UMOTO <http://ryus.co.jp>
 * @copyright  2012 ryus.co.jp
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2
 *
 */

$dirname  = dirname(__FILE__);
$basename = basename($dirname);

$modversion['name']        = "SocialMedia";
$modversion['version']     = 1.00;
$modversion['description'] = "";
$modversion['credits']     = 'RYUS Inc.';
$modversion['author']      = 'Mika UMOTO <http://ryus.co.jp>';
$modversion['help']	       = 'Readme/japanese.html';
$modversion['license']     = 'GNU GPL v2 see LISENCE';
$modversion['image']       = 'public/images/module_icon.png';
$modversion['nice_image']  = 'public/images/module_icon_square.png';
$modversion['dirname']     = $basename;
$modversion['read_any'] = true;
$modversion['cube_style'] = true;

$modversion['hasMain'] = 0;

$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

$modversion['hasSearch'] = 0;

$modversion['hasNotification'] = 0;

$modversion['hasComments'] = 0;


$modversion['onInstall']   = 'Platform/Installer.php';
$modversion['onUpdate']    = 'Platform/Installer.php';
$modversion['onUninstall'] = 'Platform/Installer.php';
