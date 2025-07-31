<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create() {
        return view('categories.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);

        $category = new Category();
        $category->name = $request->name;
        $category->save();
        return redirect()->route('categories.index')->with('success', 'Category Added');
    }

    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id) {
        $request->validate(['name' => 'required']);
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();
        $category->update(['name' => $request->name]);
        return redirect()->route('categories.index')->with('success', 'Category Updated');
    }

    public function destroy($id) {
        Category::findOrFail($id)->delete();
        return redirect()->route('categories.index')->with('success', 'Category Deleted');
    }
}
