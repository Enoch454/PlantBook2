<?php

namespace App\Http\Controllers;

use App\Models\Planta;
use Illuminate\Http\Request;

class PlantaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Planta::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $planta = [
            'nombre' => $request->nombre,
            'nCientifico' => $request->nCientifico,
            'nAlterno' => $request->nAlterno,
            'info' => $request->info,
            //el metodo store devuelve la ubicacion a partir del fichero public,
            //sin embargo, la peticion get para obtener la imagen solo pide la
            //direccion a partir de planta_img. Por ello, se procesa la cadena 
            //para que cumpla con ese formato.
            'pathImagen' => substr($request->pathImagen->store('public/planta_img'), 7),
        ];
//        if($request->hasFile('pathImagen')){
//            $planta['pathImagen'] = $request->file('pathImagen')->store('planta_img');
//        }
        Planta::create($planta);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Planta  $planta
     * @return \Illuminate\Http\Response
     */
    public function show(Planta $planta)
    {
        return $planta;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Planta  $planta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Planta $planta)
    {
        $planta->nombre = $request->nombre;
        $planta->ncientifico = $request->nCientifico;
        $planta->nAlterno = $request->nAlterno;
        $planta->info = $request->info;
        /*
        if($request->hasFile('pathImagen')){
            $planta->pathImagen = substr($request->pathImagen->store('public/planta_img'), 7);
        }
        */
        //$planta->update($new_planta);
        $planta->save();

    }

    /**
     * Función de controlador enfocada a actualizar
     * la imagen de una ficha
     * 
     *  @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function updateImg(Request $request)
    {
        //$planta = Planta::find($request->plantaId);
        $planta = Planta::where('id', $request->plantaId)->first();
        $imagen = substr($request->pathImagen->store('public/planta_img'), 7);

        $planta->pathImagen = $imagen;
        $planta->save(); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Planta  $planta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Planta $planta)
    {
        //
        $planta->delete();

    }
}
