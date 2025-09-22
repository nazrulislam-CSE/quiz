<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Menuitem;
use App\Models\Contact;
use App\Models\About;

use App\Models\Topic;
use App\Models\Subject;
use App\Models\Admission;
use App\Models\Department;
use App\Models\Mcq;
use App\Models\ExamResult;
use App\Models\BalanceRequest;
use Illuminate\Support\Carbon;

class MenuPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$url)
    {
        // dd($request->all());
        $page = Page::where('page_slug',$url)->first();
        $pageTitle = $page->page_name;
        $abouts = About::where('status',1)->latest()->get();
        // dd($tours);

        // mcq exam //
        $submitted = false;
        $correct = 0;
        $wrong = 0;
        $score = 0;
        $total = 0;

        $admissions = Admission::where('status', 1)->orderBy('id', 'asc')->take(10)->get();

        $selectedAdmission = $request->query('admission');
        $selectedDepartment = $request->query('department');
        $selectedSubject = $request->query('subject');
        $selectedTopic = $request->query('topic');
        $examStart = $request->query('exam');
        $studyMode = $request->query('study'); 

        $departments = collect();
        $subjects = collect();
        $topics = collect();
        $mcqs = collect();

        if ($selectedAdmission) {
            $departments = Department::where('admission_id', $selectedAdmission)->where('status',1)->orderBy('id', 'asc')->get();
            if ($departments->isEmpty() && !$selectedDepartment) {
                return redirect()->route('menu.page',$page->page_slug)->with('error', 'No departments available for this admission.');
            }
        }

        if ($selectedDepartment) {
            $subjects = Subject::where('department_id', $selectedDepartment)->where('status',1)->orderBy('id', 'asc')->get();
            if ($subjects->isEmpty() && !$selectedSubject) {
                return redirect()->route('menu.page',$page->page_slug)->with('error', 'No subjects available for this department.');
            }
        }

        if ($selectedSubject) {
            $topics = Topic::where('subject_id', $selectedSubject)->where('status', 1)->where('type', 2)->orderBy('id', 'asc')->take(10)->get();

            if ($topics->isEmpty() && !$selectedTopic) {
                return redirect()->route('menu.page',$page->page_slug)->with('error', 'No topics available for this subject.');
            }
        }

        // study start
        if ($selectedTopic && $studyMode) {
            $mcqs = Mcq::with('answers')
                        ->where('topic_id', $selectedTopic)
                        ->get();

            if ($mcqs->isEmpty()) {
                return redirect()->route('menu.page', [
                    'url'=>$page->page_slug,
                    'admission' => $selectedAdmission,
                    'department' => $selectedDepartment,
                    'subject' => $selectedSubject,
                    'topic' => $selectedTopic,
                ])->with('error', 'This topic has no questions.');
            }
        }

        // exam start
        if ($selectedTopic && $examStart) {

            $selectedTopicData = Topic::find($selectedTopic);

            if (!$selectedTopicData) {
                return redirect()->back()->with('error', 'টপিক পাওয়া যায়নি।');
            }

            $examFee = $selectedTopicData->fee;


            $mcqs = Mcq::with('answers')
                        ->where('topic_id', $selectedTopic)
                        ->get();

            if ($mcqs->isEmpty()) {
                return redirect()->route('menu.page', [
                    'url'=>$page->page_slug,
                    'admission' => $selectedAdmission,
                    'department' => $selectedDepartment,
                    'subject' => $selectedSubject,
                    'topic' => $selectedTopic,
                ])->with('error', 'This topic has no questions.');
            }
        }


        return view('frontend.menu.index',compact('page','pageTitle','abouts','admissions','departments','subjects','topics',
            'selectedAdmission','selectedDepartment','selectedSubject','selectedTopic','mcqs','examStart','studyMode'));
    }

    public function submit(Request $request)
    {
        // dd($request->all());
        $pageTitle = "Exam Result";
        $answers = $request->input('answers', []);
        $mcqs = Mcq::with('answers')->whereIn('id', array_keys($answers))->get();

        $total = $mcqs->count();
        $correct = 0;
        $wrong = 0;

        foreach($mcqs as $mcq){
            $givenAnswerId = $answers[$mcq->id] ?? null;
            $correctAnswer = $mcq->answers->where('is_correct',1)->first();

            if($givenAnswerId && $correctAnswer && $givenAnswerId == $correctAnswer->id){
                $correct++;
            } else {
                $wrong++;
            }
        }

        $score = $total > 0 ? round(($correct/$total)*100, 2) : 0;

        // Calculate time taken using session
        $timeTaken = $request->time_taken;
        

        $alreadyExists = ExamResult::where('admission_id', $request->admission)
            ->where('department_id', $request->department)
            ->where('subject_id', $request->subject)
            ->where('topic_id', $request->topic)
            ->first();

        // ✅ Only insert if not already exists
        if (!$alreadyExists) {
            $examResult = ExamResult::create([
                'user_id'       => '1',
                'admission_id'  => $request->admission,
                'department_id' => $request->department,
                'subject_id'    => $request->subject,
                'topic_id'      => $request->topic,
                'total'         => $total,
                'correct'       => $correct,
                'wrong'         => $wrong,
                'score'         => $score,
                'time_taken'    => $timeTaken,
                'given_answers' => $answers,
            ]);
        } else {
            $examResult = $alreadyExists;
        }

        $examResult = ExamResult::with(['user','admission','department','subject','topic'])->find($examResult->id);

        $givenAnswers = $examResult->given_answers;

        return view('frontend.exam.result', [
            'pageTitle' => "Exam Result",
            'examResult' => $examResult,
            'total' => $examResult->total,
            'correct' => $examResult->correct,
            'wrong' => $examResult->wrong,
            'score' => $examResult->score,
            'timeTaken' => $examResult->time_taken,
            'mcqs' => Mcq::with('answers')->whereIn('id', array_keys($givenAnswers ?? []))->get(),
            'answers' => $givenAnswers,
        ]);
    }

    // search result show 
    public function SearchResult(Request $request){
        // dd($request->all());
        // Retrieve search criteria from the request
        $roll = $request->input('roll');
        $registration = $request->input('registration');
        $courseName = $request->input('course_name');

        // Query to filter results based on the provided criteria
        $resultsQuery = Result::query()
            ->where('status', 1)
            ->when($roll, function ($query) use ($roll) {
                return $query->where('roll_number', $roll);
            })
            ->when($registration, function ($query) use ($registration) {
                return $query->where('reg_number', $registration);
            })
            ->when($courseName, function ($query) use ($courseName) {
                return $query->where('course_name', $courseName);
            });

        // Execute the query and get the results
        $results = $resultsQuery->get();
        // dd($results);

        $pageTitle = 'Search Results';
        // You can return the results to a view or do further processing here
        return view('frontend.menu.search_results', compact('results','pageTitle'));
    }


    public function FooterPages($page)
    {
        // single page
        $page = Page::where('page_slug',$page)->first();

        // popular all pages list
        $popular_pages = Menuitem::with(['subMenus.childMenus'])->whereNull('parent_id')->whereHas('get_menu', function($query){ $query->where('location','footer1');})->orderby('position', 'asc')->get();
        $pageTitle = $page->page_name;
        return view('frontend.menu.page_index',compact('page','popular_pages','pageTitle'));
    }


    public function ContactPages(Request $request)
    {

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ];


        // Save to the database using the Contact model
        Contact::create($data);

        // Mail::to($data['email'])->send(new ContactFormMail($data));

        flash()->addSuccess("Your Information Sent Successfully.");
        $url = '/pages/contact-us';
        return redirect($url);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
}
