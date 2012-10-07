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

class SocialMedia_Model_ProviderHandler extends Pengin_Model_AbstractHandler
{
	public function getByName($name)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('name' , $name);
		return $this->find($criteria , null , null , 1);
	}
	
	public function getNameById($id)
	{
		$criteria = new Pengin_Criteria();
		$criteria->add('id' , $id);
		$model = $this->find($criteria , null , null , 1);
		if(is_object($model) == true){
			return $model->get('name');
		} else {
			return false;
		}
	}

	public function updateProviders($item)
	{
		$ids = explode(',',substr($item['ids'], 0, -1));
		$keys = explode(',',substr($item['keys'], 0, -1));
		$secrets = explode(',',substr($item['secrets'], 0, -1));
		$i = 0;
		
		foreach($ids as $value){
			$id = $ids[$i];
			$model = $this->load($id);
			if($keys[$i] != ""){
				$option = '{"key":"'.$keys[$i].'","secret":"'.$secrets[$i].'"}';
			} else {
				$option = '';
			}
			$model->set('option', $option);
			$model->set('weight',$i);
			$model->set('id', $ids[$i]);
			if($this->save($model) == false){
				return false;
			}
			$i++;
		}
		return true;
	}
}
