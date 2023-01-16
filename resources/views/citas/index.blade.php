@extends('layouts.guest')
@section('title', 'Citas')
@section('content')
<section class="citas">
    <div class="container-fluid py-3">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 col-xxl-9">
                @if (session('warning')) <div class="alert alert-warning">{{ session('warning') }}</div> @endif
                <div class="card shadow">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 col-xxl-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h6 class="text-center mb-2">Citas</h6>
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-primary" role="status" id="loading">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <ol class="list-group" id="datingList"></ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Floating Buttons -->
    <div class="floating-button-group">
        <a href="{{ route('citas.create') }}" class="btn btn-primary floating-button shadow d-flex justify-content-center align-items-center"><i class="bi bi-calendar2-plus-fill"></i></a>
        <button class="btn btn-primary floating-button shadow d-flex justify-content-center align-items-center" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-calendar2-event-fill"></i></button>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('citas.getMyBooking') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar o cancelar reserva</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="inputNumeroDocumento" class="form-label">Número de Documento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="inputNumeroDocumento" name="numero_documento" placeholder="Número de Documento" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputFechaReserva" class="form-label">Fecha de reserva <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="inputFechaReserva" name="fechaReserva" placeholder="Fecha de reserva" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Consultar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('vendor/fullcalendar/dist/index.global.js') }}"></script>
<script src="{{ asset('vendor/fullcalendar/packages/core/locales/es.global.js') }}"></script>
<script>
    //----
    let calendarEl = document.querySelector('#calendar')
    $("#loading").hide();
    //----
    document.addEventListener('DOMContentLoaded', () => {
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            selectable: true,
            unselectAuto: true,
            dateClick: (info) => {
                $("#datingList").html('');
                $.ajax({
                    url: `{{ route('citas.getHoursDay') }}`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        fecha: info.dateStr,
                    },
                    beforeSend: () => {
                        $("#loading").show();
                    },
                    success: (response) => {
                        $("#loading").hide();
                        if (response.data.length > 0) {
                            response.data.map(cita => {
                                $("#datingList").append(`
                                    <li class="list-group-item d-flex justify-content-between align-items-start border-0">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">${ cita.cliente.nombre }</div>
                                            Hora: ${ cita.hora.hora }
                                            Paciente: ${ cita.cliente.nombre_mascota }
                                        </div>
                                        <span class="badge bg-primary rounded-pill">${ cita.fecha }</span>
                                    </li>
                                `)
                            })
                        } else {
                            $("#datingList").append(`
                                <p class="text-center m-0">No hay citas reservadas para esta fecha</p>
                            `)
                        }
                    },
                    error: (error) => {
                        $("#loading").hide();
                    }
                })
            },
            unselect: () => {}
        })
        calendar.render()
    })
</script>
@endpush