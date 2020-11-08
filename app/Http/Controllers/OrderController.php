<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $p_name = str_replace("Box_", "", $product_name);

        $features = [
            ["Box,Upper,Pallet", $box_color],
            [$p_name.",Upper,Pallet", null],
            [strpos($product_name, "UDisk") ? "Lasering,Upper,UDisk" : "Carving,Upper,Wood", $pic],
            [$p_name.",Inside,Box", null],
            ["Cover,Upper,Box", $cover_color],
            ["Box,Under,Pallet", null],
        ];
        for ($i = 0; $i < count($features); $i++) {
            $feature = new \App\Models\Feature;
            $feature->product_guid = $product->guid;
            $feature->index = $i + 1;
            $feature->description = $features[$i][0];
            $feature->parameter = $features[$i][1];
            $feature->save();
        }

        if ($product->name == "Box_UDisk") {
            OperationController::add_operations(
                "Box_UDisk",
                $product->guid,
                ["Operation_LB" => $request->box_idx, "Operation_LC" => $request->cover_idx]
            );

            $request = [
                "__UDisk_OR_Wood__" => "UDisk",
                "__Lasering_OR_Carving__" => "Lasering",
                "__PRODUCT_GUID__" => $product->guid,
                "__ORDER_ID__" => $product->order_id,
                "__FEATURE_1__" => $request->box_idx,
                "__FEATURE_3__" => "test1.nc",
                "__FEATURE_5__" => $request->cover_idx
            ];
        } elseif ($product->name == "Box_Wood") {
            OperationController::add_operations(
                "Box_Wood_CNC" . random_int(1, 2),
                $product->guid,
                ["Operation_LB" => $request->box_idx, "Operation_LC" => $request->cover_idx]
            );

            $request = [
                "__UDisk_OR_Wood__" => "Wood",
                "__Lasering_OR_Carving__" => "Carving",
                "__PRODUCT_GUID__" => $product->guid,
                "__ORDER_ID__" => $product->order_id,
                "__FEATURE_1__" => $request->box_idx,
                "__FEATURE_3__" => "8",
                "__FEATURE_5__" => $request->cover_idx
            ];
        }

        $response = Http::put('http://product-reactor:9090/product_reactor', $request);

        // $path = base_path('resources') . '/aml/product_' . $request->values['product_name'] . '.aml.xml';
        // $content = file_get_contents($path);

        // $search = "__PRODUCT_GUID__";
        // $content = str_replace($search, $product->guid, $content);

        // $search = "__ORDER_ID__";
        // $content = str_replace($search, $order->id, $content);

        // $search = "__BOX_COLOR__";
        // $content = str_replace($search, $box_color, $content);

        // $search = "__PIC__";
        // $content = str_replace($search, $pic, $content);

        // $search = "__COVER_COLOR__";
        // $content = str_replace($search, $cover_color, $content);

        // $path = base_path('storage') . '/aml/' . $product->guid . ".aml";
        // file_put_contents($path, $content);

        return response($response->body(), $response->status());
    }
}
