@extends('layout.main')
@section('content')
    <div class="flex justify-between my-6">
        <h1 class="font-bold text-2xl leading-7">Danh sách cấu hình</h1>
        <a href="{{ route('sample.create') }}"
            class="bg-pink-400 text-white px-4 py-1 rounded hover:bg-pink-500 duration-150 ease-in-out flex items-center gap-1 no-underline">
            <i class="fa-solid fa-plus"></i> Thêm mới
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
        <div class="mt-4 space-y-4">
            @forelse ($configs as $config)
                <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                    <h2 class="font-semibold text-lg mb-2">{{ $config->key }}</h2>

                    <form action="{{ route('config.update', $config->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="text-gray-700 mb-2">
                            @if (is_array($config->value))
                                @if ($config->key === 'home_section_1')
                                    <div class="mb-4">
                                        <label for="description">Mô tả:</label>
                                        <input type="text" name="description" id="description"
                                            class="block w-full mt-2 p-2 border rounded"
                                            value="{{ $config->value['description'] ?? '' }}">
                                    </div>
                                    @foreach ($config->value['banner'] as $index => $banner)
                                        <li class="flex items-center gap-4 mb-4">
                                            <img src="{{ $banner }}" alt="Banner {{ $index + 1 }}"
                                                class="w-32 h-32 object-cover">
                                            <div>
                                                <label for="banner_{{ $index }}">Upload ảnh mới:</label>
                                                <input type="file" name="banner[{{ $index }}]"
                                                    id="banner_{{ $index }}" class="block mt-2">
                                            </div>
                                            <div class="ml-4">
                                                <label for="title_{{ $index }}">Tiêu đề:</label>
                                                <input type="text" name="title[{{ $index }}]"
                                                    id="title_{{ $index }}"
                                                    value="{{ $config->value['title'][$index] }}"
                                                    class="block mt-2 p-2 border rounded">
                                            </div>
                                        </li>
                                    @endforeach
                                @elseif ($config->key === 'home_section_2')
                                    <!-- Phần mô tả -->
                                    <div class="mb-4">
                                        <label for="description">Mô tả:</label>
                                        <input name="description" id="description"
                                            class="block w-full mt-2 p-2 border rounded"
                                            value="{{ $config['description'] }}"></input>
                                    </div>
                                    <!-- Tiêu đề -->
                                    <div class="space-y-2">
                                        <label for="description">Tiêu đề :</label>
                                        @foreach ($config->value['title'] as $index => $title)
                                            <div class="flex items-center gap-4">
                                                <input type="text" name="title[{{ $index }}]"
                                                    id="title_{{ $index }}" value="{{ $title }}"
                                                    class="block w-full p-2 border rounded">
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif ($config->key === 'home_section_3')
                                    <div class="mb-4">
                                        <label for="description">Mô tả:</label>
                                        <input name="description" id="description"
                                            class="block w-full mt-2 p-2 border rounded"
                                            value="{{ $config['description'] }}">
                                    </div>

                                    @foreach ($config->value['banner'] as $index => $banner)
                                        <li class="flex items-center gap-4 mb-4">
                                            <img src="{{ $banner }}" alt="Banner {{ $index + 1 }}"
                                                class="w-32 h-32 object-cover">
                                            <div>
                                                <label for="banner_{{ $index }}">Upload ảnh mới:</label>
                                                <input type="file" name="banner[{{ $index }}]"
                                                    id="banner_{{ $index }}" class="block mt-2">
                                            </div>
                                        </li>
                                    @endforeach

                                    <!-- Tiêu đề -->
                                    <div class="mb-4">
                                        <label for="title">Tiêu đề:</label>
                                        <input type="text" name="title" id="title"
                                            value="{{ $config->value['title'] }}" class="block w-full p-2 border rounded">
                                    </div>
                                @elseif ($config->key === 'home_section_4')
                                    <!-- Phần sửa tiêu đề -->
                                    <div class="mb-4">
                                        <label for="title">Tiêu đề:</label>
                                        <input type="text" name="title" id="title"
                                            value="{{ $config['value']['title'] }}"
                                            class="block w-full p-2 border rounded">
                                    </div>

                                    <!-- Phần sửa nội dung -->
                                    <div class="mb-4">
                                        <label for="content">Nội dung:</label>
                                        @foreach ($config['value']['content'] as $index => $content)
                                            <input type="text" name="content[]" value="{{ $content }}"
                                                class="block w-full p-2 border rounded mt-2">
                                        @endforeach
                                    </div>

                                    <!-- Phần sửa mô tả -->
                                    <div class="mb-4">
                                        <label for="description">Mô tả:</label>
                                        @foreach ($config['value']['description'] as $index => $description)
                                            <input type="text" name="description[]" value="{{ $description }}"
                                                class="block w-full p-2 border rounded mt-2">
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <input type="text" name="value"
                                    class="form-control w-full mt-2 p-2 border border-gray-300 rounded"
                                    value="{{ $config->value }}">
                            @endif
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Cập nhật</button>
                    </form>
                </div>
            @empty
                <p class="text-gray-700">Không có dữ liệu.</p>
            @endforelse
        </div>
    </div>
@endsection
