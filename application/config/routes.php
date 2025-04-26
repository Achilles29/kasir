<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['gudang'] = 'gudang';
$route['gudang/index_v2'] = 'Gudang/index_v2';
$route['inventory'] = 'inventory';
$route['perbelanjaan'] = 'perbelanjaan';
$route['belanja'] = 'belanja';


$route['dbpurchase'] = 'DbPurchase';
$route['dbpurchase/index'] = 'DbPurchase/index';
$route['dbpurchase/add'] = 'DbPurchase/add';
$route['dbpurchase/search_barang'] = 'DbPurchase/search_barang';
$route['dbpurchase/edit/(:num)'] = 'DbPurchase/edit/$1';
$route['dbpurchase/update'] = 'DbPurchase/update';
$route['dbpurchase/delete/(:num)'] = 'DbPurchase/delete/$1';

$route['purchase/filter_range'] = 'purchase/filter_range';
$route['purchase_bar'] = 'Purchase_bar/index';
$route['purchase_bar/add'] = 'Purchase_bar/add';
$route['purchase_bar/delete/(:num)'] = 'Purchase_bar/delete/$1';

$route['purchase_kitchen'] = 'Purchase_kitchen/index';
$route['purchase_kitchen/add'] = 'Purchase_kitchen/add';
$route['purchase_kitchen/delete/(:num)'] = 'Purchase_kitchen/delete/$1';

$route['purchase_pending'] = 'Purchase_pending/index';
$route['purchase_pending/add'] = 'Purchase_pending/add';

$route['persediaanawal'] = 'PersediaanAwal';
$route['persediaanawal/add'] = 'PersediaanAwal/add';
$route['persediaanawal/edit'] = 'persediaanawal/edit';

$route['gudangawal'] = 'GudangAwal/index';
$route['gudangawal/index'] = 'GudangAwal/index';

$route['stok_opname'] = 'StokOpname/index';
$route['stok_opname/index'] = 'StokOpname/index';


$route['gudangawal/add'] = 'GudangAwal/add';
$route['gudangawal/search'] = 'GudangAwal/search';
$route['gudangawal/edit/(:num)'] = 'GudangAwal/edit/$1';
$route['gudangawal/delete/(:num)'] = 'GudangAwal/delete/$1';
$route['gudangawal/search'] = 'GudangAwal/search';
$route['gudangawal/update'] = 'GudangAwal/update';

$route['storerequest'] = 'StoreRequest/index';
$route['storerequest/index'] = 'StoreRequest/index';
$route['storerequest/edit/(:num)'] = 'StoreRequest/edit/$1';
$route['storerequest/delete/(:num)'] = 'StoreRequest/delete/$1';
$route['storerequest/laporan'] = 'StoreRequest/laporan';

$route['storerequestbar'] = 'StoreRequestBar/index';
$route['storerequestbar/index'] = 'StoreRequestBar/index';
$route['storerequestbar/edit/(:num)'] = 'StoreRequestBar/edit/$1';
$route['storerequestbar/delete/(:num)'] = 'StoreRequestBar/delete/$1';
$route['storerequestbar/add'] = 'StoreRequestBar/add';
$route['storerequestbar/reject/(:num)'] = 'StoreRequestBar/reject/$1';
$route['storerequestbar/verify/(:num)'] = 'StoreRequestBar/verify/$1';
$route['storerequestbar/update'] = 'StoreRequestBar/update';

$route['storerequestkitchen'] = 'StoreRequestKitchen/index';
$route['storerequestkitchen/index'] = 'StoreRequestKitchen/index';
$route['storerequestkitchen/edit/(:num)'] = 'StoreRequestKitchen/edit/$1';
$route['storerequestkitchen/delete/(:num)'] = 'StoreRequestKitchen/delete/$1';
$route['storerequestkitchen/add'] = 'StoreRequestKitchen/add';
$route['storerequestkitchen/reject/(:num)'] = 'StoreRequestKitchen/reject/$1';
$route['storerequestkitchen/verify/(:num)'] = 'StoreRequestKitchen/verify/$1';
$route['storerequestkitchen/update'] = 'StoreRequestKitchen/update';

$route['stokterbuang'] = 'StokTerbuang/index';
$route['stokterbuang/index'] = 'StokTerbuang/index';
$route['stokterbuang/add'] = 'StokTerbuang/add';
$route['stokterbuang/edit'] = 'StokTerbuang/edit';
$route['stokterbuang/update'] = 'StokTerbuang/update';
$route['stokterbuang/delete'] = 'StokTerbuang/delete';

