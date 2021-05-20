<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use Tracker;
use Session;
use Validator;

use App\customer;

class emailNotificationController extends Controller
{
    public function sendcustomerinvoice(Request $request)
    {
        $validator = validator::make($request->all(),[
        'customeremail'=>'required'
        ],[
            'customeremail.required'=>'Customer email address is required.'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else
        {
            $updateemail = customer::where('customerID', $request->input('customerid'))->first();
            $updateemail->customeremail = $request->input('customeremail');
            $updateemail->save();

            if($updateemail->save())
            {
                $url = 'https://api.elasticemail.com/v2/email/send';
                $data = array('from' => 'ankit23482@yahoo.com',
                    'fromName' => 'Vodafone Store',
                    'apikey' => '7C8CD416EB4A5D6558682A5EB54D9E7E3A2EA1ADB2D950CC49461A1DED2A2ED86342326B0AEFB27771B99E81A6DC4654',
                    'subject' => 'Purchase Invoice',
                    'to' => $request->input('customeremail'),
                    'bodyHtml' => 'Hello this is an example email',
                    'isTransactional' => false);
                
                // use key 'http' even if you send the request to https://...
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data)
                    )
                );
                
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                if ($result === FALSE) { /* Handle error */ }

                return redirect()->back()->with('success', 'Email updated and Invoice shared on email provided');
            }
            else
            {
                return redirect()->back()->with('error', 'Something went wrong, cant share invoice on email. Please try again later.');
            }
        }
    }

    public function sendcustomerrefundinvoice(Request $request)
    {
        $validator = validator::make($request->all(),[
        'customeremail'=>'required'
        ],[
            'customeremail.required'=>'Customer email address is required.'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else
        {
            $updateemail = customer::where('customerID', $request->input('customerid'))->first();
            $updateemail->customeremail = $request->input('customeremail');
            $updateemail->save();

            if($updateemail->save())
            {
                $url = 'https://api.elasticemail.com/v2/email/send';
                $data = array('from' => 'ankit23482@yahoo.com',
                    'fromName' => 'Vodafone Store',
                    'apikey' => '7C8CD416EB4A5D6558682A5EB54D9E7E3A2EA1ADB2D950CC49461A1DED2A2ED86342326B0AEFB27771B99E81A6DC4654',
                    'subject' => 'Purchase Invoice',
                    'to' => $request->input('customeremail'),
                    'bodyHtml' => 'Hello this is an example email',
                    'isTransactional' => false);
                
                // use key 'http' even if you send the request to https://...
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query($data)
                    )
                );
                
                $context  = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                if ($result === FALSE) { /* Handle error */ }

                return redirect()->back()->with('success', 'Email updated and Invoice shared on email provided');
            }
            else
            {
                return redirect()->back()->with('error', 'Something went wrong, cant share invoice on email. Please try again later.');
            }
        }
    }
}
