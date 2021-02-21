<?php 
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\UsersRole;
use Illuminate\Database\QueryException;

class UserRepository{
    
    private $user;
    public function __construct(User $user){
        $this->user = $user;
    }
    
    /**
     * Get Users
     * @param $params
     */
    public function getUsers($params){
        Log::info("UserRepository:: Get Users: Start");
        
        $search = !empty($params['search']['value'])?$params['search']['value']:'';
        $pageSize = !empty($params['length']) ? $params['length'] : 10;
        $start = isset($params['start']) ? $params['start'] : 0;
        $count_filter =$count_total= 0;
        DB::enableQueryLog();
        $users_query = User::leftjoin('users_role as ur','users.id','=','ur.user_id')
            ->leftjoin('roles as r', 'ur.role_id','=','r.id')
            ->leftjoin('role_has_permissions as rps','rps.role_id','=','r.id')
            ->leftjoin('permissions as p','p.id','=','rps.permission_id')
            ->select('users.id', 'users.name',DB::raw('string_agg(distinct r.name, \', \') as roles'),DB::raw('string_agg(p.name,\', \') as permissions'))
            ->groupBy('users.id');
        
            $count_total = DB::table( DB::raw("({$users_query->toSql()}) as sub") )
                                ->mergeBindings($users_query->getQuery()) 
                                ->count();
//         dd(DB::getQueryLog());
        if($search != ''){
            $users_query->Where(function ($query) use ($search) {
                $query->orWhere( 'users.name' , 'iLIKE' , '%'.$search.'%');
                $query->orWhere( 'r.name' , 'iLIKE' , '%'.$search.'%');
                $query->orWhere( 'p.name' , 'iLIKE' , '%'.$search.'%');
            });
                $count_filter = DB::table( DB::raw("({$users_query->toSql()}) as sub") )
                                    ->mergeBindings($users_query->getQuery())
                                    ->count();
        }
        
        
        $user_data=$users_query->skip($start)->take($pageSize)->get();
//         dd($user_data);
        if($count_filter == 0 && $search == ''){
            $count_filter = $count_total;
        }
        
        
        $data = array(  "recordsTotal" => $count_total,
            "recordsFiltered" => $count_filter,
            "recordsFetched" => count($user_data),
            "data" => $user_data
        );
        Log::info("UserRepository:: Get Users: End");
        return $data;
    }
    
    public function storeUser($data){
        try{
            Log::info("UserRepository:: Store User: Start");
            $user_data = $user_role = [];
            DB::beginTransaction();
            $user_data['name']= $data['user_name'];
            
            $user_id = User::insertGetId($user_data);
            
            foreach($data['user_roles'] as $key=>$role){
                $user_role[$key]['user_id']=$user_id;
                $user_role[$key]['role_id']=$role;
            }
            UsersRole::insert($user_role);
            DB::commit();
            Log::info("UserRepository:: Store User: End");
        }catch(QueryException $e){
            Log::error("UserRepository:: Store User: Error - ".$e->getMessage());
            DB::rollback();
        }
    }
    
    public function getUser($id){
        $data = [];
        $data['user_name'] = User::where('id',$id)->value('name');
        $user_roles = UsersRole::where('user_id',$id)->pluck('role_id');
        $data['user_roles'] = json_decode(json_encode($user_roles), true);
        return $data; 
    }
    
    public function updateUser($id,$data){
        try{
            Log::info("UserRepository:: Store User: Start");
            $user_data = $user_role = [];
            DB::beginTransaction();
            $user_data['name']= $data['user_name'];
            
            User::where('id',$id)->update($user_data);
            
            UsersRole::where('user_id',$id)->delete();
            
            foreach($data['user_roles'] as $key=>$role){
                $user_role[$key]['user_id']=$id;
                $user_role[$key]['role_id']=$role;
            }
            UsersRole::insert($user_role);
            DB::commit();
            Log::info("UserRepository:: Store User: End");
        }catch(QueryException $e){
            Log::error("UserRepository:: Store User: Error - ".$e->getMessage());
            DB::rollback();
        }
    }
    
    public function deleteuser($id){
        try{
            Log::info("UserRepository:: Delete User: Start");
            DB::beginTransaction();
            UsersRole::where('user_id',$id)->delete();
            User::where('id',$id)->delete();
            DB::commit();
            Log::info("UserRepository:: Delete User: End");
        }catch(QueryException $e){
            Log::error("UserRepository:: Store User: Error - ".$e->getMessage());
            DB::rollback();
        }
    }
}