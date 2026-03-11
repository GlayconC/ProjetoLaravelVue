<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use App\Rules\CpfCnpj;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $people = Person::all();

        return response()->json($people);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pessoas',
            'documento' => ['required', 'string', 'unique:pessoas', new CpfCnpj],
            'tipo' => 'required|string|max:255',
            'telefone' => 'required|string|max:255',
        ]);

        $person = Person::create($request->all());
        return response()->json($person, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $person = Person::findOrFail($id);
        return response()->json($person);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $person = Person::findOrFail($id);

        $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:pessoas,email,' . $person->id,
            'documento' => ['sometimes', 'required', 'string', 'unique:pessoas,documento,' . $person->id, new CpfCnpj],
            'tipo' => 'sometimes|required|string|max:255',
            'telefone' => 'sometimes|required|string|max:255',
        ]);

        $person->update($request->all());
        return response()->json($person);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();
        return response()->json(['message' => 'Pessoa deletada com sucesso!']);
    }
}
