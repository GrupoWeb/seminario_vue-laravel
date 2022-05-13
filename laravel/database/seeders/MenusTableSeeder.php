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
        $this->insertLink('guest,user,admin', 'Dashboard', '/', 'cil-speedometer');
        $this->insertLink('guest', 'Login', '/login', 'cil-account-logout');
        $this->insertLink('guest', 'Registrar', '/register', 'cil-account-logout');
        $this->beginDropdown('admin', 'Settings', '/settings', 'cil-puzzle');
            $this->insertLink('admin', 'Media',    '/media');
            $this->insertLink('admin', 'Users',    '/users');
            $this->insertLink('admin', 'Menu',    '/menu');
            $this->insertLink('admin', 'roles','/roles');
            $this->insertLink('admin', 'BREAD',    '/bread');
            $this->insertLink('admin', 'Email',    '/email');
        $this->endDropdown();
        // $this->insertTitle('user,', 'Theme');
        // $this->insertLink('user,', 'Colors', '/colors', 'cil-drop');
        // $this->insertLink('user,', 'Typography', '/typography', 'cil-pencil');
        // $this->insertTitle('user,', 'Components');
        // $this->beginDropdown('user,', 'Base', '/base', 'cil-puzzle');
        //     $this->insertLink('user,', 'Breadcrumb',    '/base/breadcrumb');
        //     $this->insertLink('user,', 'Cards',         '/base/cards');
        //     $this->insertLink('user,', 'Carousel',      '/base/carousel');
        //     $this->insertLink('user,', 'Collapse',      '/base/collapse');
        //     $this->insertLink('user,', 'Forms',         '/base/forms');
        //     $this->insertLink('user,', 'Jumbotron',     '/base/jumbotron');
        //     $this->insertLink('user,', 'List group',    '/base/list-group');
        //     $this->insertLink('user,', 'Navs',          '/base/navs');
        //     $this->insertLink('user,', 'Pagination',    '/base/pagination');
        //     $this->insertLink('user,', 'Popovers',      '/base/popovers');
        //     $this->insertLink('user,', 'Progress',      '/base/progress');
        //    // $this->insertLink('user,admin', 'Scrollspy',     '/base/scrollspy');  
        //     $this->insertLink('user,', 'Switches',      '/base/switches');
        //     $this->insertLink('user,', 'Tables',        '/base/tables');
        //     $this->insertLink('user,', 'Tabs',          '/base/tabs');
        //     $this->insertLink('user,', 'Tooltips',      '/base/tooltips');
        // $this->endDropdown();
        // $this->beginDropdown('user,', 'Buttons', '/buttons', 'cil-cursor');
        //     $this->insertLink('user,', 'Buttons',           '/buttons/buttons');
        //     $this->insertLink('user,', 'Buttons Group',     '/buttons/button-group');
        //     $this->insertLink('user,', 'Dropdowns',         '/buttons/dropdowns');
        //     $this->insertLink('user,', 'Brand Buttons',     '/buttons/brand-buttons');
        // $this->endDropdown();
        // $this->insertLink('user,', 'Charts', '/charts', 'cil-chart-pie');
        // $this->beginDropdown('user,', 'Icons', '/icon', 'cil-star');
        //     $this->insertLink('user,', 'CoreUI Icons',      '/icon/coreui-icons');
        //     $this->insertLink('user,', 'Flags',             '/icon/flags');
        //     $this->insertLink('user,', 'Brands',            '/icon/brands');
        // $this->endDropdown();
        // $this->beginDropdown('user,', 'Notifications', '/notifications', 'cil-bell');
        //     $this->insertLink('user,', 'Alerts',     '/notifications/alerts');
        //     $this->insertLink('user,', 'Badge',      '/notifications/badge');
        //     $this->insertLink('user,', 'Modals',     '/notifications/modals');
        // $this->endDropdown();
        // $this->insertLink('user,', 'Widgets', '/widgets', 'cil-calculator');
        // $this->insertTitle('user,', 'Extras');
        // $this->beginDropdown('user,', 'Pages', '/pages', 'cil-star');
        //     $this->insertLink('user,', 'Login',         '/login');
        //     $this->insertLink('user,', 'Register',      '/register');
        //     $this->insertLink('user,', 'Error 404',     '/404');
        //     $this->insertLink('user,', 'Error 500',     '/500');
        // $this->endDropdown();
        // $this->insertLink('guest,user,', 'Download CoreUI', 'https://coreui.io', 'cil-cloud-download');
        // $this->insertLink('guest,user,', 'Try CoreUI PRO', 'https://coreui.io/pro/', 'cil-layers');

        /* Create top menu */
        // DB::table('menulist')->insert([
        //     'name' => 'top_menu'
        // ]);
        // $this->menuId = DB::getPdo()->lastInsertId();  //set menuId
        // $this->beginDropdown('guest,user,admin', 'Pages');
        //     $this->insertLink('guest,user,admin', 'Dashboard',    '/');
        //     $this->insertLink('user,admin', 'Notes',              '/notes');
        //     $this->insertLink('admin', 'Users',                   '/users');
        // $this->endDropdown();
        // $this->beginDropdown('admin', 'Settings');
        //     $this->insertLink('admin', 'Edit menu',               '/menu');
        //     $this->insertLink('admin', 'Edit roles',              '/roles');
        //     $this->insertLink('admin', 'Media',                   '/media');
        //     $this->insertLink('admin', 'BREAD',                   '/bread');
        //     $this->insertLink('admin', 'E-mail',                  '/email');
        // $this->endDropdown();

        $this->joinAllByTransaction(); ///   <===== Must by use on end of this seeder
    }
}
