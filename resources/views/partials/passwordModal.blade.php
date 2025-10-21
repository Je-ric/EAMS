<dialog id="passwordDialog" class="modal">
    <form id="attendanceForm" method="POST" action="" class="modal-box max-w-md p-6 rounded-xl shadow-lg bg-white">

        @csrf

        <!-- Header -->
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="bx bx-lock-alt text-blue-600 text-xl"></i> Confirm Attendance
        </h3>

        <!-- Employee Info -->
        <div class="flex flex-col items-center mb-4">
            <img id="empPicPreview" src="https://via.placeholder.com/100" alt="Employee"
                 class="w-24 h-24 rounded-full object-cover border border-gray-300 mb-2">
            <span id="empNameDisplay" class="font-semibold text-gray-700">Employee Name</span>
        </div>

        <!-- Hidden Email -->
        <input type="hidden" name="email" id="empEmailInput">

        <!-- Password Input -->
        <input type="password" name="password" placeholder="Enter your password" required
               class="w-full border border-gray-300 rounded-md px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

        <!-- Actions -->
        <div class="flex justify-end gap-2">
            <button type="button" class="border border-gray-300 rounded-md px-4 py-2 hover:bg-gray-100 transition" onclick="passwordDialog.close()">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700 transition flex items-center gap-2">
                <i class="bx bx-check"></i> Confirm
            </button>
        </div>
    </form>

</dialog>
