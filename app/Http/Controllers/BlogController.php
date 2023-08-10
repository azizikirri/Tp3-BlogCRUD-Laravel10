<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Blog::latest()->paginate(5);

        return view('index', compact('data'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'          =>  'required',
            'product_Description'         =>  'required',
            'product_image'         =>  'required'
        ]);

        $file_name = time() . '.' . request()->product_image->getClientOriginalExtension();

        request()->product_image->move(public_path('images'), $file_name);

        $blog = new Blog;

        $blog->title = $request->title;
        $blog->product_Description = $request->product_Description;
        $blog->Active = $request->Active;
        $blog->product_image = $file_name;

        $blog->save();

        return redirect()->route('blogs.index')->with('success', 'Student Added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return view('show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title'      =>  'required',
            'product_Description'     =>  'required|email',
            'product_image'     =>  'image'
        ]);

        $product_image = $request->hidden_product_image;

        if($request->product_image != '')
        {
            $product_image = time() . '.' . request()->product_image->getClientOriginalExtension();

            request()->product_image->move(public_path('images'), $product_image);
        }

        $blog = Blog::find($request->hidden_id);

        $blog->title = $request->title;

        $blog->product_Description = $request->product_Description;

        $blog->Active = $request->Active;

        $blog->product_image = $product_image;

        $blog->save();

        return redirect()->route('blogs.index')->with('success', 'Student Data has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Student Data deleted successfully');
    }
}

