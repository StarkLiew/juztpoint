<?php
namespace App\Http\Controllers\Model;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Http\Controllers\Model\UserController as UC;
use Auth;
use App\Http\Controllers\Controller;
class JobController extends Controller
{

    public function __construct()
    {
        $this->ERRORS = [
                         100 =>'No valid ID is provided.',
                         101 =>'Invalid option.',
                         102 => 'Input Validation Fail.',
                        ];
    }

    public function create(Request $request){

      if ($request->has('detail_id')) {
        if(!$this->validateInput($request)) return $this->ERRORS[102];
        $job = new Job;
        $job->id = uniqid();   
        $job->detail_id = $request->input('detail_id');  
        $job->customer_id = $request->input('customer_id');  
        $product->user_id = $request->input('user_id');  
        $product->qty = $request->input('qty'); 
        $product->price = $request->input('price');  
        $product->discount = $request->input('discount');  
        $product->disType = $request->input('disType');  
        $product->discount = $request->input('amount');  
        $product->discount = $request->input('commission');  

        if($product->save()){
          return response()->json(['msg'=>"Job successfully registered!"]);
        } else {
          return response()->json(['msg'=>"Job to create product!"]);
          
        }
      } else {
        return response()->json(['msg'=>"Incomplete required information!"]);
 
      }
    }


    private function validateInput($request){
         
         return true;
    }


}