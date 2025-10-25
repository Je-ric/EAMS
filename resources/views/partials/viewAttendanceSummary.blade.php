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

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-4">

            <div class="flex flex-wrap items-end gap-3">
                <div class="flex flex-col">
                    <label for="summaryFrom" class="text-gray-700 font-medium">From:</label>
                    <input type="date" id="summaryFrom"
                        class="border rounded-md px-3 py-1 focus:ring-2 focus:ring-blue-500 w-44">
                </div>
                <div class="flex flex-col">
                    <label for="summaryTo" class="text-gray-700 font-medium">To:</label>
                    <input type="date" id="summaryTo"
                        class="border rounded-md px-3 py-1 focus:ring-2 focus:ring-blue-500 w-44">
                </div>
                <button id="filterSummaryBtn"
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-md px-4 py-2 flex items-center gap-2 transition mt-5 md:mt-0">
                    <i class='bx bx-search'></i> Filter
                </button>
            </div>

            <div id="exportButtons" class="hidden flex gap-2 items-end">
                <button id="exportSummaryCsv"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-md px-4 py-2 flex items-center gap-2 transition">
                    <i class='bx bx-file'></i> Export CSV
                </button>
                <button id="exportSummaryPdf"
                    class="bg-red-600 hover:bg-red-700 text-white rounded-md px-4 py-2 flex items-center gap-2 transition">
                    <i class='bx bxs-file-pdf'></i> Export PDF
                </button>
            </div>

        </div>


        <div id="summaryResults" class="overflow-x-auto border rounded-lg" style="max-height:400px; overflow:auto;">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Employee</th>
                        <th class="px-4 py-2 border">Position</th>
                        <th class="px-4 py-2 border text-center">Date</th>
                        <th class="px-4 py-2 border text-center">Time In</th>
                        <th class="px-4 py-2 border text-center">Time Out</th>
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
    // convert to 12-hour time
    function formatTo12Hour(timeString) {
        if (!timeString) return '-';
        const date = new Date(`1970-01-01T${timeString}`);
        if (isNaN(date)) return timeString;
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
                    $('#exportButtons').addClass('hidden');
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

                // Show export buttons with current range
                $('#exportButtons').removeClass('hidden').data({ from, to });
            },
            error: function () {
                alert('Failed to load summary.');
            }
        });
    });

    // CSV
    $('#exportSummaryCsv').on('click', function () {
        const { from, to } = $('#exportButtons').data();
        window.location.href = `/export-summary/csv?from=${from}&to=${to}`;
    });

    // PDF
    $('#exportSummaryPdf').on('click', function () {
        const { from, to } = $('#exportButtons').data();
        window.location.href = `/export-summary/pdf?from=${from}&to=${to}`;
    });
});
</script>
