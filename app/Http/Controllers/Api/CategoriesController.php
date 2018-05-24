<?php

namespace App\Http\Controllers\Api;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    /**
     * Get a listing of the category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::forUser(auth()->user())->get();

        return $categories;
    }

    /**
     * Store a newly created category in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Category);

        $newCategory = $request->validate([
            'name'        => 'required|max:60',
            'color'       => 'required|string|max:7',
            'description' => 'nullable|string|max:255',
        ]);
        $newCategory['creator_id'] = auth()->id();

        $category = Category::create($newCategory);

        return response()->json([
            'message' => __('category.created'),
            'data'    => $category,
        ], 201);
    }

    /**
     * Update the specified category in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $categoryData = $request->validate([
            'name'        => 'required|max:60',
            'color'       => 'required|string|max:7',
            'description' => 'nullable|string|max:255',
        ]);
        $category->update($categoryData);

        return response()->json([
            'message' => __('category.updated'),
            'data'    => $category,
        ]);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param \App\Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        request()->validate([
            'category_id' => 'required',
        ]);

        if (request('category_id') == $category->id && $category->delete()) {
            return response()->json(['message' => __('category.deleted')]);
        }

        return response()->json('Unprocessable Entity.', 422);
    }
}
