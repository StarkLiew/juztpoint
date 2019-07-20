<?php
namespace App\Http\Controllers\Model;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Price;
use App\Models\Inventory;
use App\Models\CommissionRate;
use App\Models\Taxes;
use App\Http\Controllers\Model\UserController as UC;
use Auth;
use Route;
use Schema;
use DB;
use App\Http\Controllers\Controller;



class ProductController extends Controller
{

    public function __construct()
    {


       $this->errors = [];
       $this->append = false;
       $this->noSave = true;

    }

    public function create(Request $request){

       if($this->isService()){
        $this->validate($request, [
              'name' => 'required|min:1|max:255', 
         ]);

       }else{
            $this->validate($request, [
              'name' => 'required|min:2|max:255',
              'sku' => 'required|min:2|max:255',
         ]);
   	
       }
       

        $data = new Product($request->all());
        $data->user_id = Auth::user()->id;
        $data->service = $this->isService();


        //DB::transaction(function() use ($data, $request) {        });    
            try{
              
              $data->save();

              if($request->has('prices')){
                $inputs = $request->input('prices');
                $data->prices()->createMany($inputs);
              }

              if($request->has('inventories')){
                $inputs = $request->input('inventories');
                $data->inventories()->createMany($inputs);
            
              }

              $data->load('prices', 'inventories', 'category', 'taxRate', 'commissionRate');
              $data->inventories->load('product');
             
              return response()->json(['msg'=>'Saved', 'data'=>$data], 200);

            } catch(\PDOException $e){
              
              return response()->json(['msg'=>'Fail', 'data'=>[]], 400);
            }


   
    }


    public function all(Request $request){

      $option = ['by'=>'name', 'order'=>'asc', 'paging'=>25, 'page'=>1];
      $selectedgroup = '';
      $filtername = '';
      $parent = '';
      $modal = '';
      $title = 'Products';
      $key = '';
      $group = '';
      $except = [];



      if ($request->has('page')) $option['page'] = $request->input('page');
      if ($request->has('by')) $option['by'] = $request->input('by');
      if ($request->has('order')) $option['order'] = $request->input('order');
      if ($request->has('filtername')) $filtername = $request->input('filtername');
      if ($request->has('group')) $group = $request->input('group');
      if ($request->has('except')) $except = json_decode($request->input('except'));
      $service = $this->isService();
     

     $model = Product::with(['category','taxRate', 'commissionRate', 'prices']);
     if($filtername !== '') $model = $model->where('name', 'like', '%'.$filtername.'%');

     if($group !== ''){
            $key = 'products';
             
             $model->orderBy($group);
     }
     
     foreach($except as $id){
     	$model->where('id', '<>', $id);
     }

     

     $model->orderBy($option['by'], $option['order']);
     $result  = $model->where('service', $service)->paginate($option['paging']);
                  
      return response()->json(compact('title','modal','parent','option','group','filtername','result', 'key'), 200);
    }

   public function detail($id, Request $request){


       if(!empty($id)){
    
          $commissionRates =  \App\Models\CommissionRate::all();
          $categories = \App\Models\Category::where('service', $this->isService())->get();
          $taxes = \App\Models\TaxRate::all();
          $options = compact('commissionRates', 'categories','taxes');

          if($id === 'new') {
            $result = [];
            return response()->json(compact('result', 'id','options'), 200);
          }
          $result = Product::with(['prices','category','taxRate','commissionRate','inventories'=>function($query){ $query->with('product');}])->where("id",$id)->first();

          return response()->json(compact('result', 'id','options'), 200);

      } else {

         return response()->json(['msg'=>'Bad request'], 401);
      }         

    }

    public function update($id, Request $request){

            
        $data = Product::find($id);

        if(!$data){ 
            return response()->json(['msg'=>'Not found'], 400);
        }

       if($this->isService()){
        $this->validate($request, [
              'name' => 'required|min:1|max:255', 
         ]);

       }else{
            $this->validate($request, [
              'name' => 'required|min:2|max:255',
              'sku' => 'required|min:2|max:255',
         ]);
   	
       }
    
           try{
 
              if($request->has('prices')){
                $inputs = $request->input('prices');
                $data->prices()->forceDelete();
                $data->prices()->createMany($inputs);
              }

              if($request->has('inventories')){

                $inputs = $request->input('inventories');
        
                $data->inventories()->forceDelete();
                $data->inventories()->createMany($inputs);
              }

             $data->touch();
             $data->update($request->all());
             $data->load('prices', 'inventories', 'category', 'taxRate', 'commissionRate');
             $data->inventories->load('product');
             return response()->json(['msg'=>'Saved', 'data'=>$data], 200);

            } catch(\PDOException $e){
              
              return response()->json(['msg'=>'Fail', 'data'=>$data, 'err'=> $e] , 400);
            }
        /*DB::transaction(function() use ($data, $request) { 
        });   */



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
         $data = Product::find($id);
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
             $affectedRows = Product::onlyTrashed()->forceDelete();
             if($affectedRows){
                return response()->json(['msg'=> $affectedRows." affected and unrecoverable."]);
             }

          } else {
            return response()->json(['msg'=> "Fail to validate user."]);
          }

       }



    }


   private function isService(){
       $uri =  Route::getFacadeRoot()->current()->uri();
       if(strpos($uri, 'services')) return true;
       return false;
    }

}
