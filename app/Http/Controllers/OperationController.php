<?php

namespace App\Http\Controllers;

use \App\Models\Operation;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    static function add_operations(string $filename, string $product_guid, array $replace = array())
    {
        $path = base_path('resources') . "/operations/$filename.csv";
        $operations = array_map('str_getcsv', file($path));
        array_walk($operations, function (&$a) use ($operations) {
            $a = array_combine($operations[0], $a);
        });
        array_shift($operations);
        foreach ($operations as $o) {
            $operation = new Operation;
            $operation->product_guid = $product_guid;
            $operation->equipment_guid = $o['equipment_guid'];
            $operation->name = $o['name'];
            if (array_key_exists($o['name'], $replace))
                $operation->param = $replace[$o['name']];
            else if (!empty($o['param']) && !ctype_space($o['param']))
                $operation->param = $o['param'];
            $operation->save();
        }
    }
}
