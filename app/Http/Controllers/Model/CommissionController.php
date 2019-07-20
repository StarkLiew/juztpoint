<?php
namespace App\Http\Controllers\Model;
use Illuminate\Http\Request;
use App\Models\Commission;
use App\Models\Invoice;
use App\Http\Controllers\Model\UserController as UC;
use Auth;
use DB;
use Log;
use App\Http\Controllers\Controller;
class CommissionController extends Controller
{

    public function __construct()
    {
        $this->ERRORS = [
                         100 =>'No valid ID is provided.',
                         101 =>'Invalid option.',
                         102 => 'Input Validation Fail.',
                        ];
    }

   

    public function all(Request $request){

      $option = ['by'=>'date', 'order'=>'desc', 'paging'=>31, 'page'=>1];
      $selectedgroup = '';
      $filtername = '';
      $parent = '';
      $modal = '';
      $selectedRangeOption='1';
      $status = '';
      $title = 'Commissions'; 
      $dates = ['date'];
      $date_range = [];
      $filter = $request->all();
      if ($request->has('page')) $option['page'] = $request->input('page');
      if ($request->has('by')) $option['by'] = $request->input('by');
      if ($request->has('order')) $option['order'] = $request->input('order');
      if ($request->has('filtername')) $filtername = $request->input('filtername');
      if ($request->has('group')) $selectedgroup = $request->input('group');
      if ($request->has('staff')) $selectedgroup = $request->input('staff');
      if ($request->has('status')) $selectedgroup = $request->input('status');
      if ($request->has('range')) $selectedRangeOption = $request->input('range');
      if ($request->has('date_from') && $request->has('date_to')) $range = [$request->input('date_from'), $request->input('date_to')];

      
    

      if($selectedRangeOption == 1){
         $dateRange = [date('Y-m-d' . ' 00:00:00',strtotime('first day of this month')), date('Y-m-d' . ' 23:59:59',strtotime('last day of this month'))];

      }

      if($selectedRangeOption == 2){
         $dateRange = [date('Y-m-d' . ' 00:00:00',strtotime('first day of last month')), date('Y-m-d' . ' 23:59:59',strtotime('last day of last month'))];
      }

      if($selectedRangeOption == 3){


           if(empty($range[0]) && empty($range[1])){

              $dateRange = [date('Y-m-d' . ' 00:00:00',strtotime('first day of this month')), date('Y-m-d' . ' 23:59:59',strtotime('last day of this month'))];
           }
           else $dateRange = [date('Y-m-d' . ' 00:00:00', strtotime($range[0])), date('Y-m-d' . ' 23:59:59',strtotime($range[1]))];

           $dateRange = [date('Y-m-d' . ' 00:00:00', strtotime($range[0])), date('Y-m-d' . ' 23:59:59',strtotime($range[1]))];

      }

       //DB::enableQueryLog();

         $model = Commission::select('invoices.date', 'invoices.reference', 'users.name');

         $model->selectRaw('SUM(' . DB::getTablePrefix()  .'commissions.amount) AS amount');
        
         $model->join('users','users.id', '=', 'commissions.user_id')->join('invoice_details','invoice_details.id', '=', 'commissions.inv_detail_id')->join('invoices','invoices.id', '=', 'invoice_details.invoice_id')->groupBy('invoices.date','invoices.reference','commissions.user_id');
         //if($filtername !== '') $model = $model->having('invoices.reference', 'like', '%'.$filtername.'%')->having('users.name', 'like', '%'.$filtername.'%');
         //if($filtername !== '') $model = $model->where('users.name', 'like', '%'.$filtername.'%');
         $model->whereBetween('invoices.date', [$dateRange]);

         $result = $model->orderBy($option['by'], $option['order'])->paginate($option['paging']);
          
         $sum = Commission::selectRaw('SUM(' . DB::getTablePrefix()  .'commissions.amount) AS amount');
         $sum->join('users','users.id', '=', 'commissions.user_id')->join('invoice_details','invoice_details.id', '=', 'commissions.inv_detail_id')->join('invoices','invoices.id', '=', 'invoice_details.invoice_id')->groupBy('invoices.date','invoices.reference','commissions.user_id');
         $sum->whereBetween('invoices.date', [$dateRange]);
         // $queryLog = DB::getQueryLog();

         return response()->json(compact('result','dates', 'total'));

    }




}