@extends('layouts.guest')
@section('title', 'Citas')
@section('content')
<section class="citas">
    <div class="container-fluid py-3">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 col-xxl-9">
                <div class="card shadow">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 col-xxl-3">
                <div class="card shadow">
                    <div class="card-body">
                        <h6 class="text-center m-0">Citas</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Floating Buttons -->
    <div class="floating-button-group">
        <a href="{{ route('citas.create') }}" class="btn btn-primary floating-button shadow d-flex justify-content-center align-items-center"><i class="bi bi-calendar2-plus-fill"></i></a>
        <button class="btn btn-primary floating-button shadow d-flex justify-content-center align-items-center"><i class="bi bi-calendar2-event-fill"></i></button>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('vendor/fullcalendar/dist/index.global.js') }}"></script>
<script src="{{ asset('vendor/fullcalendar/packages/core/locales/es.global.js') }}"></script>
<script>
    //----
    let calendarEl = document.querySelector('#calendar')
    //----
    document.addEventListener('DOMContentLoaded', () => {
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            selectable: true,
            unselectAuto: true,
            dateClick: (info) => {
                console.log(info)
            },
            unselect: () => {
                console.log('unselect')
            }
        })
        calendar.render()
    })
</script>
@endpush