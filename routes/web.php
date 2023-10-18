<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiagramaSecuenciaController;
use App\Http\Controllers\InvitacionController;
use App\Http\Livewire\Invitaciones;
use App\Http\Livewire\DiagramaComponent;
use App\Models\DiagramaSecuencia;
use App\Models\Invitacion;
use Livewire\Livewire;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Livewire::component('diagrama-component', DiagramaComponent::class);
Livewire::component('invitaciones', InvitacionComponent::class);

//Ruta para el login o registro
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/diagrama/{nombre}', function ($nombre) {
    $diagrama = DiagramaSecuencia::where('nombre', $nombre)->first();

    if (!$diagrama) {
        abort(404);
    }

    // return view('livewire.diagrama-component', [
    //     'diagrama' => $diagrama,
    // ]);
    return view('diagramaSecuencia.edit');
})->name('diagrama/editar');


//Ruta controlador de los diagramas de secuencia
Route::resource('/diagramas', DiagramaSecuenciaController::class);
Route::post('/guardar-diagrama/{diagrama}', 'DiagramaSecuenciaController@guardarDiagrama')->name('guardar-diagrama');

//Rutas para la autenticacion
Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::resource('diagramaSecuencia', DiagramaSecuenciaController::class);
});

//Ruta para el evento invitacion a otro usuario
// Route::post('/diagramaSecuencia/invitar', 'DiagramaSecuenciaController@invitar')->name('diagramaSecuencia.invitar');

//Ruta para las notificaciones que me llegan
// Ruta para ver las notificaciones pendientes
Route::get('/invitaciones', Invitaciones::class)->name('invitaciones');
// Route::post('/invitaciones/enviar', [Invitaciones::class, 'enviarInvitacion'])->name('invitaciones.enviarInvitacion');

Route::post('/invitaciones', [InvitacionController::class, 'enviarInvitacion'])->name('enviar.invitacion');
