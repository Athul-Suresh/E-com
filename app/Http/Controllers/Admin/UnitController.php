<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use DataTables;
class UnitController extends Controller
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
    public function index(Request $request)
    {
        $this->authorizeUser('unit', 'view');
        if ($request->ajax()) {
            $data = Unit::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.dashboard.units.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorizeUser('unit', 'create');
        return view('admin.dashboard.units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeUser('unit', 'create');

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'name'),
            ],
        ], [
            'name.required' => 'The Units Name field is required.',
            'name.string' => 'The Units Name field must be a string.',
            'name.max' => 'The Units Name may not be greater than :max characters.',
            'name.unique' => 'The Units Name has already been taken.',
            ]);

        try {
            $unit = new Unit();
            $unit->name = strtoupper($validatedData['name']);
            $unit->save();

            return redirect()->route('admin.units.index')->with('success', 'Unit ' . $unit->name . ' created successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.units.index')->with('error', 'Unit creation failed. ' . $e->getMessage());
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
        $this->authorizeUser('unit', 'create');
        $unit=Unit::findOrFail($id);
        return view('admin.dashboard.units.edit',compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeUser('unit', 'edit');
        $unit=Unit::findOrFail($id);
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'name')->ignore($id),
            ],
        ], [
            'name.required' => 'The Units Name field is required.',
            'name.string' => 'The Units Name field must be a string.',
            'name.max' => 'The Units Name may not be greater than :max characters.',
            'name.unique' => 'The Units Name has already been taken.',
            ]);

        try {
            $unit->name = strtoupper($validatedData['name']);
            $unit->save();

            return redirect()->route('admin.units.index')->with('success', 'Unit ' . $unit->name . ' Updated successfully.');
        } catch (Exception $e) {
            return redirect()->route('admin.units.index')->with('error', 'Unit Updated failed. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorizeUser('unit', 'delete');
        try {
            $unit = Unit::findOrFail($id);
            $unit->delete();
            return redirect()->route('admin.units.index')->with('success', 'Unit ' . $unit->name . ' deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
}
