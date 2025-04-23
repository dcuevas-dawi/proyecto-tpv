<!-- Overview of all active tables -->

<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-4xl font-bold text-center mb-8">Mesas Disponibles</h2>

        <div class="grid grid-cols-4 gap-6">
            @foreach ($tables as $table)
                <a href="{{ route('tables.show', $table->number)}}">
                    <div class="table-card group relative w-full bg-green-300 rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-300 ease-in-out">
                        <div class="table-card-body p-6 flex flex-col justify-evenly h-full">
                            <h3 class="text-xl font-semibold text-gray-800">Mesa {{ $table->number }}</h3>
                            <p class="text-gray-800">Estado:
                                <span class="font-medium {{ $table->status == 0 ? 'text-green-700' : 'text-red-500' }}">
                                    {{ $table->status == 0 ? 'Libre' : 'Ocupada' }}
                                </span>
                            </p>
                            <span class="btn-select w-fit self-end bg-primary hover:bg-primaryLight text-white px-4 py-2 rounded-lg shadow-md transition duration-300 ease-in-out">
                                Seleccionar
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        @if(session('employee_role') == 1)
            <div class="flex flex-col gap-4 mt-6 text-gray-900">
                <a href="{{ route('tables.manage') }}" class="w-fit text-7xl text-white bg-primary hover:bg-primaryLight focus:ring-4 focus:outline-none focus:ring-blue-300  shadow-lg font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    Gestionar Mesas
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
