<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Voucher;  
use App\Models\Topic;
use App\Models\Subject;
use App\Models\Admission;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = "Dashboard";

        // Active admissions
        $admissions = Admission::where('status',1)->latest()->get();

        // Selected admission, department, subject from query
        $selectedAdmission = $request->query('admission');
        $selectedDepartment = $request->query('department');
        $selectedSubject = $request->query('subject');

        $departments = collect();
        $subjects = collect();
        $topics = collect();

        if ($selectedAdmission) {
            $departments = Department::where('admission_id', $selectedAdmission)->where('status',1)->get();
        }

        if ($selectedDepartment) {
            $subjects = Subject::where('department_id', $selectedDepartment)->where('status',1)->get();
        }

        if ($selectedSubject) {
            $topics = Topic::where('subject_id', $selectedSubject)->where('status',1)->get();
        }

        return view('user.dashborad.index', compact(
            'pageTitle','admissions','departments','subjects','topics',
            'selectedAdmission','selectedDepartment','selectedSubject'
        ));
    }


    public function logout(Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        toast('Staff logout successfully!', 'success');
        return redirect('/login');
    } // End Method
}
