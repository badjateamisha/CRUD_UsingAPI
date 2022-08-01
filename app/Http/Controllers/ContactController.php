<?php

namespace App\Http\Controllers;

use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Auth;
use App\Models\User; 
use App\Models\Contact;
use Illuminate\Http\Request;


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

   
}
