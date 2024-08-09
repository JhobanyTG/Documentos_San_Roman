@extends('layout.template')

@section('title', 'Cambiar Contraseña')

@section('content')
    <div class="container  mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Cambiar Contraseña') }}</div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <form method="POST" action="{{ route('change-password') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="current_password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña Actual:') }}</label>

                                <div class="col-md-8">
                                    <input id="current_password" type="password" class="form-control input_change_pass" name="current_password" required autocomplete="current-password">
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label for="new_password" class="col-md-4 col-form-label text-md-right">{{ __('Nueva Contraseña:') }}</label>

                                <div class="col-md-8">
                                    <input id="new_password" type="password" class="form-control input_change_pass" name="new_password" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label for="confirm_password" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar Nueva Contraseña:') }}</label>

                                <div class="col-md-8">
                                    <input id="confirm_password" type="password" class="form-control input_change_pass" name="confirm_password" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-3">
                                <div class="col-md-8 offset-md-4 d-flex justify-content-between">
                                    <a href="{{ route('documentos.index') }}" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</a>
                                    <button type="submit" class="btn btn-success">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> {{ __('Cambiar Contraseña') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection