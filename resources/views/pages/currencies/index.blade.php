@extends('layout.main')
@section('content')
    <div class="flex justify-between my-6">
        <h1 class="font-bold text-2xl leading-7">Trips List</h1>
        <button id="addNewBtn"
            class="bg-pink-400 text-white px-4 py-1 rounded hover:bg-pink-500 duration-150 ease-in-out flex items-center gap-1">
            <i class="fa-solid fa-plus"></i> Add new
        </button>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 mb-4" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="w-full bg-white rounded-lg shadow p-6 relative">
        @php
            $stt = 1;
        @endphp

        <table class="w-full border-gray-300 mt-4">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="p-2 border-b text-start">No</th>
                    <th class="p-2 border-b text-start">Name</th>
                    <th class="p-2 border-b text-start">Code</th>
                    <th class="p-2 border-b text-start">Rate</th>
                    <th class="p-2 border-b text-start">Created at</th>
                    <th class="p-2 border-b text-start">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($currencies as $currency)
                    <tr
                        class="hover:bg-gray-100 even:bg-gray-200 border-b duration-150 text-sm leading-5 font-normal text-gray-500">
                        <td class="p-2">{{ $stt++ }}</td>
                        <td class="p-2">{{ $currency->name }}</td>
                        <td class="p-2">{{ $currency->code }}</td>
                        <td class="p-2">{{ $currency->exchange_rate }}</td>
                        <td class="p-2">{{ $currency->created_at }}</td>

                        <td class="py-2 px-4 text-sm text-gray-700 flex gap-1">
                            @if ($currency->is_default)
                            @else
                                <button id="editBtn"
                                    class="bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600 transition duration-150 ease-in-out shadow-md hover:shadow-lg editBtn"
                                    data-id="{{ $currency->id }}" data-name="{{ $currency->name }}">
                                    Update
                                </button>
                                <form action="{{ route('currency.destroy', $currency->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-2 px-4 text-sm text-gray-700 text-center">No Data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="addNewPopup" class="fixed inset-0 bg-gray-500 bg-opacity-75 items-center hidden justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Add New currency</h2>
            <form action="{{ route('currency.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="flex justify-end">
                    <button type="button" id="cancelBtn"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-pink-400 text-white px-4 py-2 rounded hover:bg-pink-500">Add</button>
                </div>
            </form>
        </div>
    </div>

    <div id="updatePopup" class="fixed inset-0 bg-gray-500 bg-opacity-75 items-center hidden justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Update currency</h2>
            <form id="updateForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="update_name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="update_name"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>

                <div class="flex justify-end">
                    <button type="button" id="cancelUpdateBtn"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit"
                        class="bg-pink-400 text-white px-4 py-2 rounded hover:bg-pink-500">Update</button>
                </div>
            </form>
        </div>
    </div>

    @include('scripts.modal_create')

    <script>
        const editButtons = document.querySelectorAll('.editBtn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const currencyId = this.getAttribute('data-id');
                const currencyName = this.getAttribute('data-name');

                const updateForm = document.getElementById('updateForm');
                updateForm.action = `/categories/${currencyId}`;
                document.getElementById('update_name').value = currencyName;

                document.getElementById('updatePopup').classList.remove('hidden');
                document.getElementById('updatePopup').classList.add('flex');
            });
        });

        document.getElementById('cancelUpdateBtn').addEventListener('click', function() {
            document.getElementById('updatePopup').classList.add('hidden');
            document.getElementById('updatePopup').classList.remove('flex');
        });
    </script>
@endsection
