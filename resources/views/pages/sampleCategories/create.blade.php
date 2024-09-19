@extends('layout.main')
@section('content')
    <div class="flex justify-between my-6">
        <h1 class="font-bold text-2xl leading-7">Create Sample Schedule</h1>
    </div>
    <div class="w-full bg-white rounded-lg shadow p-6 relative my-6">
        <form action="{{ route('sample.store') }}" method="POST">
            @csrf
            <!-- Sample Name -->
            <div class="mb-4">
                <label for="name" class="block text-md font-medium text-gray-700">Sample Name</label>
                <input type="text" name="name" id="name" placeholder="Sample Name"
                    class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Categories and Schedules -->
            <div id="categories-wrapper">
                <div class="mb-4 category-wrapper">
                    <label for="category_name" class="block text-md font-medium text-gray-700">Category Name</label>
                    <input type="text" name="category_name[]" placeholder="Category Name"
                        class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    
                    <label for="category_budget" class="block text-md font-medium text-gray-700 mt-4">Budget</label>
                    <input type="number" name="category_budget[]" step="0.001" placeholder="Budget"
                        class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">

                    <!-- Schedules with border-l -->
                    <div class="schedules-wrapper mt-4 border-l-4 border-gray-300 pl-4">
                        <div class="mb-4">
                            <label for="schedule_title" class="block text-md font-medium text-gray-700">Schedule Title</label>
                            <input type="text" name="schedule_title[][]" placeholder="Schedule Title"
                                class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="schedule_day" class="block text-md font-medium text-gray-700">Day</label>
                            <input type="number" name="schedule_day[][]" min="1" placeholder="Day"
                                class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="schedule_time" class="block text-md font-medium text-gray-700">Time</label>
                            <input type="time" name="schedule_time[][]"
                                class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <!-- Button to Add More Schedules under this Category -->
                    <div class="mb-4">
                        <button type="button" class="bg-gray-500 text-white px-4 py-1 rounded hover:bg-gray-600 duration-150 ease-in-out add-schedule" data-category-index="0">Add Schedule</button>
                    </div>
                </div>
            </div>

            <!-- Button to Add More Categories -->
            <div class="mb-4">
                <button type="button" id="add-category" class="bg-gray-500 text-white px-4 py-1 rounded hover:bg-gray-600 duration-150 ease-in-out">Add Category</button>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-pink-400 text-white px-4 py-1 rounded hover:bg-pink-500 duration-150 ease-in-out flex items-center gap-1 no-underline">
                    Save
                </button>
            </div>
        </form>
    </div>

    <script>
        let categoryIndex = 0;

        document.getElementById('add-category').addEventListener('click', function () {
            const categoriesWrapper = document.getElementById('categories-wrapper');
            categoryIndex++;
            const categoryHtml = `
                <div class="mb-4 category-wrapper">
                    <label for="category_name" class="block text-md font-medium text-gray-700">Category Name</label>
                    <input type="text" name="category_name[]" placeholder="Category Name"
                        class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">

                    <label for="category_budget" class="block text-md font-medium text-gray-700 mt-4">Budget</label>
                    <input type="number" name="category_budget[]" step="0.001" placeholder="Budget"
                        class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">

                    <!-- Schedules with border-l -->
                    <div class="schedules-wrapper mt-4 border-l-4 border-gray-300 pl-4">
                        <div class="mb-4">
                            <label for="schedule_title" class="block text-md font-medium text-gray-700">Schedule Title</label>
                            <input type="text" name="schedule_title[][]" placeholder="Schedule Title"
                                class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="schedule_day" class="block text-md font-medium text-gray-700">Day</label>
                            <input type="number" name="schedule_day[][]" min="1" placeholder="Day"
                                class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="schedule_time" class="block text-md font-medium text-gray-700">Time</label>
                            <input type="time" name="schedule_time[][]"
                                class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="button" class="bg-gray-500 text-white px-4 py-1 rounded hover:bg-gray-600 duration-150 ease-in-out add-schedule" data-category-index="${categoryIndex}">Add Schedule</button>
                    </div>
                </div>`;
            categoriesWrapper.insertAdjacentHTML('beforeend', categoryHtml);
        });

        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('add-schedule')) {
                const categoryIndex = e.target.getAttribute('data-category-index');
                const categoryWrapper = document.querySelector(`.category-wrapper:nth-of-type(${parseInt(categoryIndex) + 1}) .schedules-wrapper`);
                const scheduleHtml = `
                    <div class="mb-4">
                        <label for="schedule_title" class="block text-md font-medium text-gray-700">Schedule Title</label>
                        <input type="text" name="schedule_title[${categoryIndex}][]" placeholder="Schedule Title"
                            class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="schedule_day" class="block text-md font-medium text-gray-700">Day</label>
                        <input type="number" name="schedule_day[${categoryIndex}][]" min="1" placeholder="Day"
                            class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="schedule_time" class="block text-md font-medium text-gray-700">Time</label>
                        <input type="time" name="schedule_time[${categoryIndex}][]"
                            class="mt-1 focus:ring-pink-500 focus:border-pink-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>`;
                categoryWrapper.insertAdjacentHTML('beforeend', scheduleHtml);
            }
        });
    </script>
@endsection
