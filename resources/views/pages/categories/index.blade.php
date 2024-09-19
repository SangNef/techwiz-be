@extends('layout.main')
@section('content')
    <div class="flex justify-between my-6">
        <h1 class="font-bold text-2xl leading-7">Trips List</h1>
        <button id="addNewBtn"
            class="bg-pink-400 text-white px-4 py-1 rounded hover:bg-pink-500 duration-150 ease-in-out flex items-center gap-1">
            <i class="fa-solid fa-plus"></i> Add new
        </button>
    </div>

    <div>
        @php
            $stt = 1;
        @endphp

        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">No</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Name</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Created at</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $stt++ }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $category->name }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $category->created_at }}</td>
                        
                        <td class="py-2 px-4 text-sm text-gray-700 flex gap-1">
                            <button id="editBtn" class="bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600 transition duration-150 ease-in-out shadow-md hover:shadow-lg editBtn" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                Update
                            </button>
                            <form action="{{ route('category.destroy', $category->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                                    Delete
                                </button>
                            </form>
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
            <h2 class="text-xl font-bold mb-4">Add New Category</h2>
            <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
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
            <h2 class="text-xl font-bold mb-4">Update Category</h2>
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
                    <button type="submit" class="bg-pink-400 text-white px-4 py-2 rounded hover:bg-pink-500">Update</button>
                </div>
            </form>
        </div>
    </div>

    @include('scripts.modal_create')

    <script>
        const editButtons = document.querySelectorAll('.editBtn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-id');
                const categoryName = this.getAttribute('data-name');
                
                const updateForm = document.getElementById('updateForm');
                updateForm.action = `/categories/${categoryId}`;
                document.getElementById('update_name').value = categoryName;

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
