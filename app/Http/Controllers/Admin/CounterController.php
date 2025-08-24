<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Counter;
use Illuminate\Support\Carbon;

class CounterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Counter List';
        $counters = Counter::latest()->get();
        return view('admin.counter.index',compact('counters','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Counter Create';
        return view('admin.counter.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'counter_no' => 'required',
        ]);

        $counter = new Counter;

        $counter->title = $request->title;
        $counter->counter_no = $request->counter_no;

        if($request->status == Null){
            $request->status = 0;
        }

        $counter->status = $request->status;
        $counter->created_at = Carbon::now();
        $counter->save();

        flash()->addSuccess("Counter Created Successfully.");
        $url = '/admin/counters/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Counter Show';
        $counter = Counter::find($id);
        return view('admin.counter.show',compact('pageTitle','counter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $counter = Counter::find($id);
        $pageTitle = 'Counter Edit';
        return view('admin.counter.edit', compact('counter','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $counter = Counter::find($id);
        $counter->title = $request->title;
        $counter->counter_no = $request->counter_no;


        if($request->status == Null){
            $request->status = 0;
        }
        $counter->status = $request->status;
        $counter->updated_at = Carbon::now();

        $counter->save();

        flash()->addSuccess("Counter Updated Successfully.");
        $url = '/admin/counters/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $counter = Counter::find($id);
        $counter->delete();

        flash()->addError("Counter Deleted Successfully.");
        $url = '/admin/counter/index';
        return redirect($url);
    }
}
