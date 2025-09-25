<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgramSubject;
use Illuminate\Support\Carbon;
use Session;
use Illuminate\Support\Str;

class ProgramSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Program Subject List';
        $programs = ProgramSubject::latest()->get();
        return view('admin.program.subject.index',compact('pageTitle','programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Program Subject Create';
        return view('admin.program.subject.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);


        $program = new ProgramSubject;

        if($request->status == Null){
            $request->status = 0;
        }
      
        $program->status = $request->status;
        $program->name = $request->name;
        $program->slug = Str::slug($request->name);
        $program->description = $request->description;
        $program->created_at = Carbon::now();
        $program->save();

        flash()->addSuccess("Program Subject Created Successfully.");
        $url = '/admin/program/subject/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Program Subject Show';
        $program = ProgramSubject::find($id);
        return view('admin.program.subject.show',compact('pageTitle','program'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $program = ProgramSubject::find($id);
        $pageTitle = 'Program Subject Edit';
        return view('admin.program.subject.edit', compact('program','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $program = ProgramSubject::find($id);

        if($request->status == Null){
            $request->status = 0;
        }
       
        $program->status = $request->status;
        $program->name = $request->name;
        $program->slug = Str::slug($request->name);
        $program->description = $request->description;
        $program->updated_at = Carbon::now();

        $program->save();

        flash()->addSuccess("Program Subject Updated Successfully.");
        $url = '/admin/program/subject/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = ProgramSubject::find($id);
        $program->delete();


        flash()->addError("Program Subject Deleted Successfully.");
        $url = '/admin/program/subject/index';
        return redirect($url);
    }
}
