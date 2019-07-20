<?php
namespace App\Http\Controllers\Model;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Http\Controllers\Model\UserController as UC;
use DB;
use Illuminate\Validation\Rule;
use Auth;
use Route;
use Log;
use Schema;
use App\Http\Controllers\Controller;



class PaymentController extends Controller
{

    public function __construct()
    {


    }


    public function create(Request $request){


        $this->validate($request, [
              'invoice_id' => 'required',
              'pay_date' => 'required|date',
              'pay_type_id' => 'required',
              'amount' => 'required',
              //'details.*.appointment_id' => ['nullable','exists:invoice_details,appointment_id'],
 
    
        ]);

        $data = new Payment($request->all());
        $data->user_id = Auth::user()->id;
        $data->reference = sprintf('%03d', $data->invoice_id) . date("ymdHis", strtotime($data->pay_date)) .  sprintf('%03d',   $data->user_id) . " ";
            try{
   
              $data->save();
              DB::beginTransaction();

              $data->load('paymentType', 'user', 'invoice', 'invoice.payments');

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
      $title = 'Payments';



      if ($request->has('page')) $option['page'] = $request->input('page');
      if ($request->has('by')) $option['by'] = $request->input('by');
      if ($request->has('order')) $option['order'] = $request->input('order');
      if ($request->has('filtername')) $filtername = $request->input('filtername');
      if ($request->has('group')) $selectedgroup = $request->input('group');
      if ($request->has('status')) $selectedgroup = $request->input('status');
      $model = Payment::orderBy($option['by'], $option['order']);

      //if($filtername !== '') $model = $model->where('id', 'like', '%'.$filtername.'%');
      if($status !== '') $model = $model->where('status', $status);
      $model->with(['invoice','paymentType', 'user']);
      $result  = $model->paginate($option['paging']);
      
      
                  
      return response()->json(compact('title','modal','parent','option','selectedgroup','filtername','result', 'options','dates'), 200);
    }

   public function detail($id, Request $request){




      if(!empty($id)){

         $paymentTypes = \App\Models\PaymentType::all();
         $default = \App\Models\Company::getDefault();
         $options = compact('paymentTypes','default');
         $dates = ['pay_date'];
         
          if($id === 'new') {
            $result = [];
            return response()->json(compact('result', 'id','options'), 200);
          }

          $result = Payment::where("id", "=", $id)->with(['user', 'invoice', 'invoice.customer', 'invoice.details', 'invoice.details.product', 'invoice.details.appointment', 'invoice.details.appointment.details','invoice.details.appointment.details.product','invoice.details.appointment.details.user', 'invoice.details.appointment.details.assistant','invoice.details.appointment.customer' ])->first();

          return response()->json(compact('result', 'id', 'options', 'dates'), 200, [], JSON_NUMERIC_CHECK);
      } else {
         return response()->json(['msg'=>'Not found'], 401);
      }         

    }

    public function update($id, Request $request){
      
        $data = Payment::find($id);
        if(!$data){ 
            return response()->json(['msg'=>'Not found'], 400);
        }

        $this->validate($request, [
               'invoice_id' => 'required',
              'pay_date' => 'required|date',
              'pay_type_id' => 'required',
              'amount' => 'required',
        ]);
          
           DB::beginTransaction();
           
           try{
 
 
             $data->touch();
             $data->update($request->all());
             $data->load('paymentType', 'user', 'invoice', 'invoice.payments');
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
         $data = Payment::find($id);
         
          if ($data !== null) {
        
           if($data->delete()){
          
              return response()->json(['msg'=>'Deleted',  'data' => $data], 200);   
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
             $affectedRows = Payment::onlyTrashed()->forceDelete();
             if($affectedRows){
                return response()->json(['msg'=> $affectedRows." affected and unrecoverable."]);
             }

          } else {
            return response()->json(['msg'=> "Fail to validate user."]);
          }

       }
    }



}