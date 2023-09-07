<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\ProductType;

class SettingsController extends Controller
{
    public function updateComandes(Request $request)
    {
        $product_types = $request->product_types;
        foreach($product_types as $product_type) {
            $_product_type = ProductType::find($product_type['id']);
            $_product_type->name = $product_type['name'];
            $_product_type->price = $product_type['price'];
            $_product_type->save();
        }
        return response()->json(['status' => "success"]);
    }

    public function updateMenjador(Request $request)
    {
        $menu_templates = $request->menu_templates;
        foreach($menu_templates as $menu_template) {
            $_menu_template = \App\Models\MenuTemplate::find($menu_template['id']);
            $_menu_template->name = $menu_template['name'];
            $_menu_template->products = json_encode($menu_template['products']);
            $_menu_template->save();
        }
        if($request->selected_date && $request->selected_date_menu_template) {
            $assignation_id = MenuAssignationController::assign($request->selected_date, $request->selected_date_menu_template);
            if($assignation_id == -1) {
                return response()->json(['status' => "error"]);
            }
        }
        return response()->json(['status' => "success"]);
    }
}
