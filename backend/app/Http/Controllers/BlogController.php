<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($blogs);
    }

    public function show(Blog $blog)
    {
        $blog->load('user');
        return response()->json($blog);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = $request->file('image')?->store('images', 'public');

        $blog = Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $path,
            'user_id' => auth()->id(),
        ]);

        return response()->json($blog, 201);
    }

    public function update(Request $request, Blog $blog)
    {
        if ($blog->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($blog->image);
            $blog->image = $request->file('image')->store('images', 'public');
        }

        $blog->update($request->only('title', 'content'));

        return response()->json($blog);
    }

    public function getUserBlogs()
    {
        // Fetch blogs where the user_id matches the authenticated user's id
        $blogs = Blog::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc') // Optionally, order by creation date
            ->paginate(10); // You can adjust pagination as needed

        return response()->json($blogs);
    }

    public function destroy(Blog $blog)
    {
        if ($blog->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($blog->image);
        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully']);
    }
}

