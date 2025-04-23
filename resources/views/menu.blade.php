<!-- Main menu view -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menú') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (session('employee_role') == 1)

                    <div class="flex flex-col gap-4 p-6 text-gray-900">
                        <a href="{{ route('tables.index') }}" class="w-fit text-7xl text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300  shadow-lg shadow-blue-500/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Mesas
                        </a>
                    </div>

                    <div class="flex flex-col gap-4 p-6 text-gray-900">
                        <a href="{{ route('products.index') }}" class="w-fit text-7xl text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300  shadow-lg shadow-blue-500/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Productos
                        </a>
                    </div>

                @endif

                <div class="flex flex-col gap-4 p-6 text-gray-900">
                    <a href="{{ route('orders.history') }}" class="w-fit text-7xl text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300  shadow-lg shadow-blue-500/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                        Historial de Tickets
                    </a>
                </div>

                @if (session('employee_role') == 1)
                    <div class="flex flex-col gap-4 p-6 text-gray-900">
                        <a href="{{ route('accounting.index') }}" class="w-fit text-7xl text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300  shadow-lg shadow-blue-500/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Contabilidad
                        </a>
                    </div>

                    <div class="flex flex-col gap-4 p-6 text-gray-900">
                        <a href="{{ route('stablishment_details.edit') }}" class="w-fit text-7xl text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300  shadow-lg shadow-blue-500/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Datos del Establecimiento
                        </a>
                    </div>

                        <div class="flex flex-col gap-4 p-6 text-gray-900">
                            <a href="{{ route('employee.create') }}" class="w-fit text-7xl text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 shadow-lg shadow-blue-500/50 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Crear empleado
                            </a>
                        </div>

                @endif

            </div>
        </div>
    </div>
</x-app-layout>
