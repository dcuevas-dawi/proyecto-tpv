<div class="p-4 h-full flex flex-col">
    <!-- Logo en Sidebar -->
    <div class="mb-4 flex justify-center">
        <a href="{{ route('menu') }}">
            <img class="h-14 w-auto" src="{{ asset('favicon.png') }}" alt="Logo">
            <p>TPV DC</p>
        </a>
    </div>

    <!-- Enlaces de navegación -->
    <nav class="flex-1 space-y-2">
        <a href="{{ route('menu') }}" class="block py-2 px-4 rounded {{ request()->routeIs('menu') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            Menú Principal
        </a>

        <!-- Añade aquí más enlaces de navegación según necesites -->
        <a href="{{ route('tables.index') }}" class="block py-2 px-4 rounded {{ request()->routeIs('tables.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            Mesas
        </a>

        <a href="{{ route('orders.history') }}" class="block py-2 px-4 rounded {{ request()->routeIs('orders.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
            Tickets
        </a>
        @if(session('employee_role') == 2 || session('employee_role') == 1) {{-- Solo para dueños y encargados --}}
            <a href="{{ route('accounting.index') }}" class="block py-2 px-4 rounded {{ request()->routeIs('accounting.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                Contabilidad
            </a>
        @endif

        @if(session('employee_role') == 1) {{-- Solo para dueños --}}
            <a href="{{ route('employee.create') }}" class="block py-2 px-4 rounded {{ request()->routeIs('employees.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                Empleados
            </a>
            <a href="{{ route('products.index') }}" class="block py-2 px-4 rounded {{ request()->routeIs('products.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                Productos
            </a>

            <a href="{{ route('stablishment_details.edit') }}" class="block py-2 px-4 rounded {{ request()->routeIs('stablishment_details.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                Configuración
            </a>
        @endif
    </nav>
</div>
