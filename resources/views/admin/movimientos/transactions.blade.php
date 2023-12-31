@extends('admin.layouts.master')

@section('content')

<section class="section">
    <div>
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1 class="mr-auto">Todos los movimientos</h1>
            <div class="card-header-action text-right">
                <a href="{{ URL::previous() }}" class="btn btn-primary">Atrás</a>
            </div>
        </div>

        <div class="mt-5 mb-">
            <div class="mb-3 text-right">
                <button id="toggleFormBtn" class="btn btn-primary" value="movimiento">
                    Agregar Movimiento
                </button>
            </div>

            <form id="movimientoForm" action="{{ route('movimientos.agregar', ['accion' => 'movimiento']) }}" method="POST" style="display: none;">
                @csrf
                <div class="mb-3">
                    <label for="nombre_cliente" class="form-label">Nombre del Cliente:</label>
                    <input type="text" class="form-control" name="nombre_cliente" required>
                </div>
                <div class="mb-3">
                    <label for="concepto" class="form-label">Concepto de Movimiento:</label>
                    <input type="text" class="form-control" name="concepto" required>
                </div>
                <div class="mb-3">
                    <label for="monto" class="form-label">Monto:</label>
                    <input type="number" class="form-control" name="monto" required>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo:</label>
                    <select name="tipo" class="form-control" required>
                        <option value="Cobro">Cobro</option>
                        <option value="Pago">Pago</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
        </div>
        <div class="mt-4">
            <table class="table table-striped">
                <thead>
                    <tr class="text-center">
                        <th>Id</th>
                        <th>Nombre del Cliente</th>
                        <th>Tipo</th>
                        <th>Concepto de Movimiento</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movimientos as $movimiento)
                        <tr class="text-center">
                            <td>{{ $movimiento->id }}</td>
                            <td>{{ $movimiento->nombre_cliente }}</td>
                            <td>
                                @if ($movimiento->tipo === 'Cobro')
                                    <span class="text-success font-weight-bold">Ingreso</span>
                                @else
                                    <span class="text-danger font-weight-bold">Egreso</span>
                                @endif
                            </td>
                            <td>{{ $movimiento->concepto }}</td>
                            <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</td>
                            <td>${{ number_format($movimiento->monto, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    var toggleFormBtn = document.getElementById('toggleFormBtn');
    var movimientoForm = document.getElementById('movimientoForm');
    var tipoInput = document.querySelector('input[name="tipo"]');

    toggleFormBtn.addEventListener('click', function() {
        if (movimientoForm.style.display === 'block') {
            movimientoForm.style.display = 'none';
        } else {
            movimientoForm.style.display = 'block';
            tipoInput.value = 'movimiento';
        }
    });
</script>

@endsection
