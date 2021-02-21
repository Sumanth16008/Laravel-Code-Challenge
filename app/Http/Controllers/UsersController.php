<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use App\Repositories\UserRepository;

class UsersController extends Controller
{
    private $userRepo;
    public function __construct(UserRepository $userRepo){
        $this->userRepo = $userRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info("UsersController:: Loading Users List");
        return view('user.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Log::info("UsersController:: Loading Create user view");
        $roles = Role::get();
        return view('user.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info("UsersController:: Storing new user: Start");
        // Validate the Field
        $this->validate($request,[
            'user_name'=>'required',
            'user_roles'=>'required'
        ]);
        
        $this->userRepo->storeUser($request->all());
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info("UsersController:: Loading Edit user view");
        $roles = Role::get();
        $user = $this->userRepo->getUser($id);
        return view('user.edit',compact('roles','user','id'));
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
        Log::info("UsersController:: Update user");
        $this->validate($request,[
            'user_name'=>'required',
            'user_roles'=>'required'
        ]);
        
        $this->userRepo->updateUser($id,$request->all());
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info("UsersController:: Delete user");
        $this->userRepo->deleteuser($id);
        return true;
    }
    
    
    public function getUsersList(Request $request){
        Log::info("UsersController:: Get Users List: Start");
        $data = $this->userRepo->getUsers($request->all());
        Log::info("UsersController:: Get Users List: End");
        return $data;
    }
}
