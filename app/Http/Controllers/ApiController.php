<?php

namespace App\Http\Controllers;

use App\Models\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ApiController extends Controller
{
        public function index()
        {
            $api = new Api();
            $records = $api->getAll();

            //here i am fatching the data ;

            return view('welcome', ['data' => $records]);
        }

    public function openForm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired signed URL');
        }
       
        $requestid = $request->requestid;

        return view('feedbackform.feedback', compact('requestid'));
    }

    public function sendLink($requestid)
    {
        // ✔ Correct: generate a FRESH link
        $url = URL::temporarySignedRoute(
            'staff.form',
            now()->addSeconds(10),  // each link has its own 30-sec life
            ['request_id' => $requestid]
        );

        return response()->json([
            'request_id' => $requestid,
            'signed_url' => $url,
            'expires_in' => '30 seconds',
        ]);
    }
}
