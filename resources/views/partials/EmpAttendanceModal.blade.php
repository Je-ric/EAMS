<dialog id="EmpAttendanceModal" class="modal">
    <div class="modal-box max-w-7xl p-6 rounded-xl shadow-lg bg-white">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                <i class="bx bx-calendar-check text-xl"></i> Attendance Summary
            </h2>
            <form method="dialog">
                <button class="text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full p-1 transition">✕</button>
            </form>
        </div>

        <!-- Employee Info -->
        <div class="mb-4 flex items-center gap-4">
            <img id="attendanceEmpPic" src="https://via.placeholder.com/100" 
                 class="w-16 h-16 rounded-full border border-gray-300 object-cover"/>
            <span id="attendanceEmpName" class="font-semibold text-gray-700 text-lg"></span>
        </div>

        <!-- Attendance Table / Content -->
        <div id="attendanceSummaryContent" class="space-y-2 text-gray-700">
            <p class="text-center text-gray-400">Loading attendance...</p>
        </div>

        <!-- Actions -->
        <div class="modal-action mt-4">
            <form method="dialog">
                <button class="w-full rounded-md border border-gray-300 px-4 py-2 hover:bg-gray-100 transition">Close</button>
            </form>
        </div>

    </div>
</dialog>
