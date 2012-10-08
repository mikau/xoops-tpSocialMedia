<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.0 or Upper version
 *
 * @package    SocialMedia
 * @author     umoto <http://ryus.co.jp>
 * @copyright  2010 umoto
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2
 *
 */

class SocialMedia_Model_Provider extends Pengin_Model_AbstractModel
{
	public function __construct()
	{
		$this->val('id', self::INTEGER, null, 10);
		$this->val('name', self::STRING, null, 255);
		$this->val('option', self::TEXT, null);
		$this->val('weight', self::INTEGER, null, 10);
		$this->val('created', self::DATETIME, null);
		$this->val('modified', self::DATETIME, null);
		$this->val('creator_id', self::INTEGER, null, 10);
		$this->val('modifier_id', self::INTEGER, null, 11);
	}
	
	public function getOption()
	{
		$option = json_decode($this->get('option'), true);
		return $option;
	}
}
