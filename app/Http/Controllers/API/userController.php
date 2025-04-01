<?php

namespace App\Http\Controllers\API;

use App\Helpers\FileUpload;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class userController extends BaseController
{
   public function updateUser(Request $request){
        $request->validate([
            'email' => 'required',
        ]);

       $userID = auth()->user()->id;

       $user = User::where('id',$userID)->firstorfail();

       $user->name = $request->input('name');
       $user->email = $request->input('email');
       if($request->input('password')){
       $user->password = Hash::make($request->input('password'));
       }


       $user->save();

       return $this->responseSuccess($user,'user-update','user updated successfully',200);
   }


    public function updatePic(Request $request){
        $request->validate([
            'profile_pic' => 'required',
        ]);

        $userID = auth()->user()->id;

        $user = User::where('id',$userID)->firstorfail();

        if($request->file('profile_pic')) {
            // Use the FileUpload helper to store the file
            $url = FileUpload::storeFile($request->file('profile_pic'));
            $filename = basename($url); // Extracts the filename from the file URL
            $user->profile_pic = $filename; // Save only the filename in the database
        }

        if($user->profile_pic){
            $mediaPath = $user->profile_pic;
            $user->profile_pic = FileUpload::getFullUrl("uploads/{$mediaPath}");
        }

        $user->save();

        return $this->responseSuccess($user,'user-update','user updated successfully',200);
    }



    public function getUserData(){
       $userID = auth()->user()->id;

       $user = User::where('id',$userID)->firstorfail();

       return $this->responseSuccess($user,'user-data','user data fetched successfully!',200);

   }

}
