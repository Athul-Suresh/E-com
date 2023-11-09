<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Mail\EnquiryEmail;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;



class ContactController extends Controller
{

    private $status_200 = 200;
    private function sendmail($from = null, $contact = null)
    {

        try {
            Mail::to($from)->send(new EnquiryEmail($from,$contact));
            return true;
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return false;
        }
    }

    public function userEnquiry(Request $request)
    {
        try {

                $validator = Validator::make($request->all(), [
                    "name" => "required|max:200",
                    "email" => "required|email",
                    "subject" => "required|max:100",
                    "message" => "required|max:200",
                    "phone" => "required|max:15"
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        "status" => false,
                        "message" => "Validation Error",
                        "errors" => $validator->errors(),
                    ], $this->status_200);
                }

                $contact =new Contact();
                $contact->name = $request->input('name');
                $contact->email = $request->input('email');
                $contact->phone = $request->input('phone');
                $contact->subject = $request->input('subject');
                $contact->message = $request->input('message');
                if($contact->save()){
                     // Send the email
                     $sendMail =  $this->sendmail(env('MAIL_FROM_ADDRESS'),$contact);
                    //  dd($sendMail);
                   if ($sendMail) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Thank you for your concern. We will be in touch shortly.',
                        ], $this->status_200);
                    }else{
                        return response()->json([
                            'status' => false,
                            'message' => "Somthing happened try again later",
                        ]);

                    }
                }else{

                    return response()->json([
                        'status' => false,
                        'message' => 'Error',
                    ]);
                }



        } catch (\Throwable $th) {
            return response()->json(['message' => 'Internal server error', 'er' => $th->getMessage()], 500);
        }
    }

}
