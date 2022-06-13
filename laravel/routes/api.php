<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('menu', 'MenuController@index');

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('register', 'AuthController@register'); 


    // router custom
    Route::get('getRoles','RolesController@getRoles');
    Route::post('createUser','UsersController@createUser');

    Route::get('departamentos','CustomController@getDepartamentos');
    Route::post('departamentoById','CustomController@getDepartamentosById');
    Route::post('updateDepartamenoById','CustomController@updateDepartament');
    Route::post('deleteDepartament','CustomController@deleteDepartament');

    Route::get('municipios','CustomController@getMunicipios');
    Route::post('municipioById','CustomController@getMunicipiosById');
    Route::post('updateMunicipioById','CustomController@updateMunicipio');
    Route::post('deleteMunicipio','CustomController@deleteMunicipio');

    Route::get('getSedes','CustomController@getSede');
    Route::post('setSede', 'CustomController@setSede');
    Route::post('getMunicipioByIdSede','CustomController@getMunicipioByIdSede');
    Route::post('getSedeById','CustomController@getSedeById');
    Route::post('updateSedeById','CustomController@updateSedeById');
    Route::put('deleteSedeById', 'CustomController@deleteSedeById');

    Route::get('getEmpresas','CustomController@getEmpresas');
    Route::post('setEmpresa','CustomController@setEmpresa');
    Route::post('getEmpresaById', 'CustomController@getEmpresaById');
    Route::post('updateEmpresa','CustomController@updateEmpresa');
    Route::put('deleteEmpresaById', 'CustomController@deleteEmpresaById');
    Route::get('associateSedes','CustomController@associateSedes');
    Route::get('associateEmpresas','CustomController@associateEmpresas');
    Route::post('associateSedeEmpresa','CustomController@associateSedeEmpresa');
    Route::post('getEmpresasAsociadas','CustomController@getEmpresasAsociadas');

    Route::get('getTipoPago','CustomController@getTipoPago');
    Route::post('setTipoPago','CustomController@setTipoPago');
    Route::post('getTipoById','CustomController@getTipoById');
    Route::put('updateTipoPagoById','CustomController@updateTipoPagoById');
    Route::put('deleteTipoById','CustomController@deleteTipoById');
    
    Route::get('getTipoGasto','CustomController@getTipoGasto');
    Route::post('setTipoGasto','CustomController@setTipoGasto');
    Route::post('getTipoGastoById','CustomController@getTipoGastoById');
    Route::put('deleteTipoGastoById','CustomController@deleteTipoGastoById');
    Route::put('updateTipoGastoById','CustomController@updateTipoGastoById');

    Route::get('getMedida','CustomController@getMedida');
    Route::post('setMedida','CustomController@setMedida');
    Route::post('getMedidaById','CustomController@getMedidaById');
    Route::put('deleteMedidaById','CustomController@deleteMedidaById');
    Route::put('updateMedidaById','CustomController@updateMedidaById');

    Route::get('getStringCorrelativo','CustomController@getStringCorrelativo');
    Route::post('setStringCorrelativo','CustomController@setStringCorrelativo');
    Route::post('getStringCorrelativoById','CustomController@getStringCorrelativoById');
    Route::put('deleteStringCorrelativoById','CustomController@deleteStringCorrelativoById');
    Route::put('updateStringCorrelativoById','CustomController@updateStringCorrelativoById');

    Route::post('setCorrelativoInitial', 'CustomController@setCorrelativoInitial');
    Route::post('getData', 'CustomController@getData');
    Route::post('setData', 'CustomController@setData');
    Route::post('updateData', 'CustomController@updateData');
    Route::post('getDataById', 'CustomController@getDataById');
    Route::put('getUpdateDataById', 'CustomController@getUpdateDataById');
    Route::put('getDeleteDataById', 'CustomController@getDeleteDataById');

    Route::post('setProveedores','CustomController@setProveedores');
    Route::get('getProveedores','CustomController@getProveedores');
    Route::post('getProveedoresById','CustomController@getProveedoresById');
    Route::put('updateProveedores','CustomController@updateProveedores');
    Route::put('deleteProveedores','CustomController@deleteProveedores');

    Route::get('getClientes','CustomController@getClientes');
    Route::post('setClientes','CustomController@setClientes');
    Route::post('getClienteById','CustomController@getClienteById');
    Route::put('deleteClientes','CustomController@deleteClientes');

    Route::get('getProductos','CustomController@getProductos');
    Route::get('getMedidas','CustomController@getMedidas');
    Route::get('getSucursal','CustomController@getSucursal');
    Route::post('setInventario','CustomController@setInventario');

    Route::get('getInventario','CustomController@getInventario');
    Route::put('updateStock','CustomController@updateStock');
    Route::put('deleteStock','CustomController@deleteStock');

    Route::get('listProductosInventario','CustomController@listProductosInventario');
    Route::post('findProducto','CustomController@findProducto');
    Route::post('setRequisicion','CustomController@setRequisicion');
    Route::get('cargarMisRequisiciones','CustomController@cargarMisRequisiciones');
    Route::get('cargarrequisicionesAprobar','CustomController@cargarrequisicionesAprobar');
    Route::post('RequisicionesAprobarInfo','CustomController@RequisicionesAprobarInfo');
    Route::post('aprobarRequisicion','CustomController@aprobarRequisicion');
    Route::put('rechazarRequisicion','CustomController@rechazarRequisicion');

    Route::get('cargarrequisicionesAutorizar','CustomController@cargarrequisicionesAutorizar');
    Route::get('cargarrequisicionesDespacho','CustomController@cargarrequisicionesDespacho');
    Route::post('autorizarRequisicion','CustomController@autorizarRequisicion');
    Route::post('despacharRequisicion','CustomController@despacharRequisicion');

    Route::post('pdf','CustomController@pdfDespacho');
    Route::get('generateCorrelativo','CustomController@generateCorrelativo');
    Route::get('getString','CustomController@getString');
    Route::post('cargarItems','CustomController@cargarItems');
    Route::post('setFactura','CustomController@setFactura');

    Route::post('contactanos', 'MailController@sendContacto');
    Route::get('getEmpresasUser', 'CustomController@getEmpresasUser');
    // Route::post('contactanos/{id}', 'MailController@send');
    


    /*********************************** */

    Route::resource('resource/{table}/resource', 'ResourceController');
    
    Route::group(['middleware' => 'admin'], function ($router) {
        Route::resource('users', 'UsersController')->except( ['create', 'store'] );

        Route::resource('mail',        'MailController');
        Route::get('prepareSend/{id}', 'MailController@prepareSend')->name('prepareSend');
        Route::post('mailSend/{id}',   'MailController@send')->name('mailSend');

        Route::resource('bread',  'BreadController');   //create BREAD (resource)


        Route::prefix('menu/menu')->group(function () { 
            Route::get('/',         'MenuEditController@index')->name('menu.menu.index');
            Route::get('/create',   'MenuEditController@create')->name('menu.menu.create');
            Route::post('/store',   'MenuEditController@store')->name('menu.menu.store');
            Route::get('/edit',     'MenuEditController@edit')->name('menu.menu.edit');
            Route::post('/update',  'MenuEditController@update')->name('menu.menu.update');
            Route::get('/delete',   'MenuEditController@delete')->name('menu.menu.delete');
        });
        Route::prefix('menu/element')->group(function () { 
            Route::get('/',             'MenuElementController@index')->name('menu.index');
            Route::get('/move-up',      'MenuElementController@moveUp')->name('menu.up');
            Route::get('/move-down',    'MenuElementController@moveDown')->name('menu.down');
            Route::get('/create',       'MenuElementController@create')->name('menu.create');
            Route::post('/store',       'MenuElementController@store')->name('menu.store');
            Route::get('/get-parents',  'MenuElementController@getParents');
            Route::get('/edit',         'MenuElementController@edit')->name('menu.edit');
            Route::post('/update',      'MenuElementController@update')->name('menu.update');
            Route::get('/show',         'MenuElementController@show')->name('menu.show');
            Route::get('/delete',       'MenuElementController@delete')->name('menu.delete');
        });
        Route::prefix('media')->group(function ($router) {
            Route::get('/',                 'MediaController@index')->name('media.folder.index');
            Route::get('/folder/store',     'MediaController@folderAdd')->name('media.folder.add');
            Route::post('/folder/update',   'MediaController@folderUpdate')->name('media.folder.update');
            Route::get('/folder',           'MediaController@folder')->name('media.folder');
            Route::post('/folder/move',     'MediaController@folderMove')->name('media.folder.move');
            Route::post('/folder/delete',   'MediaController@folderDelete')->name('media.folder.delete');;

            Route::post('/file/store',      'MediaController@fileAdd')->name('media.file.add');
            Route::get('/file',             'MediaController@file');
            Route::post('/file/delete',     'MediaController@fileDelete')->name('media.file.delete');
            Route::post('/file/update',     'MediaController@fileUpdate')->name('media.file.update');
            Route::post('/file/move',       'MediaController@fileMove')->name('media.file.move');
            Route::post('/file/cropp',      'MediaController@cropp');
            Route::get('/file/copy',        'MediaController@fileCopy')->name('media.file.copy');

            Route::get('/file/download',    'MediaController@fileDownload');
        });

        Route::resource('roles',        'RolesController');
        Route::get('/roles/move/move-up',      'RolesController@moveUp')->name('roles.up');
        Route::get('/roles/move/move-down',    'RolesController@moveDown')->name('roles.down');
    });
});

