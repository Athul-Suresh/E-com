<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class ContactController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    private function authorizeUser($permission, $type): void
    {
        if (is_null($this->user) || !$this->user->can($permission . '.' . $type)) {
            abort(403, 'Sorry !! You are Unauthorized!');
        }
    }
    
    public function enquiry(Request $request)
    {
        $this->authorizeUser('enquiry', 'view');
        if($request->ajax()){
            $data = Contact::latest()->get();
            return Datatables::of($data)
                ->make(true);
        }else {
            return view('admin.dashboard.enquiry.index',['contacts'=>Contact::all()]);
        }

       
      
    }


    public function destroy(string $id)
    {
        $this->authorizeUser('enquiry', 'delete');
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();
            return redirect()->route('admin.enquiry.index')->with('success', 'Contact Enquiry deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
}
