<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear nuevo empleado
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('employee.store') }}">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del empleado</label>
                        <input type="text" name="name" required class="block w-full border-gray-300 rounded-md" />
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">PIN</label>
                        <input type="password" name="pin" required class="block w-full border-gray-300 rounded-md" />
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                        <select name="role" required class="block w-full border-gray-300 rounded-md">
                            <option value="1">Dueño</option>
                            <option value="2">Encargado</option>
                            <option value="3">Empleado</option>
                        </select>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                            Crear empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
