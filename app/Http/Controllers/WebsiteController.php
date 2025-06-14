<?php

namespace App\Http\Controllers;

use App\Models\WebPageSection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = WebPageSection::orderBy('order')->get();
        return view('user.admin.website.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.admin.website.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'section_id' => 'required|unique:web_page_sections,section_id|max:255',
            'in_navbar' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);
        WebPageSection::create($validated);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $section = WebPageSection::findOrFail($id);
        return view('user.admin.website.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $section = WebPageSection::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|max:255',
            'section_id' => [
                'required',
                'max:255',
                Rule::unique('web_page_sections', 'section_id')->ignore($section->id)
            ],
            'in_navbar' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $section->update($validated);
        return redirect()->back()->with('success', 'Webpage section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $webSection = WebPageSection::destroy($id);
        return redirect()->route('website.index')->with('success', 'Section deleted successfully.');

    }

    public function website()
    {
        $webPages = WebPageSection::where('is_active', 1)->orderBy('order')->get();
        $menus = WebPageSection::where('is_active', 1)->where('in_navbar', 1)->orderBy('order')->get();
        return view('welcome', compact('webPages', 'menus'));
    }
}
