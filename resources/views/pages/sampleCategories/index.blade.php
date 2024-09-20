@extends('layout.main')
@section('content')
    <div class="flex justify-between my-6">
        <h1 class="font-bold text-2xl leading-7">Trips List</h1>
        <a href="{{ route('sample.create') }}"
            class="bg-pink-400 text-white px-4 py-1 rounded hover:bg-pink-500 duration-150 ease-in-out flex items-center gap-1 no-underline">
            <i class="fa-solid fa-plus"></i> Add new
        </a>
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
                    <th class="p-2 border-b text-start">Categories</th>
                    <th class="p-2 border-b text-start">Days</th>
                    <th class="p-2 border-b text-start">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sampleCategories as $category)
                    <tr
                        class="hover:bg-gray-100 even:bg-gray-200 border-b duration-150 text-sm leading-5 font-normal text-gray-500">
                        <td class="p-2">{{ $stt++ }}</td>
                        <td class="p-2">{{ $category->sample->name }}</td>
                        <td class="p-2">
                            @foreach ($category->sample->categories as $category)
                                <span class="bg-gray-300 text-gray-700 px-2 py-1 rounded-md mr-2">{{ $category->name }}</span>
                            @endforeach
                        </td>
                        <td class="p-2">{{ $category->schedules->max('day') }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700 flex gap-1">
                                <button id="editBtn"
                                    class="bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600 transition duration-150 ease-in-out shadow-md hover:shadow-lg editBtn"
                                    data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                    Update
                                </button>
                                {{-- <form action="{{ route('category.destroy', $category->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 transition duration-150 ease-in-out shadow-md hover:shadow-lg">
                                        Delete
                                    </button>
                                </form> --}}
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
@endsection