$route['stokpenyesuaian'] = 'StokPenyesuaian/index';
$route['stokpenyesuaian/index'] = 'StokPenyesuaian/index';
$route['stokpenyesuaian/add'] = 'StokPenyesuaian/add';
$route['stokpenyesuaian/edit'] = 'StokPenyesuaian/edit';
$route['stokpenyesuaian/update'] = 'StokPenyesuaian/update';
$route['stokpenyesuaian/delete'] = 'StokPenyesuaian/delete';

$route['kas'] = 'kas/index';
$route['kas/index'] = 'kas/index';
$route['kas/add'] = 'kas/add';
$route['kas/edit'] = 'kas/edit';
$route['kas/update'] = 'kas/update';
$route['kas/delete'] = 'kas/delete';

$route['mutasi_kas'] = 'MutasiKas/index';
$route['mutasi_kas/index'] = 'MutasiKas/index';
$route['mutasi_kas/add_mutasi_kas'] = 'MutasiKas/add_mutasi_kas';
$route['mutasi_kas/update_mutasi_kas'] = 'MutasiKas/update_mutasi_kas';
$route['mutasi_kas/update'] = 'MutasiKas/update';
$route['mutasi_kas/delete_mutasi_kas/(:num)'] = 'MutasiKas/delete_mutasi_kas/$1';


$route['penjualan_kasir'] = 'Penjualan_kasir/index';
$route['penjualan_kasir/index'] = 'Penjualan_kasir/index';

$route['saldo_kas'] = 'SaldoKas/index';
$route['saldo_kas/index'] = 'SaldoKas/index';

$route['rekap_rekening'] = 'RekapRekening/index';
$route['rekap_rekening/index'] = 'RekapRekening/index';
$route['rekap_rekening/generate_rekap'] = 'RekapRekening/generate_rekap';
$route['rekap_rekening/generate_rekap_bulan_sebelumnya'] = 'RekapRekening/generate_rekap_bulan_sebelumnya';
$route['rekap_rekening/generate_saldo_awal'] = 'RekapRekening/generate_saldo_awal';

$route['laporan_keuangan'] = 'LaporanKeuangan/index';
$route['laporan_keuangan/index'] = 'LaporanKeuangan/index';
$route['laporan_brankas'] = 'Laporan_Brankas/index';
$route['laporan_brankas/index'] = 'Laporan_Brankas/index';

$route['mutasi_rekening'] = 'Mutasi_Rekening/index';
$route['mutasi_rekening/index'] = 'Mutasi_Rekening/index';
$route['mutasi_rekening/add'] = 'Mutasi_Rekening/add';
$route['mutasi_rekening/edit/(:num)'] = 'Mutasi_Rekening/edit/$1';
$route['mutasi_rekening/delete/(:num)'] = 'Mutasi_Rekening/delete/$1';

$route['cost_production'] = 'Cost_Production/index';
$route['cost_production/index'] = 'Cost_Production/index';
$route['cost_produksi'] = 'CostProduksi/index';
$route['cost_produksi/index'] = 'CostProduksi/index';

$route['penjualan_produk/index'] = 'Penjualan_produk/index';
$route['penjualan_produk'] = 'Penjualan_produk/index';

$route['dailyinventory'] = 'DailyInventory/index';
$route['dailyinventory/sync_storeroom'] = 'DailyInventory/sync_storeroom';

$route['purchase_umum'] = 'Umum/purchase_umum';  // Make sure the URL corresponds to the function in the controller
$route['penjualan_umum'] = 'Penjualan_kasir/penjualan_umum';  // Make sure the URL corresponds to the function in the controller
$route['penjualan_produk_umum'] = 'Penjualan_produk/penjualan_produk_umum';
$route['sr_umum'] = 'StoreRequest/sr_umum';
$route['refund_umum'] = 'Refund/refund_umum';
$route['cost_umum'] = 'Cost_Production/cost_umum';
$route['gudang_umum'] = 'gudang/gudang_umum';

$route['kasir/cetak_struk/(:num)/(:any)'] = 'kasir/cetak_struk/$1/$2';
$route['api/get_extra'] = 'extra/getExtra';
$route['api/divisi'] = 'divisi/index';

$route['kasir/detail_refund_kode/(:any)'] = 'kasir/detail_refund_kode/$1';


$route['member'] = 'member';
$route['data_awal'] = 'data_awal';

$route['halaman'] = 'halaman';
$route['default_controller'] = 'kasir';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;