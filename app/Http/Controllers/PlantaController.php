<?php

namespace App\Http\Controllers;

use App\Models\Planta;
use Illuminate\Http\Request;

class PlantaController extends Controller
{
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
        $planta = $request->all();
        if($request->hasFile('pathImagen')){
            $planta['pathImagen'] = $request->file('pathImage')->store('planta_img');
        }
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
        /*
        $planta = $request->all();
        if($request->hasFile('pathImagen')){
            $planta['pathImagen'] = $request->file('pathImage')->store('planta_img');
        }
        Planta::create($planta);
        */
        // posible falla con la actualziacion de la imagen, pendiente de checar

        $new_planta = $request->all();
        if($request->hasFile('pathImagen')){
            $new_planta['pathImagen'] = $request->file('pathImage')->store('planta_img');
        }
        $planta->update($new_planta);

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
        $planta->destroy();

    }
}
