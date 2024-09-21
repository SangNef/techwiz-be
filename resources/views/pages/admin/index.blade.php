@extends('layout.main')
@section('content')
    <div class="flex justify-between my-6">
        <h1 class="font-bold text-2xl leading-7">Admin List</h1>
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
            $stt = $admins->firstItem();
        @endphp
        <form method="GET" class="flex space-x-4 justify-between">
            <select name="page_size" class="border-gray-300 rounded-lg">
                <option value="10" {{ request('page_size') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('page_size') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('page_size') == 50 ? 'selected' : '' }}>50</option>
            </select>
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email"
                    class="border-gray-300 rounded-lg px-4 py-2">
                <select name="status" class="border-gray-300 rounded-lg">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                </select>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Filter</button>
            </div>
        </form>

        <table class="w-full border-gray-300 mt-4">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr class="">
                    <th class="p-2 border-b text-start">No</th>
                    <th class="p-2 border-b text-start">Name</th>
                    <th class="p-2 border-b text-start">Email</th>
                    <th class="p-2 border-b text-start">Status</th>
                    <th class="p-2 border-b text-start">CreatedAt</th>
                    <th class="p-2 border-b text-start">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($admins as $admin)
                    <tr class="hover:bg-gray-100 even:bg-gray-200 duration-150 text-sm leading-5 font-normal text-gray-500">
                        <td class="p-2 border-b">{{ $stt++ }}</td>
                        <td class="p-2 border-b">{{ $admin->name }}</td>
                        <td class="p-2 border-b">{{ $admin->email }}</td>
                        @if ($admin->deleted_at === null)
                            <td class="p-2 border-b text-sm"><span
                                    class="bg-green-200 text-green-600 px-1 rounded">Active</span></td>
                        @else
                            <td class="p-2 border-b text-sm"><span
                                    class="bg-red-200 text-red-600 px-1 rounded">Banned</span></td>
                        @endif
                        <td class="p-2 border-b">{{ $admin->created_at->format('d/m/Y') }}</td>
                        <td class="p-2 border-b">
                            <form action="{{ route('admin.update', $admin->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded btn-lock">
                                    {{ $admin->deleted_at === null ? 'Ban' : 'Unban' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-2 border-b text-center">No Data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="my-4">
            {{ $admins->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
