<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function new()
    {
        $productType = new \App\Models\ProductType();
        $productType->save();
        return response()->json(['id' => $productType->id]);
    }

    public function delete(Request $request, $id)
    {
        $productType = \App\Models\ProductType::find($id);
        $productType->delete();
        return response()->json(['id' => $productType->id]);
    }
}
