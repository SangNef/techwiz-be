<div class="w-[300px] h-screen overflow-auto bg-white fixed z-auto">
    <div class="flex items-center px-6 flex-shrink-0 py-10">
        <a href="#" class="no-underline">
            <h2 class="font-bold leading-7 text-3xl text-gray-900">Expense-Voyage</h2>
        </a>
    </div>
    <ul class="flex-1 px-6 space-y-2 overflow-hidden hover:overflow-auto w-full">
        <li class="w-full hover:bg-pink-50 duration-150 py-2 hover:px-2 group"><a href="{{ route('dashboard') }}"
                class="block w-full leading-5 font-normal no-underline text-gray-500 group-hover:text-[#FF6281] rounded-md">Dashboard</a>
        </li>
        <li class="w-full hover:bg-pink-50 duration-150 py-2 hover:px-2 group"><a href="{{ route('users') }}"
                class="block w-full leading-5 font-normal no-underline text-gray-500 group-hover:text-[#FF6281] rounded-md">Users Manager</a>
        </li>
        <li class="w-full hover:bg-pink-50 duration-150 py-2 hover:px-2 group"><a href="{{ route('admins') }}"
                class="block w-full leading-5 font-normal no-underline text-gray-500 group-hover:text-[#FF6281] rounded-md">Admins Manager</a>
        </li>
        <li class="w-full hover:bg-pink-50 duration-150 py-2 hover:px-2 group"><a href="{{ route('destinations') }}"
                class="block w-full leading-5 font-normal no-underline text-gray-500 group-hover:text-[#FF6281] rounded-md">Destinations Manager</a>
        </li>
        <li class="w-full hover:bg-pink-50 duration-150 py-2 hover:px-2 group"><a href="{{ route('currencies') }}"
                class="block w-full leading-5 font-normal no-underline text-gray-500 group-hover:text-[#0f0c0d] rounded-md">Currencies Manager</a>
        </li>
        <li class="w-full hover:bg-pink-50 duration-150 py-2 hover:px-2 group"><a href="{{ route('sample') }}"
                class="block w-full leading-5 font-normal no-underline text-gray-500 group-hover:text-[#FF6281] rounded-md">Samples Manager</a>
        </li>
        {{-- <li class="w-full hover:bg-pink-50 duration-150 py-2 hover:px-2 group"><a href="{{ route('config') }}"
                class="block w-full leading-5 font-normal no-underline text-gray-500 group-hover:text-[#FF6281] rounded-md">Configs Manager</a>
        </li> --}}
    </ul>
</div>
