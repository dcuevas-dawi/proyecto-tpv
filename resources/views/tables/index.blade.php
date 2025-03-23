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
    </div>
</x-app-layout>
