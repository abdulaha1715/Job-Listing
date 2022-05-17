<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobListingController extends Controller
{
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
        // dd($request->logo);

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
    public function edit(JobListing $jobListing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobListing  $jobListing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobListing $jobListing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobListing  $jobListing
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobListing $jobListing)
    {
        //
    }
}
