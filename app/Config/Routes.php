<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'C_pengelolaan_ksu::index');

$routes->get('/', 'C_katalog');
$routes->get('/weather', 'Weather');

//$routes->get('/ssh', 'C_MysqlSsh');


//$routes->get('/', 'C_katalog::index');
$routes->get('/supco', 'C_detail_ksu_lain::supco');
$routes->get('/mitra', 'C_detail_ksu_lain::mitra');

// Change Password
$routes->post('/change', "C_login::ganti_password");

# Kebun
$routes->get('/kebun', 'C_master_kebun::index');
$routes->post('/kebun/tambah', 'C_master_kebun::responsTambah');
$routes->post('/kebun/edit', 'C_master_kebun::responsEdit');
$routes->post('/kebun/hapus', 'C_master_kebun::responsHapus');
$routes->post('/kebun/aktif', 'C_master_kebun::responsaktif');

# Komoditas
$routes->get('/komoditas', 'C_master_komoditas::index');
$routes->post('/komoditas/tambah', 'C_master_komoditas::responsTambah');
$routes->post('/komoditas/edit', 'C_master_komoditas::responsEdit');
$routes->post('/komoditas/hapus', 'C_master_komoditas::responsHapus');
$routes->post('/komoditas/aktif', 'C_master_komoditas::responsaktif');

# Sub Unit
$routes->get('/sub_unit', 'C_master_sub_unit::index');
$routes->post('/sub_unit/tambah', 'C_master_sub_unit::responsTambah');
$routes->post('/sub_unit/edit', 'C_master_sub_unit::responsEdit');
$routes->post('/sub_unit/hapus', 'C_master_sub_unit::responsHapus');

# Afdeling
$routes->get('/afdeling', 'C_master_afdeling::index');
$routes->post('/afdeling/tambah', 'C_master_afdeling::responsTambah');
$routes->post('/afdeling/edit', 'C_master_afdeling::responsEdit');
$routes->post('/afdeling/hapus', 'C_master_afdeling::responsHapus');

# Mitra
$routes->get('/mitra', 'C_master_mitra::index');
$routes->post('/mitra/tambah', 'C_master_mitra::responsTambah');
$routes->post('/mitra/edit', 'C_master_mitra::responsEdit');
$routes->post('/mitra/hapus', 'C_master_mitra::responsHapus');
$routes->post('/mitra/aktif', 'C_master_mitra::responsaktif');

# Role
$routes->get('/role', 'C_master_role::index');
$routes->post('/role/tambah', 'C_master_role::responsTambah');
$routes->post('/role/edit', 'C_master_role::responsEdit');
$routes->post('/role/hapus', 'C_master_role::responsHapus');
$routes->post('/role/aktif', 'C_master_role::responsaktif');

# User
$routes->get('/user', 'C_master_user::index');
$routes->post('/user/tambah', 'C_master_user::responsTambah');
$routes->post('/user/edit', 'C_master_user::responsEdit');
$routes->post('/user/hapus', 'C_master_user::responsHapus');
$routes->post('/user/aktif', 'C_master_user::responsaktif');

# Aset
$routes->get('/aset', 'C_mastes_katalog::index');
$routes->post('/aset/tambah', 'C_master_katalog::responsTambah');
$routes->post('/aset/edit', 'C_master_katalog::responsEdit');

$routes->get('whatsapp/kirim', 'CronjobController::kirimPesan');


#pengelolaan paket kerjasama
$routes->post('/C_pengelolaan_ksu_lain/hapusOpset/(:num)', 'C_pengelolaan_ksu_lain::hapusOpset/$1');

$routes->get('api/kerjasama', 'C_report_kerjasama::index'); // Rute untuk API total kerjasama per regional
$routes->get('api/total_kerjasama', 'C_report_kerjasama::total_kerjasama'); // Rute untuk API total kerjasama semua regional

// Api untuk looker
// RKAP
$routes->get('api/total_katalog', 'C_dashboard_looker::katalog');
$routes->get('api/perhitungan_nominal', 'C_dashboard_looker::perhitungan_nominal');
$routes->get('api/potensi_sharing', 'C_dashboard_looker::potensi_sharing');
$routes->get('api/rkap', 'C_dashboard_looker::rkap');
$routes->get('api/paket_kerjasama', 'C_dashboard_looker::paket_kerjasama');
$routes->get('api/jenis_kerjasama', 'C_dashboard_looker::jenis_kerjasama');
$routes->get('api/kerjasama_regional', 'C_dashboard_looker::kerjasama_regional');
$routes->get('api/total_kerjasama_regional', 'C_dashboard_looker::total_kerjasama_regional');
$routes->get('api/hasil_luasan', 'C_dashboard_looker::hasil_luasan');

// RKO

$routes->get('api/total_katalog_rko', 'C_dashboard_looker_rko::katalog');
$routes->get('api/perhitungan_nominal_rko', 'C_dashboard_looker_rko::perhitungan_nominal');
$routes->get('api/potensi_sharing_rko', 'C_dashboard_looker_rko::potensi_sharing');
$routes->get('api/rkap_rko', 'C_dashboard_looker_rko::rkap');
$routes->get('api/paket_kerjasama_rko', 'C_dashboard_looker_rko::paket_kerjasama');
$routes->get('api/jenis_kerjasama_rko', 'C_dashboard_looker_rko::jenis_kerjasama');
$routes->get('api/kerjasama_regional_rko', 'C_dashboard_looker_rko::kerjasama_regional');
$routes->get('api/total_kerjasama_regional_rko', 'C_dashboard_looker_rko::total_kerjasama_regional');
$routes->get('api/hasil_luasan_rko', 'C_dashboard_looker_rko::hasil_luasan');


// Untuk dashboard RKAP
$routes->get('dashboard/getRegions', 'C_dashboard_rkap::getRegions');
$routes->get('dashboard/getChartData', 'C_dashboard_rkap::getChartData');
$routes->get('dashboard/getAllRegionsChartData', 'C_dashboard_rkap::getAllRegionsChartData');

// Untuk report
$routes->POST('report/reportcash', 'Reports\ReportCashController::exportExcel');
$routes->get('reportcash', 'Reports\ReportCashController::index');
$routes->post('excel/upload', 'C_upload_excel::upload');
$routes->post('excel/upload_kerjasama', 'C_upload_excel::upload_kerjasama');

$routes->post('get-regions', 'C_dashboard_aset::getRegionsCompany');




// Route for AMANAT
#KML
// End Route for AMANAT

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
