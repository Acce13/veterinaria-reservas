@extends('layouts.guest')
@section('title', 'Inicio')
@section('content')
<section class="inicio d-flex align-items-center">
    <div class="container py-3">
        <div class="row gx-0 gy-3 justify-content-center">
            <div class="col-12">
                <h1 class="text-white text-uppercase text-center">Clinica Veterinaria</h1>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <a href="{{ route('citas.index') }}" class="btn inicio__btn shadow">
                        <i class="bi bi-calendar-fill inicio__btn-icon"></i>
                        <span class="inicio__btn-text text-uppercase">Reservar</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection