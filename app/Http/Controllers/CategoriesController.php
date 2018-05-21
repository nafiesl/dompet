<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $editableCategory = null;
        $categories = Category::where(function ($query) {
            $query->where('name', 'like', '%'.request('q').'%');
        })->paginate(25);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableCategory = Category::find(request('id'));
        }

        return view('categories.index', compact('categories', 'editableCategory'));
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Category);

        $this->validate($request, [
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);

        $newCategory = $request->only('name', 'description');
        $newCategory['creator_id'] = auth()->id();

        Category::create($newCategory);

        return redirect()->route('categories.index');
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $this->validate($request, [
            'name' => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);

        $routeParam = request()->only('page', 'q');

        $category->update($request->only('name', 'description'));

        return redirect()->route('categories.index', $routeParam);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $this->validate(request(), [
            'category_id' => 'required',
        ]);

        $routeParam = request()->only('page', 'q');

        if (request('category_id') == $category->id && $category->delete()) {
            return redirect()->route('categories.index', $routeParam);
        }

        return back();
    }
}
