<?php
namespace App\Http\Controllers\Model;
use Illuminate\Http\Request;
use App\Models\ConfigNotification;
use App\Http\Controllers\Model\UserController as UC;
use Auth;
use Route;
use Schema;
use App\Http\Controllers\Controller;


class ConfigNotificationController extends Controller
{

   public function __construct()
    {
      
    }

    public function create(Request $request){ 

        $this->validate($request, [
              'name' => 'required|min:2|max:255',
         ]);

        $data = new ConfigNotification($request->all());
        $data->user_id = Auth::user()->id;
          
        if($data->save()){
           return response()->json(['msg'=>'Saved', 'data'=>$data], 200);
        } else {
           return response()->json(['msg'=>'Fail', 'data'=>[]], 400);
        }

    }

    public function all(Request $request){

      $option = ['by'=>'name', 'order'=>'asc', 'paging'=>25, 'page'=>1];
      $selectedgroup = '';
      $filtername = '';
      $parent = '';
      $modal = '';
      $title = 'Config Notification';

      if ($request->has('page')) $option['page'] = $request->input('page');
      if ($request->has('by')) $option['by'] = $request->input('by');
      if ($request->has('order')) $option['order'] = $request->input('order');
      if ($request->has('filtername')) $filtername = $request->input('filtername');
      if ($request->has('group')) $selectedgroup = $request->input('group');
      
      $model = \App\Models\ConfigNotification::orderBy($option['by'], $option['order']);
         if($filtername !== '') $model = $model->where('name', 'like', '%'.$filtername.'%');
         $result  = $model->paginate($option['paging']);

                  
      return response()->json(compact('title','modal','parent','option','selectedgroup','filtername','result'), 200);
    }

   public function detail(Request $request){


          $result = ConfigNotification::first();
          return response()->json(compact('result', 'id'), 200);
       

    }

    public function update($id, Request $request){

            
        $data = ConfigNotification::first();

        if(!$data){ 
            return response()->json(['msg'=>'Not found'], 400);
        }



        if($data->update($request->all())){
           return response()->json(['msg'=>'Saved', 'data'=>$data], 200);
        } else {
           return response()->json(['msg'=>'Fail', 'data'=>[]], 400);
        }


    }


    public function trash($id, Request $request){
      if($request->has('password')){
            if(!Auth::attempt(['email' => Auth::user()->email, 'password' => $request->input('password')])){
                return response()->json(['msg'=>'Unauthorised'], 401);
            }
      } else {
        return response()->json(['msg'=>'Bad Request'], 400);   
      }

      if(!empty($id)){
         $data = ConfigNotification::find($id);
          if ($data !== null) {
        
           if($data->delete()){
              return response()->json(['msg'=>'Deleted'], 200);   
            } else {
              return response()->json(['msg'=>'Internal Error'], 500);   
            }
          } else  { 
            return response()->json(['msg'=>'Not found'], 401);
          }
      } else {
        return response()->json(['msg'=>'Bad Request'], 400);   
      }
    }

  public function destroy(Request $request){

       if ($request->has('password')) {
          if (UC::isCurrentUser($request->input('password'))) {
             $affectedRows = ConfigNotification::onlyTrashed()->forceDelete();
             if($affectedRows){
                return response()->json(['msg'=> $affectedRows." affected and unrecoverable."]);
             }

          } else {
            return response()->json(['msg'=> "Fail to validate user."]);
          }

       }



    }





}