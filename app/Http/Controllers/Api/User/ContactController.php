<?php

namespace App\Http\Controllers\Api\User;

use App\Helper\APIResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ContactRequest;
use App\Mail\ContactEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    use APIResponse;
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function contact_us(ContactRequest $request){
        $data = $request->validated();
        $data['status'] = "User";
        Mail::to(env("MAIL_USERNAME"))->send(new ContactEmail($data));

        return $this->success(null, __("auth.contact_us_success"));
    }
}
