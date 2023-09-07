<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuTemplateController extends Controller
{
    public function index()
    {
        $menu_templates = \App\Models\MenuTemplate::all();
        foreach($menu_templates as $menu_template) {
            $menu_template->products = json_decode($menu_template->products);
        }
        return response()->json(['menu_templates' => $menu_templates]);
    }
    public function new()
    {
        $menuTemplate = new \App\Models\MenuTemplate();
        $menuTemplate->products = '{"segon": [], "primer": [], "postres": []}';
        $menuTemplate->save();
        return response()->json(['id' => $menuTemplate->id]);
    }

    public function delete($id)
    {
        $menuTemplate = \App\Models\MenuTemplate::find($id);
        $menuTemplate->delete();
        return response()->json(['id' => $menuTemplate->id]);
    }
}
