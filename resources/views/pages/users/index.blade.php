@extends('layout.main')
@section('content')
    <div class="flex justify-between my-6">
        <h1 class="font-bold text-2xl leading-7">Users List</h1>
    </div>

    <div>
        @php
            $stt = 1;
        @endphp

        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Stt</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Name</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Email</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Status</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">CreatedAt</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $stt++ }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $user->name }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $user->email }}</td>
                        @if ($user->deleted_at === null)
                            <td class="py-2 px-4 text-sm text-gray-700">Active</td>
                        @else
                            <td class="py-2 px-4 text-sm text-gray-700">Banned</td>
                        @endif
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">
                            <a href="{{ route('admin.user.update', $user->id) }}" class="text-blue-500 hover:text-blue-700">
                                {{ $user->deleted_at === null ? 'Ban' : 'Unban' }}
                            </a>
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
