<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6">Gestión de Mesas</h2>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($tables as $table)
                <div class="p-4 bg-white rounded shadow flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Mesa {{ $table->number }}</h3>
                        <p class="text-gray-600">Estado:
                            <span class="{{ $table->active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $table->active ? 'Activa' : 'Desactivada' }}
                            </span>
                        </p>
                    </div>

                    <div class="mt-4">
                        @if ($table->active)
                            <form method="POST" action="{{ route('tables.deactivate', $table->id) }}">
                                @csrf
                                @method('PATCH')
                                <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    Desactivar
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('tables.activate', $table->id) }}">
                                @csrf
                                @method('PATCH')
                                <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                    Activar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            <form action="{{ route('tables.add') }}" method="POST">
                @csrf
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Añadir nueva mesa
                </button>
            </form>
        </div>

    </div>
</x-app-layout>
