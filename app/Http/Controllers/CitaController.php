<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Hora;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        //Consultamos si el cliente ya habia hecho una reserva en esa fecha
        $cita = Cita::where([ ['cliente_id', $cliente->id], ['fecha', $request->fecha], ['reservado', true] ])->first();
        //Validamos si ya tiene reserva en esa fecha
        if ($cita) return redirect()->back()->with('warning', 'Ya tiene una reserva asignada');
        //Registramos la reserva
        Cita::create([
            'cliente_id' => $cliente->id,
            'hora_id' => $request->hora_id,
            'fecha' => $request->fecha,
        ]);
        //Volvemos al formulario de reserva
        return redirect()->back()->with('success', 'Su cita ha sido asignada exitosamente');
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
    public function edit(Cita $cita)
    {
        return view('citas.edit', compact('cita'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cita $cita)
    {
        //Validamos los campos requeridos
        $request->validate([ 'fecha' => 'required|date|after:'.Carbon::now()->format('Y-m-d'), 'hora_id' => 'required' ]);
        //Validamos el estado de la fecha
        if (!$cita->reservado) return redirect()->back()->with('warning', 'La reserva ya no se encuentra disponible');
        //Validamos la diferencia en horas
        $hora_reservada = Carbon::parse($cita->fecha." ".$cita->hora->hora);
        $hora_actual = Carbon::parse(Carbon::now()->format('Y-m-d H:m:s'));
        $diferencia = $hora_reservada->diffInHours($hora_actual);
        if ($diferencia < 3) return redirect()->back()->with('warning', 'Ya no puedes modificar la fecha y hora de reserva');
        //Actualizamos los campos
        $cita->update($request->all());
        //Retornamos a la vista
        return redirect()->back()->with('success', 'Su reserva ha sido actualizada con exito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cita $cita)
    {
        //Validamos el estado de la fecha
        if (!$cita->reservado) return redirect()->back()->with('warning', 'La reserva ya no se encuentra disponible');
        //Validamos la diferencia en horas
        $hora_reservada = Carbon::parse($cita->fecha." ".$cita->hora->hora);
        $hora_actual = Carbon::parse(Carbon::now()->format('Y-m-d H:m:s'));
        $diferencia = $hora_reservada->diffInHours($hora_actual);
        if ($diferencia < 3) return redirect()->back()->with('warning', 'Ya no puedes modificar la fecha y hora de reserva');
        //Actualizamos los campos
        $cita->reservado = false;
        $cita->save();
        //Retornamos a la vista
        return redirect()->back()->with('success', 'Su reserva ha sido actualizada con exito');
    }

    //------------------------------
    /**
     * 
     */
    public function getHoursDay(Request $request)
    {
        //Consultamos las citas en base a la fecha y su estado
        $citas = Cita::with('cliente', 'hora')->where([ ['fecha', $request->fecha], ['reservado', true] ])->get();
        //Retornamos la respuesta
        return response()->json(['status' => 'success', 'data' => $citas]);
    }

    /**
     * 
     */
    public function getMyBooking(Request $request)
    {
        //Validamos los campos requeridos
        $validator = Validator::make($request->all(), [
            'numero_documento' => 'required',
            'fechaReserva' => 'required|date|after:'.Carbon::now()->format('Y-m-d')
        ]);
        //Si falla, regresamos a la vista
        if ($validator->fails()) return redirect()->back()->with('warning', 'Los campos son requeridos');
        //Consultamos el cliente
        $cliente = Cliente::where('numero_documento', $request->numero_documento)->first();
        //Si no existe, regresamos a la vista
        if (!$cliente) return redirect()->back()->with('warning', 'No se encuentra registrado');
        //Consultamos la cita o reserva
        $cita = Cita::where([ ['cliente_id', $cliente->id], ['fecha', $request->fechaReserva], ['reservado', true] ])->first();
        //Si no hay reserva para esa fecha, regresamos a la vista
        if (!$cita) return redirect()->back()->with('warning', 'No se encontro una reserva para esta fecha '. $request->fechaReserva);
        //Si hay reserva, redireccionamos a la vista editar
        return redirect()->route('citas.edit', $cita);
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
