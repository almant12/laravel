<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;


class ListingController extends Controller
{

    public function index()
    {
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }

    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    public function create()
    {
        return view('listings.create');
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            ($formFields['logo'] = $request->file('logo')->store('logos', 'public'));
        }
        $formFields['user_id'] = auth()->user()->id;
        Listing::create($formFields);
        return redirect('/')->with('message', 'Post Created Successfully!');
    }

    public function edit(Listing $listing){
        if ($listing->user_id == auth()->id()) {
            return view('listings.edit', ['listing' => $listing]);
        }else{
            abort('403','Unauthorized Action');
        }
    }

    public function update(Request $request, Listing $listing)
    {

        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email', 'unique:listings,email,'.Auth::user()->id],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);
        return redirect('/')->with('message', 'Post Updated Successfully');
    }

    public function destroy(Listing $listing){
        if ($listing->user_id == auth()->id()){
            $listing->delete();
            return redirect('/')->with('message','Post Deleted Successfully');
        }else{
            return abort(403,'Unauthorized Action');
        }
    }


    public function manage(){
        return view('listings.manage',[
            'listings'=> auth()->user()->listings()->get()
        ]);
    }

}
