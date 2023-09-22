<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MenuTemplateController;
use App\Http\Controllers\MenuAssignationController;
use App\Models\MenuAssignation;
use App\Models\MenuTemplate;
use App\Models\ProductType;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Http\Controllers\SocialiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('dashboard'));
});

Route::get('/alogin', function () {
    return Inertia::render('Auth/Login2');
})->name('alogin');

Route::get('/bar2023', function () {
    Auth::loginUsingId(5);
    return redirect(route('admin.reserves'));
})->name('bar2022');

Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/menjador', function () {
        $menu_assignation = MenuAssignation::where('date', date('Y-m-d'))->first();
        $assignation = false;
        $menu = null;
        if($menu_assignation)
        {
            $assignation = true;
            $menu_template = MenuTemplate::find($menu_assignation->menu_template_id);
            $menu = json_decode($menu_template->products);
        }
        $can_book = false;
        $booking = \App\Models\Booking::where('date', date('Y-m-d'))->where('user_id', Auth::user()->id)->first();
        if(!$booking) {
            $can_book = true;
        }
        return Inertia::render('Menjador')->with('menu', $menu)->with('assignation', $assignation)->with('can_book', $can_book);
    })->name('menjador');
    Route::post('/menjador', [BookingController::class, 'new'])->name('menjador.add');
    Route::get('/encomanar', function () {
        $can_order = false;
        $order_items = null;
        $order_turn = null;
        $order = \App\Models\Order::where('date', date('Y-m-d'))->where('user_id', Auth::user()->id)->first();
        if(!$order) {
            $can_order = true;
        }
        else {
            $order_items = json_decode($order->products);
            $order_turn = $order->turn;
        }
        return Inertia::render('Encomanar')->with('product_types', ProductType::all())->with('can_order', $can_order)->with('order_items', $order_items)->with('order_turn', $order_turn); //Es pot optimizar més, ho sé..
    })->name('encomanar');
    Route::post('/encomanar', [OrderController::class, 'new'])->name('encomanar.add');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin'
])->group(function () {
    Route::get('/admin/settings', function () {
        return Inertia::render('Admin/Settings')->with('settings', [
            'general' => [],
            'menjador' => [
                'enabled' => true,
            ],
            'comandes' => [
                'enabled' => true,
                'product_types' => ProductType::all()
            ]
        ]);
    })->name('admin.settings');
    Route::get('/admin/comandes', function () {
        return Inertia::render('Admin/Comandes');
    })->name('admin.comandes');
    Route::get('/admin/comandes/{date}/{turn}', [OrderController::class, 'index'])->name('admin.comandes.index');
    //Comandes
    Route::get('/admin/comandes/add', [ProductTypeController::class, 'new'])->name('admin.settings.comandes.add');
    Route::post('/admin/comandes/delete/{id}', [ProductTypeController::class, 'delete'])->name('admin.settings.comandes.delete');
    Route::post('/admin/comandes/update', [SettingsController::class, 'updateComandes'])->name('admin.settings.comandes.update');
    //MenuTemplates
    Route::get('/admin/menjador/menu_templates', [MenuTemplateController::class, 'index'])->name('admin.settings.menjador.menu_templates');
    Route::get('/admin/menjador/add', [MenuTemplateController::class, 'new'])->name('admin.settings.menjador.add');
    Route::get('/admin/menjador/delete/{id}', [MenuTemplateController::class, 'delete'])->name('admin.settings.menjador.delete');
    Route::post('/admin/menjador/update', [SettingsController::class, 'updateMenjador'])->name('admin.settings.menjador.update');
    //Assignation by date Controller get
    Route::get('/admin/menjador/assignation/{date}', [MenuAssignationController::class, 'getAssignationByDate'])->name('admin.settings.menjador.assignation.get');
    Route::get('/admin/reserves', function () {
        return Inertia::render('Admin/Reserves');
    })->name('admin.reserves');
    Route::get('/admin/reserves/{date}', [BookingController::class, 'index'])->name('admin.reserves.index');
});