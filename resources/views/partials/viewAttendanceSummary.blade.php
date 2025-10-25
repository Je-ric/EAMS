<dialog id="attendanceSummaryModal" class="modal">
    <div class="modal-box max-w-6xl p-6 bg-white rounded-xl shadow-lg">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                <i class='bx bx-calendar-check'></i> Attendance Summary
            </h3>
            <form method="dialog">
                <button class="text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full p-1 transition">✕</button>
            </form>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="flex items-center gap-2">
                <label class="text-gray-700 font-medium">From:</label>
                <input type="date" id="summaryFrom" class="border rounded-md px-3 py-1 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-gray-700 font-medium">To:</label>
                <input type="date" id="summaryTo" class="border rounded-md px-3 py-1 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <button id="filterSummaryBtn"
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-md px-4 py-2 flex items-center gap-2 transition">
                    <i class='bx bx-search'></i> Filter
                </button>

                <button id="exportSummaryBtn"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-md px-4 py-2 flex items-center gap-2 transition hidden">
                    <i class='bx bx-download'></i> Export
                </button>
            </div>
        </div>

        <!-- Table -->
        <div id="summaryResults" class="overflow-x-auto border rounded-lg" style="max-height:400px; overflow:auto;">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Employee</th>
                        <th class="px-4 py-2 border">Position</th>
                        <th class="px-4 py-2 border">Date</th>
                        <th class="px-4 py-2 border">Time In</th>
                        <th class="px-4 py-2 border">Time Out</th>
                    </tr>
                </thead>
                <tbody id="summaryTableBody">
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">
                            Select a date range to view summary
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</dialog>

<script>
$(document).ready(function () {
    // Helper: convert time to 12-hour format
    function formatTo12Hour(timeString) {
        if (!timeString) return '-';
        const date = new Date(`1970-01-01T${timeString}`);
        if (isNaN(date)) return timeString; // fallback
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    $('#filterSummaryBtn').on('click', function (e) {
        e.preventDefault();

        const from = $('#summaryFrom').val();
        const to = $('#summaryTo').val();

        if (!from || !to) {
            alert('Please select both From and To dates.');
            return;
        }

        $.ajax({
            url: "{{ route('attendance.summary') }}",
            method: 'GET',
            data: { from, to },
            success: function (res) {
                const tbody = $('#summaryTableBody');
                tbody.empty();

                if (res.length === 0) {
                    tbody.append('<tr><td colspan="6" class="text-center py-4 text-gray-500">No records found.</td></tr>');
                    $('#exportSummaryBtn').addClass('hidden');
                    return;
                }

                res.forEach((row, i) => {
                    const timeIn = formatTo12Hour(row.time_in);
                    const timeOut = formatTo12Hour(row.time_out);

                    tbody.append(`
                        <tr>
                            <td class="border px-4 py-2">${i + 1}</td>
                            <td class="border px-4 py-2">${row.employee}</td>
                            <td class="border px-4 py-2">${row.position}</td>
                            <td class="border px-4 py-2 text-center">${row.date}</td>
                            <td class="border px-4 py-2 text-center">${timeIn}</td>
                            <td class="border px-4 py-2 text-center">${timeOut}</td>
                        </tr>
                    `);
                });

                // Show export button
                $('#exportSummaryBtn').removeClass('hidden').data({ from, to });
            },
            error: function (xhr) {
                alert('Failed to load summary.');
            }
        });
    });

    // Export Button
    $('#exportSummaryBtn').on('click', function () {
        const { from, to } = $(this).data();
        window.location.href = `/attendance/export?from=${from}&to=${to}`;
    });
});
</script>
