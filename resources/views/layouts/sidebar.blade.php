    <!-- Aside left menu -->

<div class="p-2 h-full flex flex-col">

    <div class="mb-4 flex justify-center">
        <a href="{{ route('menu') }}">
            <img class="h-14 w-auto" src="{{ asset('favicon.png') }}" alt="Logo">
            <p>TPV DC</p>
        </a>
    </div>

    <nav class="flex-1 space-y-2">

        <!-- Check if the user is logged in -->
        @if(session('employee_role'))
            <a href="{{ route('menu') }}" class="block py-2 px-4 rounded {{ request()->routeIs('menu') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                Menú Principal
            </a>

            <a href="{{ route('tables.index') }}" class="block py-2 px-4 rounded {{ request()->routeIs('tables.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                Mesas
            </a>


            <a href="{{ route('orders.history') }}" class="block py-2 px-4 rounded {{ request()->routeIs('orders.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                Tickets
            </a>

            <!-- Check if the user is an employee or owner -->
            @if(session('employee_role') == 2 || session('employee_role') == 1)
                <a href="{{ route('cash-register.history') }}" class="block py-2 px-4 rounded {{ request()->routeIs('cash-register.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                    Caja
                </a>

                <a href="{{ route('products.index') }}" class="block py-2 px-4 rounded {{ request()->routeIs('products.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                    Productos
                </a>
            @endif

            <!-- Check if the user is an owner -->
            @if(session('employee_role') == 1)
                <a href="{{ route('employee.create') }}" class="block py-2 px-4 rounded {{ request()->routeIs('employee.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                    Empleados
                </a>

                <a href="{{ route('accounting.index') }}" class="block py-2 px-4 rounded {{ request()->routeIs('accounting.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                    Contabilidad
                </a>

                <a href="{{ route('stablishment_details.edit') }}" class="block py-2 px-4 rounded {{ request()->routeIs('stablishment_details.*') ? 'bg-primary text-white' : 'hover:bg-gray-100' }}">
                    Configuración
                </a>
            @endif
        @endif
    </nav>
</div>
