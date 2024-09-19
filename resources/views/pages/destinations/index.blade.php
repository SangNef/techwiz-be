@extends('layout.main')
@section('content')
    <div class="flex justify-between my-6">
        <h1 class="font-bold text-2xl leading-7">Destinations List</h1>
        <button id="addNewBtn"
            class="bg-pink-400 text-white px-4 py-1 rounded hover:bg-pink-500 duration-150 ease-in-out flex items-center gap-1">
            <i class="fa-solid fa-plus"></i> Add new
        </button>
    </div>

    {{-- error any --}}

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li><span class="block sm:inline">{{ $error }}</span></li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        @php
            $stt = 1;
        @endphp

        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead class="border-b border-gray-200">
                <tr>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">No</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Name</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Image</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($destinations as $destination)
                    <tr class="border-b hover:bg-slate-200 duration-150">
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $stt++ }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $destination->name }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">
                            <img src="{{ asset('images/destinations/' . $destination->images->first()->image) }}"
                                alt="{{ $destination->name }}" class="h-16 object-cover rounded">
                        </td>
                        <td class="py-2 px-4 text-sm text-gray-700 h-full flex gap-2 items-center">
                            <button
                                class="bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600 transition duration-150 ease-in-out shadow-md hover:shadow-lg editBtn"
                                data-id="{{ $destination->id }}" data-name="{{ $destination->name }}"
                                data-description="{{ $destination->description }}"
                                data-image="{{ asset('images/destinations/' . $destination->images) }}">
                                Edit
                            </button>
                            <form action="{{ route('destination.destroy', $destination->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
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
        <div class="bg-white p-6 rounded-lg w-full max-w-md max-h-[600px] overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Add New Destination</h2>
            <form action="{{ route('destination.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" name="description" id="description"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700" for="file_input">Upload files</label>
                    <input id="file_input" type="file" name="images[]" accept="image/*" multiple class="hidden" />

                    <div id="imagePreviewGrid" class="grid grid-cols-4 gap-2 mt-1">
                        <label for="file_input"
                            class="border rounded aspect-square flex justify-center items-center text-3xl hover:text-4xl text-gray-500 duration-300 ease-in-out">
                            <i class="fa-solid fa-plus"></i>
                        </label>
                    </div>
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
        <div class="bg-white p-6 rounded-lg w-full max-w-md max-h-[600px] overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Update Destination</h2>
            <form id="updateForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="update_name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="update_name"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="update_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" name="description" id="update_description"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700" for="update_file_input">Upload files</label>
                    <input id="update_file_input" type="file" name="images[]" accept="image/*" multiple
                        class="hidden" />

                    <div id="updateImagePreviewGrid" class="grid grid-cols-4 gap-2 mt-1">
                        <label for="update_file_input"
                            class="border rounded aspect-square flex justify-center items-center text-3xl hover:text-4xl text-gray-500 duration-300 ease-in-out">
                            <i class="fa-solid fa-plus"></i>
                        </label>
                    </div>
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
        let selectedImages = [];

        document.getElementById('file_input').addEventListener('change', function(event) {
            const files = event.target.files;

            Array.from(files).forEach((file) => {
                if (!selectedImages.some(img => img.file === file)) {
                    selectedImages.push({
                        file,
                        url: URL.createObjectURL(file)
                    });
                }
            });

            updateImagePreviewGrid();
        });

        function updateImagePreviewGrid() {
            const imagePreviewGrid = document.getElementById('imagePreviewGrid');

            imagePreviewGrid.innerHTML = `
                <label for="file_input" class="border rounded aspect-square flex justify-center items-center text-3xl hover:text-4xl text-gray-500 duration-300 ease-in-out">
                    <i class="fa-solid fa-plus"></i>
                </label>
            `;

            selectedImages.forEach((imageData, index) => {
                const imgDiv = document.createElement('div');
                imgDiv.classList.add('relative', 'aspect-square', 'border', 'rounded', 'overflow-hidden');

                const img = document.createElement('img');
                img.src = imageData.url;
                img.classList.add('object-cover', 'w-full', 'h-full');

                const closeBtn = document.createElement('button');
                closeBtn.classList.add('absolute', 'top-2', 'right-2', 'bg-white', 'text-gray-800', 'rounded-full',
                    'w-6', 'h-6', 'flex', 'items-center', 'justify-center', 'hover:bg-gray-200');
                closeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                closeBtn.addEventListener('click', function() {
                    removeImage(index);
                });

                imgDiv.appendChild(img);
                imgDiv.appendChild(closeBtn);
                imagePreviewGrid.appendChild(imgDiv);
            });
        }

        function removeImage(index) {
            selectedImages.splice(index, 1);

            updateImagePreviewGrid();
        }
    </script>

    <script>
        // Lắng nghe sự kiện click vào các nút Edit
        document.querySelectorAll('.editBtn').forEach(button => {
            button.addEventListener('click', function() {
                const destinationId = this.getAttribute('data-id');
                const destinationName = this.getAttribute('data-name');
                const destinationDescription = this.getAttribute('data-description');

                // Cập nhật form với dữ liệu từ nút edit
                document.getElementById('update_name').value = destinationName;
                document.getElementById('update_description').value = destinationDescription;
                document.getElementById('updateForm').action = `/destinations/${destinationId}`;

                // Hiển thị popup update
                document.getElementById('updatePopup').classList.remove('hidden');
            });
        });

        // Đóng popup khi bấm Cancel trong form update
        document.getElementById('cancelUpdateBtn').addEventListener('click', function() {
            document.getElementById('updatePopup').classList.add('hidden');
        });
        
    </script>
@endsection
