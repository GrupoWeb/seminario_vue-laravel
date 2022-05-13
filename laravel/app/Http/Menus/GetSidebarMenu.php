<?php
/*
*   07.11.2019
*   MenusMenu.php
*/
namespace App\Http\Menus;

use App\MenuBuilder\MenuBuilder;
use Illuminate\Support\Facades\DB;
use App\Models\Menus;
use App\Models\userHasRoles;
use Illuminate\Support\Arr;
use App\MenuBuilder\RenderFromDatabaseData;

class GetSidebarMenu implements MenuInterface{

    private $mb; //menu builder
    private $menu;

    public function __construct(){
        $this->mb = new MenuBuilder();
    }

    private function getMenuFromDB($menuName, $role){
        $this->menu = Menus::join('menu_role', 'menus.id', '=', 'menu_role.menus_id')
            ->join('menulist', 'menulist.id', '=', 'menus.menu_id')
            ->select('menus.*')
            ->distinct()
            ->where('menulist.name', '=', $menuName)
            ->whereRaw('menu_role.role_name in ('. DB::raw("SELECT r.name FROM user_has_roles s INNER JOIN roles r 	ON r.id = s.role_id WHERE users_id = ". $role ."") . ')')
            ->orderBy('menus.sequence', 'asc')->get();       
    }

    private function getGuestMenu($menuName){
        $this->getMenuFromDB($menuName, 'guest');
    }

    private function getUserMenu($menuName){
        $this->getMenuFromDB($menuName, 'user');
    }

    private function getAdminMenu($menuName){
        $this->getMenuFromDB($menuName, 'admin');
    }

    // roles prueba 2

    public function getCustomMenu($menuName, $roles){
        $this->getMenuFromDB($menuName, $roles);           
        
    }

    /******************************* */

    public function get($roles, $menuName = 'sidebar menu'){
        // $roles = explode(',', $roles);
        if(empty($roles)){
            $this->getGuestMenu($menuName);
        }else{
            $this->getCustomMenu($menuName, $roles);
        }
        // if(empty($roles)){
        //     $this->getGuestMenu($menuName);
        // }elseif(in_array('admin', $roles)){
        //     $this->getAdminMenu($menuName);
        // }elseif(in_array('user', $roles)){
        //     $this->getUserMenu($menuName);
        // }elseif(in_array('empresa1', $roles)){
        //     $this->getPruebaMenu($menuName);
        // }else{
        //     $this->getGuestMenu($menuName);
        // }
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }

    public function getAll( $menuId = 1 ){
        $this->menu = Menus::select('menus.*')
            ->where('menus.menu_id', '=', $menuId)
            ->orderBy('menus.sequence', 'asc')->get();  
        $rfd = new RenderFromDatabaseData;
        return $rfd->render($this->menu);
    }
}