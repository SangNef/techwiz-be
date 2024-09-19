@extends('layout.main')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Statistics Cards -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Total Users</h2>
            <p class="text-3xl mt-2">150</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Total Orders</h2>
            <p class="text-3xl mt-2">320</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Revenue</h2>
            <p class="text-3xl mt-2">$10,500</p>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-xl font-bold">Recent Activities</h3>
        <table class="min-w-full bg-white mt-4">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Activity</th>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">User</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-4 py-2">New Order Created</td>
                    <td class="border px-4 py-2">2024-09-19</td>
                    <td class="border px-4 py-2">John Doe</td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
@endsection
