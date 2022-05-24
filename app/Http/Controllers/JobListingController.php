<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobListingController extends Controller
{
    /**
     * Construct function
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['create','edit','store','update','destroy', 'manage']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home_list()
    {
        return view('listing.index')->with([
            'listings' => JobListing::latest()->filter(request(['tag', 'search']))->paginate(10)
        ]);
    }

    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('listing.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'company'     => ['required'],
            'location'    => 'required',
            'email'       => ['required', 'email'],
            'website'     => 'required',
            'logo'        => ['image'],
            'tags'        => 'required',
            'description' => 'required',
        ]);

        try {
            $logo = null;
            if (!empty($request->file('logo'))) {
                $logo = time() . '-' . $request->file('logo')->getClientOriginalName();
                $request->file('logo')->storeAs('public/logos', $logo);
            }

            JobListing::create([
                'user_id'     => auth()->id(),
                'title'       => $request->title,
                'logo'        => $logo,
                'company'     => $request->company,
                'location'    => $request->location,
                'email'       => $request->email,
                'website'     => $request->website,
                'tags'        => $request->tags,
                'description' => $request->description,
            ]);

            return redirect('/')->with('message', "Listing Added Successfully!");
        } catch (\Throwable $th) {
            return redirect('/')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobListing  $jobListing
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('listing.show')->with([
            'listing' => JobListing::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobListing  $jobListing
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('listing.edit')->with([
            'listing'    => JobListing::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobListing  $jobListing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobListing $jobListing, $id)
    {
        // Make sure logged in user is owner
        // if($jobListing->user_id != auth()->id()) {
        //     abort(403, 'Unauthorized Action');
        // }

        $request->validate([
            'title'       => 'required',
            'company'     => ['required'],
            'location'    => 'required',
            'email'       => ['required', 'email'],
            'website'     => 'required',
            'logo'        => ['image'],
            'tags'        => 'required',
            'description' => 'required',
        ]);

        try {
            $logo = null;
            $logo = JobListing::find($id)->logo;
            if (!empty($request->file('logo'))) {
                Storage::delete('public/logos/'.$logo);
                $logo = time() . '-' . $request->file('logo')->getClientOriginalName();
                $request->file('logo')->storeAs('public/logos', $logo);
            }

            JobListing::find($id)->update([
                'user_id'     => auth()->id(),
                'title'       => $request->title,
                'logo'        => $logo,
                'company'     => $request->company,
                'location'    => $request->location,
                'email'       => $request->email,
                'website'     => $request->website,
                'tags'        => $request->tags,
                'description' => $request->description,
            ]);

            return back()->with('message', "Listing Update Successfully!");
        } catch (\Throwable $th) {
            return redirect('/')->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobListing  $jobListing
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobListing $jobListing, $id)
    {
        // Make sure logged in user is owner
        // if($jobListing->user_id != auth()->id()) {
        //     abort(403, 'Unauthorized Action');
        // }

        $logo = JobListing::find($id);
        Storage::delete('public/logos/'.$logo->logo);
        JobListing::find($id)->delete();
        return redirect()->route('home')->with('message', "Listing Deleted");
    }

    /**
     * Show listings of an user.
     *
     * @return \Illuminate\Http\Response
     */
    public function manage()
    {
        return view('listing.manage')->with([
            'listings' => JobListing::where('user_id', Auth::user()->id)->orderBy('id','DESC')->paginate(10),
        ]);
    }
}
