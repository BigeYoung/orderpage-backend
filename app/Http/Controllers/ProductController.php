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
        $process = DB::select('select count(DISTINCT product_guid) as count from operations join products on product_guid=products.guid where `status`!="done";')[0]->count;
        $done = DB::select('select count(distinct product_guid) as count from operations where product_guid not in (select distinct product_guid from operations where `status`!="done");')[0]->count;
        return response()->json(['process' => $process, 'done' => $done]);
    }

    function processList()
    {
        DB::enableQueryLog(); // Enable query log

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
        DB::enableQueryLog(); // Enable query log

        $products = Product::with("order")
            ->with("features")
            ->with("operations")
            ->whereDoesntHave('operations', function (Builder $query) {
                $query->where('status', '!=', 'done');
            })
            ->get();

        return response()->json(["products" => $products]);
    }
}
