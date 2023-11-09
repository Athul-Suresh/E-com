<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $this->authorizeUser('voucher', 'view');
        $vouchers = Voucher::all();
        return view('admin.dashboard.voucher.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dashboard.voucher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vouchers', 'voucher_code'),
            ],
            'discount_amount' => 'nullable|numeric',
            'discount_amount_type' => 'nullable|integer|in:1,2',
            'usage_limit'=>'required|numeric',
            'expires_at'=>'required|date',
        ],
        [
            'code.required' => 'The Voucher Code field is required.',
            'code.string' => 'The Voucher Code field must be a string.',
            'code.max' => 'The Voucher Code may not be greater than :max characters.',
            'code.unique' => 'The Voucher Code has already been taken.',

            'discount_amount.numeric' => 'The Discount field must be a Number.',
            'discount_amount_type.integer' => 'The Discount Type field must be a Number.',
            'discount_amount_type.in' => 'The Discount Type is invalid.',

            'usage_limit.required' => 'The Usage Limit is required',
            'usage_limit.numeric' => 'The Usage Limit must be a Number.',

            'expires_at.required' => 'The Usage Limit is limt',
            'expires_at.date' => 'The Usage Limit is limt',
        ]);

        try {
            $voucher = new Voucher();
            $voucher->voucher_code = $validatedData['code'];
            $voucher->discount = $validatedData['discount_amount'] ?? 0;
            $voucher->discount_type = $validatedData['discount_amount_type'] ?? 0;
            $voucher->status = 1;
            $voucher->expires_at = $validatedData['expires_at'];
            $voucher->usage_limit = $validatedData['usage_limit'];
            $voucher->save();

            return redirect()->route('admin.vouchers.index')->with('success', 'Voucher Code ' . $voucher->name . ' created successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.vouchers.index')->with('error', 'Voucher Code creation failed. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(Request $request, $id)
    {
        $this->authorizeUser('voucher', 'edit');
        try {
            $voucher = Voucher::findOrFail($id);
            $voucher->status == 1 ? $voucher->status = 0 : $voucher->status = 1;
            $voucher->save();
            return response()->json(['success' => true, 'status' => $voucher->status]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
