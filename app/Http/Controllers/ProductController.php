<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    function count()
    {
        $process = DB::select('select count(DISTINCT product_guid) as count from operations join products on product_guid=products.guid where `operations`.`status`!="done";')[0]->count;
        $done = DB::select('select count(distinct product_guid) as count from operations where product_guid not in (select distinct product_guid from operations where `operations`.`status`!="done");')[0]->count;
        return response()->json(['process' => $process, 'done' => $done]);
    }

    function processList()
    {
        $products = Product::with("order")
            ->with("features")
            ->with("operations")
            ->whereHas('operations', function (Builder $query) {
                $query->where('status', '!=', 'done');
            })->get();

        return response()->json(["products" => $products]);
    }

    function doneList()
    {
        $products = Product::with("order")
            ->with("features")
            ->with("operations")
            ->whereDoesntHave('operations', function (Builder $query) {
                $query->where('status', '!=', 'done');
            })
            ->get();

        return response()->json(["products" => $products]);
    }

    function info($product_guid)
    {
        $products = Product::where("guid", $product_guid)
            ->with("order")
            ->with("features")
            ->with("operations")
            ->first();
        return response()->json(["products" => $products]);
    }

    function pallet($pallet_guid)
    {
        $response = Http::get("http://192.168.137.121:8500/v1/catalog/service/pallet", ['filter' => 'ServiceID=="'.$pallet_guid.'"', 'dc' => 'dc1']);
        if (empty($response->json()))
            return response('托盘未注册。', 404);
        $Node = $response[0]["Node"];
        $Address = $response[0]["Address"];
        $ServicePort = $response[0]["ServicePort"];
        $PortValid = is_resource(@fsockopen($Address, $ServicePort));
        $ProductID = $response[0]["ServiceMeta"]["ProductID"];
        return response()->json(["Address" => $Address, "ServicePort" => $ServicePort, "PortValid" => $PortValid, "ProductID" => $ProductID, "Node" => $Node]);
    }
}
