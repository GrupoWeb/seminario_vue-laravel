<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class MenusTableSeeder extends Seeder
{
    private $menuId = null;
    private $dropdownId = array();
    private $dropdown = false;
    private $sequence = 1;
    private $joinData = array();
    private $adminRole = null;
    private $userRole = null;

    public function join($roles, $menusId){
        $roles = explode(',', $roles);
        foreach($roles as $role){
            array_push($this->joinData, array('role_name' => $role, 'menus_id' => $menusId));
        }
    }

    /*
        Function assigns menu elements to roles
        Must by use on end of this seeder
    */
    public function joinAllByTransaction(){
        DB::beginTransaction();
        foreach($this->joinData as $data){
            DB::table('menu_role')->insert([
                'role_name' => $data['role_name'],
                'menus_id' => $data['menus_id'],
            ]);
        }
        DB::commit();
    }

    public function insertLink($roles, $name, $href, $icon = null){
        if($this->dropdown === false){
            DB::table('menus')->insert([
                'slug' => 'link',
                'name' => $name,
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'sequence' => $this->sequence
            ]);
        }else{
            DB::table('menus')->insert([
                'slug' => 'link',
                'name' => $name,
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'parent_id' => $this->dropdownId[count($this->dropdownId) - 1],
                'sequence' => $this->sequence
            ]);
        }
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        $permission = Permission::where('name', '=', $name)->get();
        if(empty($permission)){
            $permission = Permission::create(['name' => 'visit ' . $name]);
        }
        $roles = explode(',', $roles);
        if(in_array('user', $roles)){
            $this->userRole->givePermissionTo($permission);
        }
        if(in_array('admin', $roles)){
            $this->adminRole->givePermissionTo($permission);
        }
        return $lastId;
    }

    public function insertTitle($roles, $name){
        DB::table('menus')->insert([
            'slug' => 'title',
            'name' => $name,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence
        ]);
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        return $lastId;
    }

    public function beginDropdown($roles, $name, $href='', $icon=''){
        if(count($this->dropdownId)){
            $parentId = $this->dropdownId[count($this->dropdownId) - 1];
        }else{
            $parentId = null;
        }
        DB::table('menus')->insert([
            'slug' => 'dropdown',
            'name' => $name,
            'icon' => $icon,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence,
            'parent_id' => $parentId,
            'href' => $href
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        array_push($this->dropdownId, $lastId);
        $this->dropdown = true;
        $this->sequence++;
        $this->join($roles, $lastId);
        return $lastId;
    }

    public function endDropdown(){
        $this->dropdown = false;
        array_pop( $this->dropdownId );
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Get roles */
        $this->adminRole = Role::where('name' , '=' , 'admin' )->first();
        $this->userRole = Role::where('name', '=', 'user' )->first();
        $dropdownId = array();
        /* Create Sidebar menu */
        DB::table('menulist')->insert([
            'name' => 'sidebar menu'
        ]);
        $this->menuId = DB::getPdo()->lastInsertId();  //set menuId
        /* guest menu */
        $this->insertLink('guest,user,admin', 'Principal', '/', 'cil-speedometer');
        $this->insertLink('guest', 'Entrar', '/login', 'cil-account-logout');
        // $this->insertLink('guest', 'Registrar', '/register', 'cil-account-logout');
        $this->beginDropdown('admin', 'Catálogos', '/settings', 'cil-puzzle');
            $this->insertLink('admin', 'Carpetas',    '/media');
            $this->insertLink('admin', 'Usuarios',    '/users');
            $this->insertLink('admin', 'Menu',    '/menu');
            $this->insertLink('admin', 'Roles','/roles');
            $this->insertLink('admin', 'Correo Electrónico',    '/email');
            $this->insertLink('admin', 'Departamentos',    '/departamento');
            $this->insertLink('admin', 'Municipios',    '/municipio');
            // custom menu
            $this->insertLink('admin', 'Empresas',    '/empresas');
            $this->insertLink('admin', 'Sedes',    '/sede');
            $this->insertLink('admin', 'Clientes',    '/cliente');
            $this->insertLink('admin', 'Proveedores',    '/proveedores');
            $this->insertLink('admin', 'Tipo de Pago',    '/tipo-pago');
            $this->insertLink('admin', 'Tipo de Gastos',    '/tipo-gasto');
            $this->insertLink('admin', 'Medidas',    '/medidas');
            $this->insertLink('admin', 'Productos',    '/productos');
            $this->insertLink('admin', 'Formato Correlativos',    '/formato-correlativos');
            $this->insertLink('admin', 'Catalogo Vehiculo',    '/catalogo-vehiculo');
            /**************** */
        $this->endDropdown();
        // Empresa 1
        $this->beginDropdown('admin', 'TransPort extraccion', '/settings', 'cil-puzzle');
            $this->insertLink('admin', 'Producto',    '/productos');
            $this->insertLink('admin', 'Inventario',    '/inventario');
            $this->insertLink('admin', 'Requisición',    '/requisiciones');
            $this->insertLink('admin', 'Aprobación',    '/aprobacion');
            $this->insertLink('admin', 'Autorización',    '/autorizacion');
            $this->insertLink('admin', 'Despacho',    '/despacho');
            $this->insertLink('admin', 'Facturación',    '/factura');
            // $this->insertLink('admin', 'Venta',    '/venta');
            // $this->insertLink('admin', 'Pedido',    '/pedido');
            // $this->insertLink('admin', 'Despacho',    '/despacho');
            // $this->insertLink('admin', 'Gasto',    '/gasto');
        $this->endDropdown();
        // Empresa 2
        $this->beginDropdown('admin', 'TransPort planta', '/settings', 'cil-puzzle');
            $this->insertLink('admin', 'Producto',    '/productos');
            $this->insertLink('admin', 'Inventario',    '/inventario');
            $this->insertLink('admin', 'Requisicion',    '/requisiciones');
            $this->insertLink('admin', 'Aprobación',    '/aprobacion');
            $this->insertLink('admin', 'Autorización',    '/autorizacion');
            $this->insertLink('admin', 'Despacho',    '/despacho');
            $this->insertLink('admin', 'Facturación',    '/factura');
            // $this->insertLink('admin', 'Venta',    '/venta');
            // $this->insertLink('admin', 'Pedido',    '/pedido');
            // $this->insertLink('admin', 'Despacho',    '/despacho');
            // $this->insertLink('admin', 'Gasto',    '/gasto');
        $this->endDropdown();
        // Empresa 3
        $this->beginDropdown('admin', 'TransPort alquiler', '/settings', 'cil-puzzle');
            $this->insertLink('admin', 'Solicitudes',    '/producto');
            $this->insertLink('admin', 'Producto',    '/productos');
            $this->insertLink('admin', 'Inventario',    '/inventario');
            $this->insertLink('admin', 'Requisicion',    '/requisiciones');
            $this->insertLink('admin', 'Aprobación',    '/aprobacion');
            $this->insertLink('admin', 'Autorización',    '/autorizacion');
            $this->insertLink('admin', 'Despacho',    '/despacho');
            $this->insertLink('admin', 'Facturación',    '/factura');
            // $this->insertLink('admin', 'Venta',    '/venta');
            // $this->insertLink('admin', 'Pedido',    '/pedido');
            // $this->insertLink('admin', 'Despacho',    '/despacho');
            // $this->insertLink('admin', 'Gasto',    '/gasto');
        $this->endDropdown();
        // Empresa 4
        $this->beginDropdown('admin', 'TransPort cemex', '/settings', 'cil-puzzle');
            $this->insertLink('admin', 'Solicitudes',    '/producto');
            $this->insertLink('admin', 'Producto',    '/productos');
            $this->insertLink('admin', 'Inventario',    '/inventario');
            $this->insertLink('admin', 'Requisicion',    '/requisiciones');
            $this->insertLink('admin', 'Aprobación',    '/aprobacion');
            $this->insertLink('admin', 'Autorización',    '/autorizacion');
            $this->insertLink('admin', 'Despacho',    '/despacho');
            $this->insertLink('admin', 'Facturación',    '/factura');
            // $this->insertLink('admin', 'Venta',    '/venta');
            // $this->insertLink('admin', 'Pedido',    '/pedido');
            // $this->insertLink('admin', 'Despacho',    '/despacho');
            // $this->insertLink('admin', 'Gasto',    '/gasto');
        $this->endDropdown();
        // Empresa 5
        $this->beginDropdown('admin', 'Multi TransPort ', '/settings', 'cil-puzzle');
            $this->insertLink('admin', 'Solicitudes',    '/producto');
            $this->insertLink('admin', 'Producto',    '/productos');
            $this->insertLink('admin', 'Inventario',    '/inventario');
            $this->insertLink('admin', 'Requisicion',    '/requisiciones');
            $this->insertLink('admin', 'Aprobación',    '/aprobacion');
            $this->insertLink('admin', 'Autorización',    '/autorizacion');
            $this->insertLink('admin', 'Despacho',    '/despacho');
            $this->insertLink('admin', 'Facturación',    '/factura');
            // $this->insertLink('admin', 'Venta',    '/venta');
            // $this->insertLink('admin', 'Pedido',    '/pedido');
            // $this->insertLink('admin', 'Despacho',    '/despacho');
            // $this->insertLink('admin', 'Gasto',    '/gasto');
        $this->endDropdown();
        // Empresa 6
        $this->beginDropdown('admin', 'TransPort Constructora', '/settings', 'cil-puzzle');
            $this->insertLink('admin', 'Proyectos',    '/proyecto');
            $this->insertLink('admin', 'Producto',    '/productos');
            $this->insertLink('admin', 'Inventario',    '/inventario');
            $this->insertLink('admin', 'Requisicion',    '/requisiciones');
            $this->insertLink('admin', 'Aprobación',    '/aprobacion');
            $this->insertLink('admin', 'Autorización',    '/autorizacion');
            $this->insertLink('admin', 'Despacho',    '/despacho');
            $this->insertLink('admin', 'Facturación',    '/factura');
            // $this->insertLink('admin', 'Venta',    '/venta');
            // $this->insertLink('admin', 'Pedido',    '/pedido');
            // $this->insertLink('admin', 'Despacho',    '/despacho');
            // $this->insertLink('admin', 'Gasto',    '/gasto');
        $this->endDropdown();
        $this->joinAllByTransaction(); ///   <===== Must by use on end of this seeder
    }
}
