<?php
namespace App\Http\Controllers\Front;

use App\Models\WholesaleEnquiry;
use App\Models\FranchiseEnquiry;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WholesaleEnquiryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'city'          => 'nullable|max:255',
            'company_name'  => 'required|string|max:255',
            'gst_number'    => 'required|string|max:50',
            'message'       => 'required|string',
        ]);

        WholesaleEnquiry::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Your enquiry has been submitted successfully. We will contact you soon!',
             'redirect_url' => url('/'),
        ]);
    }
    
     public function franchiseStore(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'city'          => 'nullable|max:255',
            'space'  => 'nullable|max:255',
            'investment'    => 'nullable|max:255',
            'message'       => 'required|string',
        ]);

        FranchiseEnquiry::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Your enquiry has been submitted successfully. We will contact you soon!',
             'redirect_url' => url('/'), // back ka url
        ]);
    }
    
     public function contact(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|max:20',
            'subject'          => 'nullable|max:500',
           'message'       => 'required|string',
           ]);

        Contact::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Your enquiry has been submitted successfully. We will contact you soon!',
             'redirect_url' => url('/'), // back ka url
        ]);
    }
}
