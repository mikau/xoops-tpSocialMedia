<?php

class Social_media_Service extends XCube_ActionFilter
{
	function preBlockFilter()
	{
/*
		require_once XOOPS_MODULE_PATH . "/pm/service/Service.class.php";
		$service =& new Pm_Service();
		$service->prepare();
		
		$this->mRoot->mServiceManager->addService('privateMessage', $service);
*/
		require_once dirname(dirname(__FILE__)).'/Service/Service.php';
		$service = new SocialMedia_Service_Service();
		$service->prepare();
		$this->mRoot->mServiceManager->addService('socialMedia', $service);
	}
}
