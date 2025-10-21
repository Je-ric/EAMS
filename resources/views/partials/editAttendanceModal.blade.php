<dialog id="editAttendanceModal" class="modal">
    <form method="dialog" id="editAttendanceForm" class="modal-box max-w-md p-6 rounded-xl shadow-lg bg-white">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-xl font-bold text-blue-700 flex items-center gap-2">
                <i class="bx bx-edit-alt text-lg"></i> Edit Attendance
            </h5>
            <button type="button" class="text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-full p-1 transition"
                onclick="editAttendanceModal.close()">
                ✕
            </button>
        </div>

        <!-- Hidden ID -->
        <input type="hidden" id="edit_attendance_id" name="attendance_id">

        <!-- Time In -->
        <div class="mb-3">
            <label for="edit_time_in" class="block text-gray-700 font-medium mb-1">Time In</label>
            <input type="text" id="edit_time_in" name="time_in"
                placeholder="e.g. 08:30 AM"
                pattern="^(0[1-9]|1[0-2]):[0-5][0-9]\s?(AM|PM|am|pm)$"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Time Out -->
        <div class="mb-3">
            <label for="edit_time_out" class="block text-gray-700 font-medium mb-1">Time Out</label>
            <input type="text" id="edit_time_out" name="time_out"
                placeholder="e.g. 05:15 PM"
                pattern="^(0[1-9]|1[0-2]):[0-5][0-9]\s?(AM|PM|am|pm)$"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>


        <!-- Actions -->
        <div class="flex justify-end gap-2 mt-4">
            <button type="submit"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md px-4 py-2 transition">
                <i class="bx bx-save"></i> Save Changes
            </button>
            <button type="button"
                class="flex items-center gap-2 border border-gray-300 rounded-md px-4 py-2 hover:bg-gray-100 transition"
                onclick="editAttendanceModal.close()">
                <i class="bx bx-x"></i> Cancel
            </button>
        </div>
    </form>
</dialog>

<script>
    const editAttendanceModal = document.getElementById('editAttendanceModal');
</script>
