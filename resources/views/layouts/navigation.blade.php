<!-- Top Navigation bar -->

<nav class="bg-white border-b border-gray-200 px-6 py-3">
    <div class="flex justify-between items-center">

        <!-- Employee name and role -->
        <div class="flex items-center">
            @if(session('employee_name'))
                <span class="text-gray-600 font-medium">
                    Empleado: <span class="text-primary">{{ session('employee_name') }}</span>
                    @switch(session('employee_role'))
                        @case(1)
                            - <span class="font-semibold">Dueño</span>
                            @break
                        @case(2)
                            - <span class="font-semibold">Encargado</span>
                            @break
                        @case(3)
                            - <span class="font-semibold">Empleado</span>
                            @break
                        @default
                            - <span class="font-semibold">Sin rol</span>
                    @endswitch
                </span>
            @endif
        </div>

        <div class="flex items-center space-x-4">

            <!-- Button to change employee -->
            <form method="POST" action="{{ route('employee.logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primaryLight transition duration-200">
                    Cambiar empleado
                </button>
            </form>

            <!-- Profile menu and user logout -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center px-3 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 focus:outline-none">
                    <span>{{ Auth::user()->name }}</span>
                    <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg hidden">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/navigation.js') }}"></script>
    @endpush
</nav>
