<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

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
        DB::enableQueryLog();

        $products = Product::where("guid", $product_guid)
            ->with("order")
            ->with("features")
            ->with("operations")
            ->first();
        return response()->json(["products" => $products]);
    }
}
