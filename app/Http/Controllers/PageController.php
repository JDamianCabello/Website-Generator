<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageTemplate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('template')->get();
        return Inertia::render('Dashboard/Pages/Index', compact('pages'));
    }

    public function create()
    {
        $templates = PageTemplate::all();
        return Inertia::render('Dashboard/Pages/Create', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages,slug',
            'template_id' => 'required|exists:page_templates,id',
            'data' => 'required|array',
        ]);

        Page::create($request->all());

        return redirect()->route('dashboard.pages.index')->with('success', 'Página creada correctamente.');
    }

    public function show(Page $page)
    {
        return Inertia::render('Dashboard/Pages/Show', [
            'page' => $page->load('template')
        ]);
    }

    public function edit(Page $page)
    {
        return Inertia::render('Dashboard/Pages/Edit', [
            'page' => $page->load('template')
        ]);
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => "required|string|unique:pages,slug,{$page->id}",
            'data' => 'required|array',
        ]);

        $page->update($request->all());

        return redirect()->route('dashboard.pages.index')->with('success', 'Página actualizada correctamente.');
    }

}
