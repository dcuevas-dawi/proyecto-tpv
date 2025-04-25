<!-- VIew for creating a new employee -->

<x-app-layout>
    <div class="w-full h-auto py-6 bg-gray-100">
        <div class="flex items-center justify-center mb-6">
            <h2 class="text-4xl font-bold text-center mb-8">Crear Nuevo Empleado</h2>
        </div>

        <div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="mt-4">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <form method="POST" action="{{ route('employee.store') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del empleado</label>
                        <input id="name" type="text" name="name" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600" />
                    </div>

                    <div>
                        <label for="pin" class="block text-sm font-medium text-gray-700 mb-1">PIN</label>
                        <input id="pin" type="password" name="pin" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600" />
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                        <select id="role" name="role" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600">
                            <option value="1">Dueño</option>
                            <option value="2">Encargado</option>
                            <option value="3">Empleado</option>
                        </select>
                    </div>

                    <div class="flex justify-end pt-3">
                        <button type="submit"
                                class="bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                            <i class="fas fa-user-plus mr-1"></i> Crear empleado
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actual employees list -->
        <div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Empleados Actuales</h2>

            @if(isset($employees) && count($employees) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="py-2 px-4 font-medium border-b">Nombre</th>
                            <th class="py-2 px-4 font-medium border-b">Rol</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 px-4">{{ $employee->name }}</td>
                                <td class="py-2 px-4">
                                    @if($employee->role == 1)
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">Dueño</span>
                                    @elseif($employee->role == 2)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">Encargado</span>
                                    @elseif($employee->role == 3)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">Empleado</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-center py-4">No hay empleados registrados.</p>
            @endif
        </div>
    </div>
</x-app-layout>
