<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    public function index()
    {
        if (!\Auth::user()->can('manage expense')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $assets = DB::table('asset_registers')->where('parent_id', parentId())->orderByDesc('id')->get();
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        if (!\Auth::user()->can('create expense')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
        return view('assets.create');
    }

    public function store(Request $request)
    {
        if (!\Auth::user()->can('create expense')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'acquisition_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'useful_life_years' => 'required|integer|min:1',
        ]);

        DB::table('asset_registers')->insert([
            'parent_id' => parentId(),
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'category' => $request->category,
            'acquisition_date' => $request->acquisition_date,
            'cost' => $request->cost,
            'salvage_value' => $request->salvage_value ?? 0,
            'useful_life_years' => $request->useful_life_years,
            'method' => $request->method ?? 'straight_line',
            'accumulated_depreciation' => 0,
            'book_value' => $request->cost,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('asset.index')->with('success', __('Asset created.'));
    }

    public function show($id)
    {
        if (!\Auth::user()->can('show expense')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
        $asset = DB::table('asset_registers')->where('id', $id)->where('parent_id', parentId())->first();
        if (!$asset) {
            return redirect()->back()->with('error', __('Asset not found.'));
        }
        $depreciationEntries = DB::table('depreciation_entries')->where('asset_register_id', $id)->orderByDesc('id')->get();

        return view('assets.show', compact('asset', 'depreciationEntries'));
    }

    public function edit($id)
    {
        if (!\Auth::user()->can('edit expense')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
        $asset = DB::table('asset_registers')->where('id', $id)->where('parent_id', parentId())->first();
        if (!$asset) {
            return redirect()->back()->with('error', __('Asset not found.'));
        }
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, $id)
    {
        if (!\Auth::user()->can('edit expense')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'useful_life_years' => 'required|integer|min:1',
        ]);

        DB::table('asset_registers')->where('id', $id)->where('parent_id', parentId())->update([
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'category' => $request->category,
            'acquisition_date' => $request->acquisition_date,
            'cost' => $request->cost,
            'salvage_value' => $request->salvage_value ?? 0,
            'useful_life_years' => $request->useful_life_years,
            'method' => $request->method ?? 'straight_line',
            'updated_at' => now(),
        ]);

        return redirect()->route('asset.index')->with('success', __('Asset updated.'));
    }

    public function destroy($id)
    {
        if (!\Auth::user()->can('delete expense')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
        DB::table('asset_registers')->where('id', $id)->where('parent_id', parentId())->delete();
        DB::table('depreciation_entries')->where('asset_register_id', $id)->delete();

        return redirect()->route('asset.index')->with('success', __('Asset deleted.'));
    }
}
