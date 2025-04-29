<!-- Include ini sekali di bawah halaman -->
<script>
$(document).ready(function() {
    $('#tabelPending').DataTable({
        pageLength: 10,
        lengthMenu: [
            [10, 30, 50, 100, -1],
            [10, 30, 50, 100, "Semua"]
        ],
        responsive: true,
        ordering: false,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"
            }
        }
    });
});
</script>