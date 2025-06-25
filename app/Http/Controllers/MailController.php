<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\MailNotify;

class MailController extends Controller
{
    public function index()
    {
        $data=[
            'subject'=>'Subject Mail',
            'body'=>'Hello, <BR>This is my body mail',
            'from'=>'noreply@double-p.co.th',
            'to'=>'test@gmail.com'
        ];

        try {
            Mail::to($data['to'])->send(new MailNotify($data));
            return response()->json(['Please check your mail box!']);
        } catch (Exception $th) {
            return response()->json(['Sorry, something went wrong']);
        }
    }
}
