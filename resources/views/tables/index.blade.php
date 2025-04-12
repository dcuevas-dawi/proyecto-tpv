<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-4xl font-bold text-center mb-8">Mesas Disponibles</h2>

        <!-- Grid de mesas con animación al pasar el cursor -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($tables as $table)
                <div class="table-card group relative w-full bg-white rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out">
                    <div class="table-card-body p-6 flex flex-col justify-evenly h-full">
                        <h3 class="text-xl font-semibold text-gray-800">Mesa {{ $table->number }}</h3>
                        <p class="text-gray-500">Estado:
                            <span class="font-medium {{ $table->status == 0 ? 'text-green-500' : 'text-red-500' }}">
                                {{ $table->status == 0 ? 'Libre' : 'Ocupada' }}
                            </span>
                        </p>
                        <!-- Botón para seleccionar la mesa -->
                                <a href="{{ route('tables.show', $table->number)}}" class="btn-select w-fit self-end bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 ease-in-out group-hover:bg-blue-700">
                                    Seleccionar
                                </a>
                    </div>
                </div>
            @endforeach
        </div>
        @if(session('employee_role') == 1)
            <div class="flex flex-col gap-4 mt-6 text-gray-900 dark:text-gray-100">
                <a href="{{ route('tables.manage') }}" class="w-fit text-7xl text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    Gestionar Mesas
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
