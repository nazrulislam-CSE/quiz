<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Menuitem;
use App\Models\Contact;
use App\Models\About;
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
        return view('frontend.menu.index',compact('page','pageTitle','abouts'));
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
