<dialog id="editAttendanceModal" class="modal">
    <form id="editAttendanceForm" class="modal-box max-w-md p-6 rounded-xl shadow-lg bg-white">
        @csrf

        <div class="flex justify-between items-center mb-4">
            <h5 class="text-xl font-bold text-blue-700 flex items-center gap-2">
                <i class="bx bx-calendar-edit"></i> Edit / Add Attendance
            </h5>
            <button type="button"
                    class="text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full p-1 transition"
                    onclick="document.getElementById('editAttendanceModal').close()">
                ✕
            </button>
        </div>

        <input type="hidden" id="edit_attendance_id" name="attendance_id">
        <input type="hidden" id="edit_attendance_emp_id" name="emp_id">
        <input type="hidden" id="edit_attendance_date" name="date">

        <div class="mb-3">
            <label for="edit_time_in" class="block text-gray-700 font-medium mb-1">Time In</label>
            <input type="time"
                    id="edit_time_in"
                    name="time_in"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="mb-3">
            <label for="edit_time_out" class="block text-gray-700 font-medium mb-1">Time Out</label>
            <input type="time"
                    id="edit_time_out"
                    name="time_out"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button"
                class="border border-gray-300 rounded-md px-4 py-2 hover:bg-gray-100 transition"
                onclick="document.getElementById('editAttendanceModal').close()">Cancel</button>

            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Save Changes
            </button>
        </div>
    </form>
</dialog>

<script>
    const editAttendanceModal = document.getElementById('editAttendanceModal');
</script>
