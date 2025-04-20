<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Identificación de empleado
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('employee.authenticate') }}">
                    @csrf

                    <!-- Empleado -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Selecciona empleado</label>
                        <select name="employee_id"
                                class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600"
                                required>
                            <option value="">Selecciona...</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- PIN -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">PIN</label>
                        <input type="password" name="employee_pin"
                               class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600"
                               required>
                    </div>

                    <!-- Botón -->
                    <div>
                        <button type="submit"
                                class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                            Acceder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
