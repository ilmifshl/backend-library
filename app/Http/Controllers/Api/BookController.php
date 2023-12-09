<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return $books->isNotEmpty()
            ? response()->json(['status' => 200, 'books' => $books], 200)
            : response()->json(['status' => 404, 'message' => 'No Records Found'], 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:191',
            'author' => 'required|string|max:191',
            'category_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'error' => $validator->messages()], 422);
        }

        $existingBook = Book::where('title', $request->title)->where('author', $request->author)->first();

        if($existingBook){
            $existingBook->quantity += $request->quantity;
            $existingBook->save();
            $book = $existingBook;
        } else{
            $book = Book::create($request->only(['title', 'author', 'category_id', 'quantity']));
        }

        return $book
            ? response()->json(['status' => 200, 'message' => 'Book added successfully'], 200)
            : response()->json(['status' => 500, 'message' => 'Something went wrong'], 500);
    }

    public function show($id)
    {
        $book = Book::find($id);
        return $book
            ? response()->json($book, 200)
            : response()->json(['message' => 'Book not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $book->update($request->only([
            'title', 'author', 'category_id', 'quantity'
        ]));
        return response()->json($book, 200);
    }

    public function destroy($id)
    {   
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        $book->delete();
        return response()->json(['message' => 'Book deleted'], 200);
    }
}
