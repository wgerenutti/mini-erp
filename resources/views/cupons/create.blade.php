@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h4 mb-0">Novo Cupom</h2>
        <a href="{{ route('cupons.index') }}" class="btn btn-danger">Voltar</a>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="couponForm" action="{{ route('cupons.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="codigo" class="form-label">Código</label>
                        <input type="text" name="codigo" id="codigo" class="form-control" value="{{ old('codigo') }}"
                            required>
                    </div>

                    <div class="col-md-3">
                        <label for="valor_desc" class="form-label">Desconto Fixo (R$)</label>
                        <input type="text" name="valor_desc" id="valor_desc" class="form-control money"
                            value="{{ old('valor_desc') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="pct_desc" class="form-label">Desconto (%)</label>
                        <input type="text" name="pct_desc" id="pct_desc" class="form-control percent"
                            value="{{ old('pct_desc') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="minimo" class="form-label">Valor Mínimo do Subtotal (R$)</label>
                        <input type="text" name="minimo" id="minimo" class="form-control money"
                            value="{{ old('minimo', 0) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="valid_from" class="form-label">Data de Início (opcional)</label>
                        <input type="datetime-local" name="valid_from" id="valid_from" class="form-control"
                            value="{{ old('valid_from') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="valid_to" class="form-label">Data de Fim (opcional)</label>
                        <input type="datetime-local" name="valid_to" id="valid_to" class="form-control"
                            value="{{ old('valid_to') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="validade" class="form-label">Validade (Data Final)</label>
                        <input type="date" name="validade" id="validade" class="form-control"
                            value="{{ old('validade') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="uso_maximo" class="form-label">Limite de Uso (opcional)</label>
                        <input type="number" name="uso_maximo" id="uso_maximo" min="1" class="form-control"
                            value="{{ old('uso_maximo') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label d-block">Ativo</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ativo" name="ativo"
                                {{ old('ativo', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="ativo">Sim</label>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button id="submitBtn" type="submit" class="btn btn-success">
                            Salvar Cupom
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.money').maskMoney({
                prefix: 'R$ ',
                allowNegative: false,
                thousands: '.',
                decimal: ',',
                affixesStay: true
            });
            $('.percent').maskMoney({
                suffix: ' %',
                allowNegative: false,
                thousands: '',
                decimal: ',',
                precision: 2,
                affixesStay: true
            });

            $('#couponForm').on('submit', function() {
                var btn = $('#submitBtn');
                btn.prop('disabled', true);
                btn.html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Salvando...'
                    );
            });
        });
    </script>
@endpush
