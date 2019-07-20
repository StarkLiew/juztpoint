<?php
namespace App\Http\Controllers\Model;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Commission;
use App\Models\CommissionRate;
use App\Models\Payment;
use App\Http\Controllers\Model\UserController as UC;
use DB;
use Illuminate\Validation\Rule;
use Auth;
use Route;
use Log;
use Schema;
use App\Http\Controllers\Controller;



class InvoiceController extends Controller
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
      
        $data = new Invoice($request->all());
        $data->user_id = Auth::user()->id;
        //$data->reference = uniqid();
        $data->reference = sprintf('%03d', $data->invoice_id) . date("ymdHis", strtotime($data->date)) .  sprintf('%03d',   $data->user_id) . " ";

            try{
       
              $data->save();
              DB::beginTransaction();
       
              if($request->has('details')){
               
              $inputs = $request->input('details');
                
                 $data->details()->createMany($inputs);
              }
      
              $commissions = $this->calcCommission($data);

              $data->load('customer', 'details', 'user');
              $data->details->load('user', 'product','appointment','appointment.customer','appointment.user', 'appointment.details','appointment.details.product','appointment.details.user','appointment.details.assistant', 'appointment.details.commissions');


               DB::commit();
               $default = \App\Models\Company::getDefault();
               $options = compact('default');
               return response()->json(['msg'=>'Saved', 'data'=>$data, 'options'=> $options], 200);

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
      $title = 'Invoices';
      //$dates = ['date'];


      if ($request->has('page')) $option['page'] = $request->input('page');
      if ($request->has('by')) $option['by'] = $request->input('by');
      if ($request->has('order')) $option['order'] = $request->input('order');
      if ($request->has('filtername')) $filtername = $request->input('filtername');
      if ($request->has('group')) $selectedgroup = $request->input('group');
      if ($request->has('status')) $selectedgroup = $request->input('status');
      $model = Invoice::orderBy($option['by'], $option['order']);

      if($filtername !== '') $model = $model->where('id', 'like', '%'.$filtername.'%');
      if($status !== '') $model = $model->where('status', $status);
      $model->with(['details' => function($query){$query->with(['product', 'user']);},'customer', 'user']);


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


          $result = Invoice::where("id", "=", $id)->with(['payments', 'payments.paymentType','payments.user','customer', 'user', 'details' => function($query){
                    $query->with(['product','user', 'commissions', 'appointment' => function($query){ 
                         $query->with(['customer','user', 'details' => function($query){
                             $query->with(['product','commissions', 'user', 'assistant'])->orderBy('line');
                          }]);
                        }]);
                     }])->first();
          

    
          return response()->json(compact('result', 'options', 'dates'), 200, [], JSON_NUMERIC_CHECK);
      } else {
         return response()->json(['msg'=>'Not found'], 401);
      }         

    }

    public function update($id, Request $request){
      
        $data = Invoice::find($id);
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

                foreach($data->details() as $detail){
                    $detail->commissions()->forceDelete();
                }
                $data->details()->forceDelete();
                $data->details()->createMany($inputs);
              }

         
             
             $commissions = $this->calcCommission($data);

             $data->touch();
             $data->update($request->all());
             $data->load('customer', 'details', 'user', 'payments', 'payments.paymentType');
             $data->details->load('user', 'product');
             DB::commit();
              $default = \App\Models\Company::getDefault();
              $options = compact('default');
             return response()->json(['msg'=>'Saved', 'data'=>$data, 'options'=> $options], 200);

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
         $data = Invoice::find($id);
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
             $affectedRows = Invoice::onlyTrashed()->forceDelete();
             if($affectedRows){
                return response()->json(['msg'=> $affectedRows." affected and unrecoverable."]);
             }

          } else {
            return response()->json(['msg'=> "Fail to validate user."]);
          }

       }
    }

      public function getMonthlySum(Request $request){
         $option = ['paging'=>25, 'page'=>1];
         if ($request->has('page')) $option['page'] = $request->input('page');
         
         if(!isset($request->yearRange)){
            $yearRange = ['from' => date('Y') - 1, 'to' => date('Y')];
            
          /*  $now = strtotime("last day of this month");
            if(12 - date('m',$now) > 0){
            
            } else {
              $yearRange = ['from' => date('Y'), 'to' => date('Y')];
            } */


            
         } 
          else $yearRange = $request->yearRange;
         
         $months = '';

         
         for($yr = $yearRange['from']; $yr<=$yearRange['to']; $yr++) {
            for($mth = 1; $mth<=12; $mth++) {
                if(!empty($months)) $months .= ' UNION ';
                $months .= 'SELECT ' .$yr.' AS yr, '.$mth.' AS mth';
            }
          }

          $months = ' (' . $months . ') AS pos_months';



           $model = Invoice::select('months.yr','months.mth')->selectRaw('SUM(gross) as total')->rightJoin(DB::raw($months), function($q) {
                $q->on('months.mth', '=', DB::raw('MONTH(date)'))->where('months.yr', '=', DB::raw('YEAR(date)'));
           })->groupBy('months.yr')->groupBy('months.mth')->havingRaw('yr BETWEEN ? and ?', $yearRange )->orderBy('months.yr')->orderBy('months.mth');


//         DB::enableQueryLog();
         $result  = $model->paginate($option['paging']);
     //   dd(DB::getQueryLog());

         return response()->json(compact('result'));

  } 

  public function getMonthlySum_2(Request $request){
         $option = ['paging'=>25, 'page'=>1];
         if ($request->has('page')) $option['page'] = $request->input('page');
         
         if(!isset($request->yearRange)) $yearRange = ['from' => date('Y'), 'to' => date('Y')];
          else $yearRange = $request->yearRange;
         
         $months = '';
         
         for($yr = $yearRange['from']; $yr<=$yearRange['to']; $yr++) {
            for($mth = 1; $mth<=12; $mth++) {
                if(!empty($months)) $months .= ' UNION ';
                $months .= 'SELECT ' .$yr.' AS yr, '.$mth.' AS mth';
            }
          }

          $months = ' (' . $months . ') AS pos_months';



           $model = Invoice::select('months.yr','months.mth')->selectRaw('SUM(gross) as total')->rightJoin(DB::raw($months), function($q) {
                $q->on('months.mth', '=', DB::raw('MONTH(date)'))->where('months.yr', '=', DB::raw('YEAR(date)'));
           })->groupBy('months.yr')->groupBy('months.mth')->havingRaw('yr BETWEEN ? and ?', $yearRange )->orderBy('months.yr')->orderBy('months.mth');


//         DB::enableQueryLog();
         $result  = $model->paginate($option['paging']);
     //   dd(DB::getQueryLog());

         return response()->json(compact('result'));

  } 


  public $commissions = [];

  public function calcCommission($invoice, $details = [], $lineId = ''){
 

      if(empty($details)){
         //$invoice = Invoice::find($invoice);
        $details = $invoice->details()->get();  
 
      }       



      foreach($details as $detail) {

        if( $detail->product_id == 1){
          $appointment = $detail->appointment()->first();
          $app_details = $appointment->details()->get();
          $this->comms = $this->calcCommission('', $app_details, $detail->id);
          continue;
        }else {
           $product = $detail->product()->first(); 
        }

        $amount = 0.00;
        $commission = new Commission(); 

       
        if($lineId) {
           $commission->inv_detail_id = $lineId;
           $commission->appointment_detail_id = $detail->id;
        } else {
           $commission->inv_detail_id = $detail->id;
        }

        $commission->user_id = $detail->user_id;

  
        $commRate =  $product->commissionRate()->first();
      
        if($commRate->fix){
            $amount =  $commRate->rate; 
        } else{
            $qty = 1;
            if($detail->qty > 1)  $qty = $detail->qty;

            if($detail->disType === '$') $price = $detail->price - $detail->discount;
            else $price = $detail->price - ($detail->price * $detail->discount/100);
            $amount =  ($price * $qty)  * ($commRate->rate/100);
            if($detail->assistant_id){
               $amount = $amount/2;
               $commission2 = new Commission(); 
               if(empty($lineId)) $commission2->inv_detail_id = $detail->id;
               else $commission2->inv_detail_id = $lineId;
               //$commission2->inv_detail_id = $lineId || $detail->id;
               if($lineId) $commission2->appointment_detail_id = $detail->id;
               $commission2->user_id = $detail->assistant_id;
               $commission2->amount = $amount;
               $commission2->save();
               $this->commissions[] = $commission2;

            }
        } 
         $commission->amount = $amount;
         $inventories = $product->inventories()->get();
 
         if(count($inventories) > 0){         
           foreach($inventories as $service){
            foreach($details as $item){ 
                 if($item->product_id === $service->prod_id){
                      $product = $item->product()->first();
                      $commRate = $product->commissionRate()->first();
                         $amount = $amount - $commRate->rate; 
                 }
             }
            }     
          }
          $commission->amount = $amount;
          $this->commissions[] = $commission;
          $commission->save();
          
        } 
        if(!empty($id)) return response()->json($this->commissions);
        return $this->commissions;

    }


}
