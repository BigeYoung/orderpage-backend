<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function add(Request $request)
    {
        $created_at = \Carbon\Carbon::now('Asia/Shanghai');
        $product_guid = Str::uuid();
        $product_name = $request->values['product_name'];
        $box_color = $request->values['box_color'];
        $pic = "scut";
        $cover_color = $request->values['cover_color'];

        $order = new \App\Models\Order;
        $order->created_at = $created_at;
        $order->save();

        $product = new \App\Models\Product;
        $product->guid = $product_guid;
        $product->name = $product_name;
        $product->order_id = $order->id;
        $product->save();

        $features = [
            ["Box,Upper,Pallet", $box_color],
            ["UDisk,Upper,Pallet", null],
            [strpos($product_name,"UDisk")?"Lasering,Upper,UDisk":"Carving,Upper,Wood", $pic],
            ["UDisk,Inside,Box", null],
            ["Cover,Upper,Box", $cover_color],
            ["Box,Under,Pallet", null],
        ];
        for ($i=0; $i < count($features); $i++) { 
            $feature = new \App\Models\Feature;
            $feature->product_guid = $product->guid;
            $feature->index = $i+1;
            $feature->description = $features[$i][0];
            $feature->parameter = $features[$i][1];
            $feature->save();
        }

        if ($product->name == "Box_UDisk") {
            OperationController::add_operations("Box_UDisk", $product->guid);
        }
        elseif ($product->name == "Box_Wood") {
            OperationController::add_operations("Box_Wood_CNC".random_int(1, 2), $product->guid);
        }

        $path = base_path('resources') . '/aml/product_' . $request->values['product_name'] . '.aml.xml';
        $content = file_get_contents($path);

        $search = "__PRODUCT_GUID__";
        $content = str_replace($search, $product->guid, $content);

        $search = "__ORDER_ID__";
        $content = str_replace($search, $order->id, $content);

        $search = "__BOX_COLOR__";
        $content = str_replace($search, $box_color, $content);

        $search = "__PIC__";
        $content = str_replace($search, $pic, $content);

        $search = "__COVER_COLOR__";
        $content = str_replace($search, $cover_color, $content);

        $path = base_path('storage') . '/aml/' . $product->guid . ".aml";
        file_put_contents($path, $content);

        return response()->json([' $created_at' => $created_at]);
    }
}
