<dialog id="passwordDialog" class="modal">
    <form id="attendanceForm" method="POST" action="" class="modal-box max-w-md p-6 rounded-xl shadow-lg bg-white">
        @csrf
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="bx bx-lock-alt text-blue-600 text-xl"></i> Confirm Attendance
        </h3>

        <div class="flex flex-col items-center mb-4">
            <img id="empPicPreview" src="https://via.placeholder.com/100" alt="Employee"
                class="w-24 h-24 rounded-full object-cover border border-gray-300 mb-2">
            <span id="empNameDisplay" class="font-semibold text-gray-700">Employee Name</span>
        </div>

        <input type="hidden" name="email" id="empEmailInput">

        <input type="password" name="password" id="attendancePassword" placeholder="Enter your password" required
            class="w-full border border-gray-300 rounded-md px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

        <div class="flex justify-end gap-2">
            <button type="button" class="border border-gray-300 rounded-md px-4 py-2 hover:bg-gray-100 transition"
                onclick="passwordDialog.close()">Cancel</button>
            <button type="submit" id="attendanceSubmit"
                class="bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700 transition flex items-center gap-2">
                <i class="bx bx-check"></i> Confirm
            </button>
        </div>
    </form>
</dialog>


<script>
    $(document).ready(function() {
        $('#attendanceForm').on('submit', function(e) {
            e.preventDefault();

            const form = $(this);
            const url = form.attr('action');
            const email = $('#empEmailInput').val();
            const password = form.find('input[name="password"]').val();

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    email: email,
                    password: password,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res.success || res.message) {
                        alert(res.success || res.message ||
                            'Attendance recorded successfully!');
                        $('#passwordDialog')[0].close();

                        // Find the employee row by email
                        const row = $('button[data-email="' + email + '"]').closest('tr');
                        let now = new Date();
                        let formatted = now.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        });

                        let cell = row.find('td').eq(4); // 5th col (Attendance)
                        let html = cell.html();

                        if (url.includes('time-in')) {
                            cell.html(
                                `<span class="text-green-700 font-semibold">${formatted}</span> - <span class="text-yellow-500 font-semibold">Active</span>`
                                );
                        } else {
                            let timeIn = html.split('-')[0].trim();
                            cell.html(
                                `${timeIn} - <span class="text-red-700 font-semibold">${formatted}</span>`
                                );
                        }
                    } else {
                        alert('Attendance recorded successfully!');
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.error || 'Failed to record attendance.');
                }
            });
        });
    });
</script>
