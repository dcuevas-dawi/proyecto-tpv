<!-- View for employee login -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Identificación de empleado
        </h2>
    </x-slot>

    <div class="py-12 flex justify-center items-center w-full">
        <div class="flex flex-row gap-6 w-full max-w-4xl px-4">

            <div class="w-full w-1/2 bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('employee.authenticate') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Selecciona empleado</label>
                        <select name="employee_id" id="employee_id"
                                class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600"
                                required>
                            <option value="" class="text-3xl">Selecciona...</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" class="text-3xl">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">PIN</label>
                        <input type="password" id="employee_pin" name="employee_pin"
                               class="block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-2 focus:ring-green-600 focus:border-green-600"
                               required>
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">
                            Acceder
                        </button>
                    </div>
                </form>
            </div>

            <div class="w-full w-1/2 bg-white overflow-hidden shadow-sm rounded-lg p-6">
                <div class="flex flex-col items-center space-y-4">
                    <div class="grid grid-cols-3 gap-4 w-full">
                        <button type="button" class="numpad-key h-16 text-2xl font-bold bg-gray-100 rounded-md hover:bg-gray-200" data-value="1">1</button>
                        <button type="button" class="numpad-key h-16 text-2xl font-bold bg-gray-100 rounded-md hover:bg-gray-200" data-value="2">2</button>
                        <button type="button" class="numpad-key h-16 text-2xl font-bold bg-gray-100 rounded-md hover:bg-gray-200" data-value="3">3</button>
                        <button type="button" class="numpad-key h-16 text-2xl font-bold bg-gray-100 rounded-md hover:bg-gray-200" data-value="4">4</button>
                        <button type="button" class="numpad-key h-16 text-2xl font-bold bg-gray-100 rounded-md hover:bg-gray-200" data-value="5">5</button>
                        <button type="button" class="numpad-key h-16 text-2xl font-bold bg-gray-100 rounded-md hover:bg-gray-200" data-value="6">6</button>
                        <button type="button" class="numpad-key h-16 text-2xl font-bold bg-gray-100 rounded-md hover:bg-gray-200" data-value="7">7</button>
                        <button type="button" class="numpad-key h-16 text-2xl font-bold bg-gray-100 rounded-md hover:bg-gray-200" data-value="8">8</button>
                        <button type="button" class="numpad-key h-16 text-2xl font-bold bg-gray-100 rounded-md hover:bg-gray-200" data-value="9">9</button>
                        <button type="button" class="clear-pin h-16 text-xl font-bold bg-red-500 text-white rounded-md hover:bg-red-600 col-span-1" data-value="clear">Borrar</button>
                        <button type="button" class="numpad-key h-16 text-2xl font-bold bg-gray-100 rounded-md hover:bg-gray-200" data-value="0">0</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/employee_login.js') }}"></script>
    @endpush
</x-app-layout>
