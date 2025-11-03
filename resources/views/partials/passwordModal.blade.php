{{--
    Pag submit, proceed to AttendanceController@timeIn or AttendanceController@timeOut via AJAX (form.action is set dynamically).
    - Who uses what:
        The modal uses 'email' and 'password' to the AttendanceController.
--}}
<dialog id="passwordDialog" class="modal">

    {{--  --}}
    <form id="attendanceForm"
            method="POST"
            action=""
            class="modal-box max-w-md p-6 rounded-xl shadow-lg bg-white">
        @csrf

        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="bx bx-lock-alt text-blue-600 text-xl"></i> Confirm Attendance
        </h3>

        <div class="flex flex-col items-center mb-4">
            <img id="empPicPreview"
                src="https://via.placeholder.com/100"
                alt="Employee"
                class="w-24 h-24 rounded-full object-cover border border-gray-300 mb-2">
            <span id="empNameDisplay" class="font-semibold text-gray-700">
                Employee Name
            </span>
        </div>

        <input type="hidden" name="email" id="empEmailInput">

        <input type="password"
                name="password"
                id="attendancePassword"
                placeholder="Enter your password"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

        <div class="flex justify-end gap-2">

            <button type="button"
                    class="border border-gray-300 rounded-md px-4 py-2 hover:bg-gray-100 transition"
                    onclick="passwordDialog.close()">
                    Cancel
            </button>

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

            // collect data
            const form = $(this);
            const url = form.attr('action');
            const email = $('#empEmailInput').val();
            const passwordInput = form.find('input[name="password"]');

            // send AJAX request to AttendanceController
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    email: email,
                    password: passwordInput.val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    // pag success
                    if (res.success || res.message) {
                        alert(res.success || res.message || 'Attendance recorded successfully!');
                        $('#passwordDialog')[0].close();
                        passwordInput.val('');

                        // Find the employee row by email
                        const row = $('button[data-email="' + email + '"]').closest('tr');
                        let now = new Date(); // get current time
                        let formatted = now.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        });

                        let cell = row.find('td').eq(4); // select Attendance column
                        let html = cell.html().trim();

                        // Attendance cell update (mainly ui lang)
                        if (url.includes('time-in')) { // Only time in exists, leave time out blank
                            cell.html(`${formatted}`); // Disable Time In, enable Time Out
                            row.find('button:contains("Time In")').prop('disabled', true);
                            row.find('button:contains("Time Out")').prop('disabled', false);
                        } else { // Time out recorded
                            let timeIn = html.split('-')[0].trim();
                            if (!timeIn) timeIn = formatted; // fallback if cell was empty
                            cell.html(timeIn ? `${timeIn} - ${formatted}` : `${formatted}`);
                            // Disable both buttons after time out
                            row.find('button:contains("Time In")').prop('disabled', true);
                            row.find('button:contains("Time Out")').prop('disabled', true);
                        }
                    } else {
                        alert('Wrong Password!');
                        passwordInput.val('');
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.error || 'Failed to record attendance.');
                    passwordInput.val('');
                }
            });
        });
    });
</script>
