<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Category;

class CategoryController extends Controller
{
   	public function index()
    {
    	$categories = Category::paginate(10);
    	return view('admin.categories.index')->with(compact('categories')); //listado
    }

    public function create()
    {
    	return view('admin.categories.create'); //formulario de registro
    }

    public function store(Request $request)
    {
        //validar
        $messages = [
            'name.required'         => 'Es necesario ingresar un nombre para la categoría.',
            'name.min'              => 'El nombre de la categoría debe tener al menos 3 caracteres.',            
            'description.max'       => 'La descripción corta solo admite hasta 250 caracteres.'  
        ];
        $rules = [
            'name' => 'required|min:3', 
            'description' => 'required|max:250'
        ];
        $this->validate($request, $rules, $messages);
    	// registrar la nueva categoria en la BD
    	Category::create($request->all()); //mass assignment - asignación masiva

    	return redirect('/admin/categories');
    }

    public function edit($id)
    {
    	//return "Mostrar aquí el formulario del categoryo con el id $id";
    	$category = Category::find($id);
    	return view('admin.categories.edit')->with(compact('category')); //formulario de registro
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'name.required'         => 'Es necesario ingresar un nombre para el categoryo.',
            'name.min'              => 'El nombre del categoryo debe tener al menos 3 caracteres.',
            'description.required'  => 'La descripción corta es un campo obligatorio.',
            'description.max'       => 'La descripción corta solo admite hasta 200 caracteres.',
            'price.required'        => 'Es obligatorio definir un precio para el categoryo.',
            'price.numeric'         => 'Ingrese un precio válido.',
            'price.min'             => 'No se admiten valores negativos.'
        ];
        $rules = [
            'name' => 'required|min:3', 
            'description' => 'required|max:200',
            'price' => 'required|numeric|min:0'
        ];
        $this->validate($request, $rules, $messages);
    	// registrar el nuevo categoryo en la BD
    	//dd($request->all());
    	$category = Category::find($id);
    	$category->name = $request->input('name');
    	$category->description = $request->input('description');
    	$category->price = $request->input('price');
    	$category->long_description = $request->input('long_description');
    	$category->save(); // UPDATE

    	return redirect('/admin/categories');
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        $category->delete(); //DELETE

        return back();
    }
}
