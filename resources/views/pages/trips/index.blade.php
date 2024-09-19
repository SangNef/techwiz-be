@extends('layout.main')
@section('content')
    <div class="flex justify-between my-6">
        <h1 class="font-bold text-2xl leading-7">Trips List</h1>
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
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Destination</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">User</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Package</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Start date</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">End date</th>
                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($trips as $trip)
                    <tr>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $stt++ }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $trip->name }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $trip->email }}</td>
                        @if ($trip->deleted_at === null)
                            <td class="py-2 px-4 text-sm"><span class="bg-green-200 text-green-600 px-1 rounded">Active</span></td>
                        @else
                            <td class="py-2 px-4 text-sm"><span class="bg-red-200 text-red-600 px-1 rounded">Banned</span></td>
                        @endif
                        <td class="py-2 px-4 text-sm text-gray-700">{{ $trip->created_at->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 text-sm text-gray-700">
                            <form action="{{ route('trip.update', $trip->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-red-200 text-red-600 px-1 rounded">
                                    {{ $trip->deleted_at === null ? 'Ban' : 'Unban' }}
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
@endsection
