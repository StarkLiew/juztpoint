<?php
namespace App\Http\Controllers\Model;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Account;
use App\Http\Controllers\Model\UserController as UC;
use Auth;
use Route;
use Schema;
use App\Http\Controllers\Controller;


class SalesController extends Controller
{

    public function __construct()
    {


       $this->errors = [];
       $this->append = false;
       $this->noSave = true;

    }

    public function create(Request $request){
        $this->append = true;
         
  

        if(!$this->validateInput($request)){

           return $this->callback($request);
        } 

         $data = $this->mapInput($request);
     

         $this->save($data, $request);
    }


    public function all(Request $request){
    
      $result = Appointment::all();
                  
      return response()->json($result);
    }

    public function detail($id, Request $request){
      if(!empty($id)){
          $result = Appointment::where("id", "=", $id)->first();
          return $this->callback($request, $result);
      } else {
         $this->errors[] = ["msg"=>  $title." not found"];
         return $this->callback($request);
      }


         
    }

    public function update($id, Request $request){
           if(empty($id)){     
              return $this->gotoList();
            }
            
            $data = Appointment::find($id);
            if(!$data){
               
                return $this->gotoList();
            }

            if(!$this->validateInput($request, $data)){
                return $this->callback($request);

            }
       
            $data = $this->mapInput($request, $data);
   
              
            if(!$this->noSave){
               $data->touch();
              $this->save($data, $request);
            } else {
               $this->errors[] = ["msg"=>"Nothing to save."]; 
               return $this->callback($request, $data);

            }



    }


    public function trash($id, Request $request){
      if($request->has('password')){
            if(!Auth::attempt(['email' => Auth::user()->email, 'password' => $request->input('password')])){
                return redirect()->back();
            }
      }

      if(!empty($id)){
         $data = Appointment::find($id);
         if ($data !== null) {
            $data->delete();
            return $this->gotoList(); 
          } else  { 
            return $this->gotoList();
          }
      } else {
        return $this->gotoList(); 
      }
    }

  public function destroy(Request $request){

       if ($request->has('password')) {
          if (UC::isCurrentUser($request->input('password'))) {
             $affectedRows = Appointment::onlyTrashed()->forceDelete();
             if($affectedRows){
                return response()->json(['msg'=> $affectedRows." affected and unrecoverable."]);
             }

          } else {
            return response()->json(['msg'=> "Fail to validate user."]);
          }

       }



    }

    private function mapInput($request, $data = null){
        if ($request->isMethod('post')){

            var_dump($request->all());
            return;
            $data = new Appointment($request->all());
            $data->user_id = Auth::user()->id;
            $data->details->fill($request->get('details'));
              


/*   $user = User::with('datosAlumno')->find($id);
    $user->fill(\Request::all());
    $user->datosAlumno->fill(\Request::get('datosAlumno'));
    $user->push();*/
        } 
      
            foreach ($request->toArray() as $key => $value) {
              if(Schema::hasColumn('appointments', $key) && $key !== 'id') {

                 $type = Schema::getColumnType('appointments', $key);
                 switch ($type) {
                  case 'double' :
                  case 'integer' :
                  case 'float' :
                     $casted  = (double) $value;
                     break;
                   case 'boolean':
                     $casted  = (boolean) $value;
                     break;   
                   default:
                     $casted = trim($value);
                     break;
                 }
              
                 if($casted !== $data[$key] || !isset($data[$key])){
                      $data->$key = $casted;
                      $this->noSave = false;
                  }     
           
              }
                

         }
        
     
 

         $data->touch();
       
         return $data;
    }

    private function validateInput($request, $old = []){
         $this->errors = [];
         $noCheck = [];
         if(!$this->append){
           foreach ($old->toArray() as $key => $value) {
             if($request->has($key) && trim($value) === $request->input($key)){
                $noCheck[] = $key;
             } 
            }    
         }


         if($request->has('account_id')){
          if(!in_array('account_id',$noCheck)){
            $name = trim($request->input('account_id'));
            if(Account::where('id', $name)->count() < 1){
               $this->errors['account_id'] = ["msg"=>"Invalid Customer"]; 
            }
           }

         }else{
               $this->errors['account_id'] = ["msg"=>"Customer is required"]; 
         }
      

        if(count($this->errors) > 0) return false;
         return true;
    }
    


}