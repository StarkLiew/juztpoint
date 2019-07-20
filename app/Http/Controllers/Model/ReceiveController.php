<?php
namespace App\Http\Controllers\Model;
use Illuminate\Http\Request;
use App\Models\Receive;
use App\Http\Controllers\Model\UserController as UC;
use DB;
use Illuminate\Validation\Rule;
use Auth;
use Route;
use Log;
use Schema;
use App\Http\Controllers\Controller;



class ReceiveController extends Controller
{

    public function __construct()
    {


    }

    public function create(Request $request){


        $this->validate($request, [
              'account_id' => 'required',
              'date' => 'required|date',
              'details.*.product_id' => 'required',
              //'details.*.appointment_id' => ['nullable','exists:invoice_details,appointment_id'],
 
    
        ]);

        $data = new Receive($request->all());
        $data->user_id = Auth::user()->id;
           
            try{
   
              $data->save();
              DB::beginTransaction();
              if($request->has('details')){
                $inputs = $request->input('details');
                $data->details()->createMany($inputs);
              }


              $data->load('supplier', 'details', 'user');
              $data->details->load('user', 'product');


              DB::commit();

              return response()->json(['msg'=>'Saved', 'data'=>$data], 200);

            } catch(\PDOException $e){
               DB::rollback();
              return response()->json(['msg'=>'Fail', 'data'=>$e->getMessage()], 400);
            }
          



    }

    public function all(Request $request){

      $option = ['by'=>'id', 'order'=>'desc', 'paging'=>25, 'page'=>1];
      $selectedgroup = '';
      $filtername = '';
      $parent = '';
      $modal = '';
      $status = '';
      $title = 'Receives';



      if ($request->has('page')) $option['page'] = $request->input('page');
      if ($request->has('by')) $option['by'] = $request->input('by');
      if ($request->has('order')) $option['order'] = $request->input('order');
      if ($request->has('filtername')) $filtername = $request->input('filtername');
      if ($request->has('group')) $selectedgroup = $request->input('group');
      if ($request->has('status')) $selectedgroup = $request->input('status');
      $model = Receive::orderBy($option['by'], $option['order']);

      //if($filtername !== '') $model = $model->where('id', 'like', '%'.$filtername.'%');
      if($status !== '') $model = $model->where('status', $status);
      $model->with(['details' => function($query){$query->with(['product', 'user']);},'supplier', 'user']);
      $result  = $model->paginate($option['paging']);
      
      
                  
      return response()->json(compact('title','modal','parent','option','selectedgroup','filtername','result', 'options','dates'), 200);
    }

   public function detail($id, Request $request){




      if(!empty($id)){

         $users = \App\Models\User::all();
         $discountTypes = \App\Models\DiscountType::all();
         $taxRates = \App\Models\TaxRate::all();
         $products = \App\Models\Price::whereHas('product', function($query){ $query->where('service', false); })->with(['product'=>function($query){ $query->with('category', 'taxRate')->where('service', false);  }])->get();
         $job = \App\Models\Product::with('category', 'taxRate')->where('id', 1)->first();
         $default = \App\Models\Company::getDefault();
         $options = compact('discountTypes','taxRates','users', 'products','job','default');
         $dates = ['date'];
         
          if($id === 'new') {
            $result = [];
            return response()->json(compact('result', 'id','options'), 200);
          }

          $result = Receive::where("id", "=", $id)->with(['supplier', 'user', 'details' => function($query){
                    $query->with(['product','user']); }])->first();
          

     
          return response()->json(compact('result', 'id', 'options', 'dates'), 200, [], JSON_NUMERIC_CHECK);
      } else {
         return response()->json(['msg'=>'Not found'], 401);
      }         

    }

    public function update($id, Request $request){
      
        $data = Receive::find($id);
        if(!$data){ 
            return response()->json(['msg'=>'Not found'], 400);
        }

        $this->validate($request, [
              'account_id' => 'required',
              'date' => 'required|date',
              'details.*.product_id' => 'required',
        ]);
          
           DB::beginTransaction();
           
           try{
 
              if($request->has('details')){
                $inputs = $request->input('details');
                $data->details()->forceDelete();
                $data->details()->createMany($inputs);
              }

         
             $data->touch();
             $data->update($request->all());
             $data->load('supplier', 'details', 'user');
             $data->details->load('user', 'product');
             DB::commit();
             return response()->json(['msg'=>'Saved', 'data'=>$data], 200);

            } catch(\PDOException $e){
              DB::rollback();
              return response()->json(['msg'=>'Fail', 'data'=>$data, 'err'=> $e] , 400);
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
         $data = Receive::find($id);
          if ($data !== null) {
        
           if($data->delete()){
              $data->details()->delete();
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
             $affectedRows = Receive::onlyTrashed()->forceDelete();
             if($affectedRows){
                return response()->json(['msg'=> $affectedRows." affected and unrecoverable."]);
             }

          } else {
            return response()->json(['msg'=> "Fail to validate user."]);
          }

       }
    }

}