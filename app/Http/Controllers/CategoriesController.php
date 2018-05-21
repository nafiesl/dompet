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
        $categories = Category::all();

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

        $newCategory = $this->validate($request, [
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newCategory['creator_id'] = auth()->id();

        Category::create($newCategory);

        flash(__('category.created'), 'success');

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

        $categoryData = $this->validate($request, [
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);

        $category->update($categoryData);

        flash(__('category.updated'), 'success');

        return redirect()->route('categories.index');
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

        request()->validate([
            'category_id' => 'required',
        ]);

        if (request('category_id') == $category->id && $category->delete()) {
            flash(__('category.deleted'), 'warning');
            return redirect()->route('categories.index');
        }

        flash(__('category.undeleted'), 'warning');
        return back();
    }
}
