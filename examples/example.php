<?php

require "../vendor/autoload.php";

// logging library 
// configuration through ENV references


class MyAppRequests extends \Connect\RequestsProcessor
{
	function __construct()
	{
		parent::__construct('config.json');
	}
	
	function processRequest($req)
	{
// 		print_r($req);
		$req->asset->tiers['customer']->name;	
		$req->asset->tiers['tier1']->name;

		$req->status;
		$req->id;
		$req->type;

		$p1 = $req->asset->params['param_a']->error('xxxx');
// 		$p2 = $req->asset->params['param_b']->error('yyyy')->value('true');
		$p2 = new \Connect\Param('param_b', 'true');
		$req->requestProcessor->updateParameters($req, [$p1, $p2]);
				
		foreach($req->asset->items as $item)
		{
			// ...
		}
			
		foreach($req->getNewItems() as $item)
		{
			// provision new Items	
			$item->mpn;
			\Connect\Logger::get()->info($item->id." New ".($item->quantity + 5));
			$item->old_quantity;
		}
		
		foreach($req->getChangedItems() as $item) 
		{
			// update items
			$item->mpn;
			\Connect\Logger::get()->info($item->id." Change: ".($item->quantity + 5)." -> ".($item->old_quantity));
		}
		
		foreach($req->getRemovedItems() as $item)
		{
			// unprovision items
			\Connect\Logger::get()->info($item->id." Change: ".($item->old_quantity + 5)." -> ".($item->quantity));
		}
		
		$req->asset->params['param_a'];
		
// 		if (!isset($req->asset->params['param_a']))
// 			throw new \Connect\Inquire(array( 'param_a' => 'ActivationID should be set' ));
// 			throw new \Connect\Inquire(array( 
// 					$req->asset->params['param_a']->error('xxxx'),
// 					$req->asset->params['param_b']->error('yyyy')->value('default')
// 			));
				
// 		if (!isset($req->asset->params['param_b']))
// 			throw new \Connect\Fail(array( 'param_b' => 'Your activation code is already used' ));

		throw new \Connect\Skip();

		return "activation succeeded";
	}
}


try {
    $rp = new MyAppRequests();

    $rp->process();

//	throw new Exception('Some unexpected error happened');

} catch (Exception $e) {
	\Connect\Logger::get()->error($e->getMessage());
	
	// Dump to log all level records
	\Connect\Logger::get()->dump();
}

// $rlist = $rp->listRequests(array('status' => 'inquiring'));
// print_r($rlist);