<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return $categories->isNotEmpty()
            ? response()->json(['status' => 200, 'categories' => $categories], 200)
            : response()->json(['status' => 404, 'message' => 'No Records Found'], 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->messages()], 422);
        }

        $category = Category::create($request->only('category_name'));

        return $category
            ? response()->json(['status' => 200, 'message' => 'Category added successfully'], 200)
            : response()->json(['status' => 500, 'message' => 'Something went wrong'], 500);
    }

    public function show($id)
    {
        $category = Category::find($id);
        return $category
            ? response()->json($category, 200)
            : response()->json(['message' => 'Category not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->messages()], 422);
        }

        $category->category_name = $request->category_name;
        $category->save();

        return response()->json(['status' => 200, 'message' => 'Category updated successfully'], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted'], 200);
    }
}
