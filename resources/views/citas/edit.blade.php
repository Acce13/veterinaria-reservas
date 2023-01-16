@extends('layouts.guest')
@section('title', 'Reservar')
@section('content')
<section class="citas">
    <div class="container py-3">
        <div class="row g-0 justify-content-center">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-7 col-xxl-6">
                <div class="card shadow mb-2">
                    <div class="card-body">
                        <h6>Información de la cita</h6>
                        <p class="m-0">Fecha: {{ $cita->fecha }}</p>
                        <p class="m-0">Hora: {{ $cita->hora->hora }}</p>
                        <p class="m-0">Paciente: {{ $cita->cliente->nombre_mascota }}</p>
                        <p class="m-0">Estado: <span class="badge {{ ($cita->reservado) ? 'bg-success' : 'bg-danger' }}">{{ ($cita->reservado) ? 'Reservado' : 'Finalizado' }}</span></p>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        @if (session('warning'))
                            <div class="alert alert-warning">{{ session('warning') }}</div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form action="{{ route('citas.update', $cita) }}" method="POST">
                            @csrf
                            @method('put')
                            <div class="mb-3">
                                <label for="inputFecha" class="form-label">Nueva Fecha <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="inputFecha" name="fecha" placeholder="Fecha" value="{{ old('fecha') }}" required>
                                @error('fecha') <span class="text-center">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputHora" class="form-label">Hora <span class="text-danger">*</span></label>
                                <select class="form-control" id="inputHora" name="hora_id" required>
                                    <option value="">--Seleccione una hora disponible--</option>
                                </select>
                                @error('hora') <span class="text-center">{{ $message }}</span> @enderror
                            </div>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary rounded-pill">Actualizar</button>
                                        </div>  
                                    </div>
                                    <div class="col-6">
                                        <div class="d-grid">
                                            <button type="button" class="btn btn-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#exampleModal">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Floating Buttons -->
    <div class="floating-button-group">
        <a href="{{ route('citas.index') }}" class="btn btn-primary floating-button shadow d-flex justify-content-center align-items-center"><i class="bi bi-arrow-left"></i></a>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('citas.destroy', $cita) }}" method="POST">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cancelar Reserva</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="m-0">¿Estás seguro de cancelar la reserva?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    $("#inputFecha").on('change', (event) => {
        $.ajax({
            url: `{{ route('citas.getAvailableHours') }}`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                fecha: event.target.value,
            },
            beforeSend: () => {
                $("#inputHora").html('');
            },
            success: (response) => {
                $("#inputHora").append(`<option value="">--Seleccione una hora disponible--</option>`)
                if (response.data.length > 0) {
                    response.data.map(hora => {
                        $("#inputHora").append(`
                            <option value="${ hora.id }">${ hora.hora }</option>
                        `)
                    })
                }
            },
            error: (error) => {
                $("#inputHora").append(`<option value="">--Seleccione una hora disponible--</option>`)
            }
        })
    });
</script>
@endpush