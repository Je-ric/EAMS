<!-- Attendance Summary Modal -->
<dialog id="attendanceSummaryModal" class="modal">
    <div class="modal-box max-w-5xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-blue-600"></i>
                Attendance Summary
            </h2>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-gray-200">✕</button>
            </form>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row justify-between gap-3 mb-5">
            <div class="flex flex-wrap items-center gap-3">
                <label class="text-gray-700 font-medium">From:</label>
                <input type="date" id="fromDate" class="input input-bordered input-sm w-40" />
                <label class="text-gray-700 font-medium">To:</label>
                <input type="date" id="toDate" class="input input-bordered input-sm w-40" />
                <button class="btn btn-sm btn-primary gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>

            <div class="flex items-center">
                <label class="input input-bordered input-sm flex items-center gap-2 w-full md:w-64">
                    <i class="fas fa-search text-gray-400"></i>
                    <input type="text" id="employeeSearch" class="grow" placeholder="Search by employee/date..." />
                </label>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="table table-zebra w-full text-center">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-500 text-white">
                    <tr>
                        <th>#</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-blue-50">
                        <td>1</td>
                        <td><img src="https://via.placeholder.com/40" class="mx-auto rounded-full border" /></td>
                        <td class="font-semibold text-gray-800">John Doe</td>
                        <td>2025-10-20</td>
                        <td class="text-green-600 font-medium">8:30 AM</td>
                        <td class="text-red-600 font-medium">5:00 PM</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-gray-400 py-3">No more records</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-5">
            <button class="btn btn-outline btn-sm">Previous</button>
            <span class="text-gray-700 font-medium">Page 1 of 1</span>
            <button class="btn btn-outline btn-sm">Next</button>
        </div>

        <!-- Footer (optional close) -->
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-neutral">Close</button>
            </form>
        </div>
    </div>

    <!-- Modal backdrop -->
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
