<dialog id="EmpAttendanceModal" class="modal">
    <div class="modal-box max-w-7xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                <i class="fas fa-calendar-check"></i> Attendance Summary
            </h2>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-gray-200">✕</button>
            </form>
        </div>

        <div class="mb-4 flex items-center gap-4">
            <img id="attendanceEmpPic" src="https://via.placeholder.com/100" class="w-16 h-16 rounded-full border border-gray-300"/>
            <span id="attendanceEmpName" class="font-semibold text-gray-700 text-lg"></span>
        </div>

        <div id="attendanceSummaryContent" class="space-y-2 text-gray-700">
            <p class="text-center text-gray-400">Loading attendance...</p>
        </div>

        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-neutral">Close</button>
            </form>
        </div>
    </div>
</dialog>
