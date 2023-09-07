<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuAssignationController extends Controller
{
    public static function assign($date, $menu_template_id)
    {
        $menuAssignation = \App\Models\MenuAssignation::where('date', $date)->first();
        if ($menuAssignation) {
            $menuAssignation->delete();
        }
        $menuAssignation = new \App\Models\MenuAssignation();
        $menuAssignation->date = $date;
        $menuAssignation->menu_template_id = $menu_template_id;
        $menuAssignation->save();
        return $menuAssignation->id;
    }

    public function getAssignationByDate($date)
    {
        $menuAssignation = \App\Models\MenuAssignation::where('date', $date)->first();
        if ($menuAssignation) {
            $menuAssignation->menu_template = \App\Models\MenuTemplate::find($menuAssignation->menu_template_id);
            $menuAssignation->menu_template->products = json_decode($menuAssignation->menu_template->products);
            return response()->json(['menu_assignation' => $menuAssignation]);
        } else {
            return response()->json(['menu_assignation' => null]);
        }
    }
}
