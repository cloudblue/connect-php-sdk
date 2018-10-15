<?php

require "../vendor/autoload.php";


class MyAppRequests extends \Connect\RequestsProcessor
{

    public function processRequest($req)
    {
        $req->asset->tiers['customer']->name;
        $req->asset->tiers['tier1']->name;

        $req->status;
        $req->id;
        $req->type;

        $p1 = $req->asset->params['param_a']->error('xxxx');

        $p2 = new \Connect\Param(['param_b' => true]);
        $req->requestProcessor->updateParameters($req, [$p1, $p2]);

        foreach ($req->asset->items as $item) {
            // ...
        }

        foreach ($req->getNewItems() as $item) {
            // provision new Items
            $item->mpn;
            \Connect\Logger::get()->info($item->id . " New " . ($item->quantity + 5));
            $item->old_quantity;
        }

        foreach ($req->getChangedItems() as $item) {
            // update items
            $item->mpn;
            \Connect\Logger::get()->info($item->id . " Change: " . ($item->quantity + 5) . " -> " . ($item->old_quantity));
        }

        foreach ($req->getRemovedItems() as $item) {
            // unprovision items
            \Connect\Logger::get()->info($item->id . " Change: " . ($item->old_quantity + 5) . " -> " . ($item->quantity));
        }

        $req->asset->params['param_a'];

        throw new \Connect\Skip();

        return "activation succeeded";
    }
}


try {

    $rp = new MyAppRequests();
    $rp->process();

} catch (Exception $e) {
    \Connect\Logger::get()->error($e->getMessage());
    \Connect\Logger::get()->dump();
}

