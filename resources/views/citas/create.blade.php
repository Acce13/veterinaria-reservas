@extends('layouts.guest')
@section('title', 'Reservar')
@section('content')
<section class="citas">
    <div class="container py-3">
        <div class="row g-0 justify-content-center">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-7 col-xxl-6">
                <div class="card shadow">
                    <div class="card-body">
                        <form action="{{ route('citas.store') }}" method="post">
                            @csrf
                            @method('post')
                            <div class="mb-3">
                                <label for="inputNumeroDocumento" class="form-label">Número de Documento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inputNumeroDocumento" name="numero_documento" placeholder="Número de Documento" value="{{ old('numero_documento') }}" required>
                                @error('numero_documento') <span class="text-center">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputNombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inputNombre" name="nombre" placeholder="Nombre" value="{{ old('nombre') }}" required>
                                @error('nombre') <span class="text-center">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputApellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inputApellido" name="apellido" placeholder="Apellido" value="{{ old('apellido') }}" required>
                                @error('apellido') <span class="text-center">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputNombreMascota" class="form-label">Nombre de la mascota <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inputNombreMascota" name="nombre_mascota" placeholder="Nombre de la mascota" value="{{ old('nombre_mascota') }}" required>
                                @error('nombre_mascota') <span class="text-center">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="inputFecha" class="form-label">Fecha <span class="text-danger">*</span></label>
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
                                <div class="d-grid">
                                    <button class="btn btn-primary rounded-pill">Reservar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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