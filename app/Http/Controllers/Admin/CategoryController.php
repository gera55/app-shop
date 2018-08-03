<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Category;

class CategoryController extends Controller
{
   	public function index()
    {
    	$categories = Category::orderBy('id')->paginate(10);

    	return view('admin.categories.index')->with(compact('categories')); //listado
    }

    public function create()
    {
    	return view('admin.categories.create'); //formulario de registro
    }

    public function store(Request $request)
    {
        $this->validate($request, Category::$rules, Category::$messages);

    	$category = Category::create($request->only('name', 'description')); //mass assignment - asignación masiva

        if ($request->hasFile('image')){
            $file = $request->file('image');
                $path = public_path() . '/images/categories';
                $fileName = uniqid() . '-' . $file->getClientOriginalName();
                $moved = $file->move($path, $fileName);
                // update category
                if ($moved) {                    
                    $category->image = $fileName;
                    $category->save(); //UPDATE
                }
        }

    	return redirect('/admin/categories');
    }

    public function edit(Category $category)
    {
    	return view('admin.categories.edit')->with(compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, Category::$rules, Category::$messages);

    	$category->update($request->all());

    	return redirect('/admin/categories');
    }

    public function destroy(Category $category)
    {
        $category->delete(); //DELETE
        return back();
    }
}
