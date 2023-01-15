<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Hora;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('citas.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('citas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validamos los campos
        $request->validate([
            'numero_documento' => 'required',
            'nombre' => 'required',
            'apellido' => 'required',
            'nombre_mascota' => 'required',
            'fecha' => 'required|date|after:'.Carbon::now()->format('Y-m-d'),
            'hora_id' => 'required',
        ]);
        //Validamos si existe el cliente
        $cliente = Cliente::where('numero_documento', $request->numero_documento)->first();
        //Si no existe, lo registramos
        if (!$cliente) $cliente = Cliente::create($request->except('fecha', 'hora'));
        //Registramos la reserva
        Cita::create([
            'cliente_id' => $cliente->id,
            'hora_id' => $request->hora_id,
            'fecha' => $request->fecha,
        ]);
        //Volvemos al formulario de reserva
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 
     */
    public function getAvailableHours(Request $request)
    {
        //Validamos que la fecha no llegue vacia
        if (!empty($request->fecha)) {
            //Consultamos las citas
            $citas = Cita::where('fecha', $request->fecha)->get();
            //Consultamos las horas disponibles
            $horas_disponibles = Hora::whereNotIn('id', $citas->pluck('hora_id'))->get();
            //Retornamos la respuesta
            return response()->json(['status' => 'success', 'data' => $horas_disponibles]);
        }
        //Si la fecha esta vacia retornamos un array vacio
        return response()->json(['status' => 'success', 'data' => []]);
    }
}
