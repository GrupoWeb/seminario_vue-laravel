<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\userHasRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $you = auth()->user()->id;
        $users = DB::table('users')
        ->select('users.id', 'users.name as nombre', 'users.email',  'status.name as estado', 'users.email_verified_at as registro')
        ->join('status','status.id','=','users.status_id')
        ->whereNull('deleted_at')
        ->get();
        return response()->json( compact('users', 'you') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = DB::table('users')
        ->select('users.id', 'users.name', 'users.email',  'status.name as estado', 'users.email_verified_at as registered')
        ->join('status','status.id','=','users.status_id')
        ->where('users.id', '=', $id)
        ->first();
        return response()->json( $user );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = DB::table('users')
        ->select('users.id', 'users.name', 'users.email',  'users.status_id')
        ->where('users.id', '=', $id)
        ->first();
        return response()->json( $user );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            'email'      => 'required|email|max:256'
        ]);
        $user = User::find($id);
        $user->name       = $request->input('name');
        $user->email      = $request->input('email');
        $user->save();
        //$request->session()->flash('message', 'Successfully updated user');
        return response()->json( ['status' => 'success'] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user){
            $user->update(['status_id'   => 2]);
            $user->delete();
        }
        return response()->json( ['status' => 'success'] );
    }

    public function createUser(Request $request){
        try {
            DB::beginTransaction();

            $user = User::create([
                'name'              =>  $request->name,
                'email'             =>  $request->email,
                'password'          =>  Hash::make($request->password),
                'status_id'         =>  1
            ]);

            $roleName = DB::table('roles')->select('name')->where(['id'   =>  $request->role_id])->first();


            $user->assignRole($roleName->name);

            userHasRoles::create([
                'role_id'   =>  $request->role_id,
                'users_id'  =>  $user->id
            ]);

            DB::commit();

            return response()->json($user,Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['error' => 'usuario no creado ' . $th], Response::HTTP_BAD_REQUEST);
        }
    }
}
