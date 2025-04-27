// assets/js/upgrade-pos.js
// Smooth Interaction Upgrade

$(document).ready(function() {

    // Klik item pesanan untuk efek aktif
    $(document).on('click', '.pesanan-item', function() {
        $(".pesanan-item").removeClass('selected');
        $(this).addClass('selected');
    });

    // Tombol loading animation saat simpan
    $("#simpan-transaksi, #simpan-perubahan").on('click', function() {
        $(this).addClass('loading').attr('disabled', true);
        setTimeout(() => {
            $(this).removeClass('loading').attr('disabled', false);
        }, 1500);
    });

    // Hover efek glow untuk semua tombol
    $("button").hover(function() {
        $(this).css("box-shadow", "0px 4px 10px rgba(0,0,0,0.2)");
    }, function() {
        $(this).css("box-shadow", "none");
    });

});

$(document).ready(function() {
    // Smooth Scrolling
    $('html, body').on('click', 'a.nav-link', function(event){
        if (this.hash !== "") {
            event.preventDefault();
            const hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset()?.top || 0
            }, 600, function(){
                window.location.hash = hash;
            });
        }
    });

    // Loading produk pertama
    loadProduk();

    function loadProduk(kategori = '', search = '') {
        $.ajax({
            url: base_url + "kasir/load_produk",
            type: "GET",
            data: { kategori, search },
            success: function(res) {
                $("#produk-list").html(res);
            }
        });
    }

    $("#search").keyup(function() {
        const keyword = $(this).val();
        loadProduk('', keyword);
    });

    $("#kategori-tab button").click(function() {
        $("#kategori-tab button").removeClass('active');
        $(this).addClass('active');
        const kategori = $(this).data('kategori');
        loadProduk(kategori);
    });

});
