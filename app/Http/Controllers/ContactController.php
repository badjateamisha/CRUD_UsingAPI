<?php

namespace App\Http\Controllers;

use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Auth;
use App\Models\User; 
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use App\Mail\sendmail;
//-----
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use App\Notifications\PasswordResetRequest;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;






class ContactController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required'
        ]);

        $contact = new Contact;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->password = $request->password;
        $contact->address = $request->address;
        $contact->country = $request->country;
        
        $contact->save();
        return response()->json(['message'=>'Contact created Successfully'],200);

    }
    public function display()
    {
        $contact=contact::all();
        return response()->json(['success'=>$contact],200);

    }

    public function display_id($id)
    {
        $contact=contact::find($id);
        return response()->json(['success'=>$contact],200);

    }
    public function update_by_id(Request $request, $id)
    {
        //validating the data to make it not to be null
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
            'address'=>'required',
            'country'=>'required',
        ]);
        $contact=contact::find($id);
        if($contact)
        {
            $contact->name = $request->name;
            $contact->email = $request->email;
            //Password Encrytion using HASH methodd
            $contact->password = $request->password;
            $contact->password = Hash::make($contact->password);

            $contact->address = $request->address;
            $contact->country = $request->country;

            $contact ->update();
            return response()->json(['message'=>'Data Updated Successfully'],200);
        }
        else
        {
            Log::channel('custom')->error("No Data Found with that ID");
            return response()->json(['message'=>'No Data Found with that ID'],404);
        }
    }


   //Function to Delete Data based on ID
   public function delete_by_id(Request $request, $id)
   {
      
       $contact = contact::find($id);
       if($contact)
       {
           $contact ->delete();
           return response()->json(['message'=>'Data Deleted Successfully'],200);
       }
       else
       {
           Log::channel('custom')->error("No Data Found with that ID");
           return response()->json(['message'=>'No Data Found with that ID'],404);
       }
   }

   public function changePassword(Request $request){
    $request->validate([
        'email' => 'required',
        'password' =>'required',
        'newPassword' => 'required'
    ]);
    $result = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
    if($result){
        User::where('id', $request->userId)->update(['password' => Hash::make($request->newPassword)]);
        return response()->json(['message'=>"password updated successfully", 'status'=>200]);
        
    }
    else{
        Log::channel('custom')->error("You have Entered the wrong password");
        return response()->json(['message'=>"Check your old password", 'status'=>400]);
    }
}


   public function forgotPassword(Request $request)
    {  
        
         $request->validate([
            'email'=>'required | max:200',         
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            Log::channel('custom')->error("Email does not exists");
            return response()->json(['Message' => "Email does not exists", 'status' => 404]);
            
        } 
        else {

            $token = Str::random(40);
            $reset = new PasswordReset();

            PasswordReset::create([
                'email' => $request->email,
                'token' => $token
            ]);

            Mail::to($email)->send(new SendMail($token, $email));
            
            return "Token Sent to Mail to Reset Password";
            
        }
    
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'token' => 'required'
        ]);

        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if(!$passwordReset){
            Log::channel('custom')->error("You have entered invalid token");
            return response()->json(['message' => "Token is invalid "]);
        }

        $user = User::where('email', $passwordReset->email)->first();
        $user->password = Hash::make($request->password);

        PasswordReset::where('email', $request->email)->delete();
        return "password Reset successfull";
      
    }


}
