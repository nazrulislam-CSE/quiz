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
use App\Models\Mcq;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = "Dashboard";

        $admissions = Admission::where('status',1)->latest()->get();

        $selectedAdmission = $request->query('admission');
        $selectedDepartment = $request->query('department');
        $selectedSubject = $request->query('subject');
        $selectedTopic = $request->query('topic');

        $departments = collect();
        $subjects = collect();
        $topics = collect();
        $mcqs = collect();

        if ($selectedAdmission) {
            $departments = Department::where('admission_id', $selectedAdmission)->where('status',1)->get();
            if ($departments->isEmpty() && !$selectedDepartment) {
                return redirect()->route('user.user.home')->with('error', 'No departments available for this admission.');
            }
        }

        if ($selectedDepartment) {
            $subjects = Subject::where('department_id', $selectedDepartment)->where('status',1)->get();
            if ($subjects->isEmpty() && !$selectedSubject) {
                return redirect()->route('user.user.home')->with('error', 'No subjects available for this department.');
            }
        }

        if ($selectedSubject) {
            $topics = Topic::where('subject_id', $selectedSubject)->where('status',1)->get();
            if ($topics->isEmpty() && !$selectedTopic) {
                return redirect()->route('user.user.home')->with('error', 'No topics available for this subject.');
            }
        }

        if ($selectedTopic) {
            $mcqs = Mcq::with('answers')
                        ->where('topic_id', $selectedTopic)
                        ->get();
        }

        return view('user.dashborad.index', compact(
            'pageTitle','admissions','departments','subjects','topics',
            'selectedAdmission','selectedDepartment','selectedSubject','selectedTopic','mcqs'
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
