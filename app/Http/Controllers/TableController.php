<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Show all table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allTable()
    {
        $tables = Table::all();
        return view('user.admin.table.all-table', [
            'tables' => $tables
        ]);
    }

    /**
     * Add new table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addTable()
    {
        return view('user.admin.table.add-table');
    }

    /**
     * Edit table
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editTable($id)
    {
        $table = Table::findOrFail($id);
        return view('user.admin.table.edit-table', [
            'table' => $table
        ]);
    }

    /**
     * Delete table
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTable($id)
    {
        $table = Table::findOrFail($id);
        if ($table->delete()) {
            Order::where('table_id', $id)->update(array('table_id' => 0));
            return redirect()->back();
        }
    }

    /**
     * Save table
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTable(Request $request)
    {
        $table = new Table();
        $table->table_no = $request->get('table_no');
        $table->table_capacity = $request->get('table_capacity');
        $table->status = 1;
        $table->user_id = auth()->user()->id;
        if ($table->save()) {
            return response()->json('Ok', 200);
        }
    }

    /**
     * Update table
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTable($id, Request $request)
    {
        $table = Table::findOrFail($id);
        $table->table_no = $request->get('table_no');
        $table->table_capacity = $request->get('table_capacity');
        $table->status = $request->get('status') == 'on' ? 1 : 0;
        $table->user_id = auth()->user()->id;
        if ($table->save()) {
            return response()->json('Ok', 200);
        }
    }

    /**
     * All table api
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTables(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Table::all());
    }

}
