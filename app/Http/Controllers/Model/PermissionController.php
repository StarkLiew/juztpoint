<?php
namespace App\Http\Controllers\Model;
use Illuminate\Http\Request;
use App\Models\PermissionRole;
use App\Http\Controllers\Model\UserController as UC;
use App\Models\User;
use Auth;
use Route;
use Schema;
use App\Models\Role;

use App\Models\Permission;
use App\Http\Controllers\Controller;


class PermissionController extends Controller
{

    public function __construct()
    {
      

    }

    public function all(Request $request){
      $options = [];

      $roles = Role::all();
      $permissions = Permission::all();
      $roles_count =  Role::count();
      $options = compact('roles','roles_count');

      $result = Permission::with('permission_role')->all();

      return response()->json(compact('result', 'options'), 200); 
    }

   public function detail(Request $request){



       

    }

    public function update($id, Request $request){

            
   


    }







}