# Analisis Program Aplikasi Penjualan

## 1. Gambaran Umum Program

Program ini merupakan sebuah **Sistem Informasi Penjualan** berbasis web yang dibangun menggunakan bahasa pemrograman PHP dan database MySQL. Aplikasi ini dirancang untuk membantu pengelolaan data penjualan barang dengan fitur-fitur lengkap mulai dari pencatatan transaksi, manajemen customer, pelaporan, hingga sistem reward poin otomatis. Nama program yang tertera adalah "App Penjualan" dan menampilkan identitas brand sederhana dengan ikon toko pada dashboard utama. Program ini memiliki struktur yang modular dengan pemisahan antara tampilan (view), logika bisnis (controller), dan koneksi database (model), meskipun belum mengikuti standar MVC secara penuh. UI/UX dibangun menggunakan framework Bootstrap 5 yang memberikan tampilan modern dan responsif, sehingga dapat diakses dengan nyaman dari berbagai ukuran layar perangkat.

Dari segi arsitektur, program ini mengimplementasikan beberapa konsep database lanjutan yang menunjukkan pemahaman mendalam tentang basis data relasional. Aplikasi menggunakan **Stored Procedures** untuk operasi kompleks seperti penambahan dan pembaruan transaksi, **Triggers** untuk pencatatan log aktivitas secara otomatis, **Views** untuk penyederhanaan query pelaporan, dan **Functions** untuk perhitungan reward poin berdasarkan total pembelian customer. Pendekatan ini tidak hanya membuat kode lebih terorganisir tetapi juga meningkatkan keamanan dan kinerja database dengan mengurangi traffic data antara aplikasi dan server database.

## 2. Struktur File dan Direktori

Program ini tersimpan dalam struktur direktori yang terorganisir dengan baik di dalam folder `/workspace/penjualan/penjualan/`. Setiap file memiliki fungsi spesifik yang mendukung operasional sistem secara keseluruhan. Struktur file terdiri dari sepuluh file utama yang masing-masing memiliki tanggung jawab tertentu dalam arsitektur aplikasi. File `koneksi.php` berfungsi sebagai file konfigurasi koneksi database yang akan di-include oleh semua file lainnya. File `index.php` merupakan halaman dashboard utama yang menampilkan statistik dan data transaksi terkini. File `tambah.php` menyediakan formulir untuk pencatatan transaksi baru, sementara `edit.php` digunakan untuk memodifikasi data transaksi yang sudah ada. File `proses.php` menangani semua logika pemrosesan data mulai dari penambahan, pembaruan, hingga penghapusan. File `laporan.php` menyediakan antarmuka untuk melihat laporan penjualan berdasarkan periode tertentu, dan `cetak_laporan.php` berfungsi untuk menghasilkan output cetak dalam format yang siap dicetak. File `tambah_customer.php` khusus untuk penambahan data customer baru, dan `db_penjualan.sql` berisi skema lengkap database beserta data sampel.

Struktur ini menunjukkan bahwa program mengikuti pola arsitektur yang cukup sederhana namun efektif untuk aplikasi web berukuran kecil hingga menengah. Pemisahan tanggung jawab antar file memungkinkan pemeliharaan dan pengembangan lebih mudah, karena perubahan pada satu komponen tidak akan mempengaruhi komponen lainnya secara signifikan. Setiap file PHP secara konsisten menyertakan file `koneksi.php` di bagian atas untuk memastikan koneksi database tersedia sebelum menjalankan operasi apapun. Penggunaan pola include ini juga memungkinkan penggunaan variabel dan fungsi database di seluruh bagian aplikasi secara konsisten.

## 3. Konfigurasi Database

File `koneksi.php` menyimpan konfigurasi koneksi database yang menggunakan MySQLi extension dengan pendekatan prosedural. Konfigurasi default menggunakan host `localhost` dengan user `root` dan password kosong, yang merupakan setting standar untuk pengembangan lokal. Database yang digunakan diberi nama `db_penjualan`, dan koneksi diverifikasi menggunakan fungsi `mysqli_connect()` dengan mekanisme error handling dasar menggunakan `die()` jika koneksi gagal. Konfigurasi ini dapat dengan mudah disesuaikan untuk environment produksi dengan mengubah nilai variabel sesuai dengan konfigurasi server yang sebenarnya. Penggunaan konfigurasi di file terpisah memudahkan migrasi antar environment dan meningkatkan keamanan dengan menyimpan kredensial di satu lokasi terpusat.

Konfigurasi database yang digunakan mendukung karakter encoding UTF-8 melalui setting di file SQL, yang memastikan bahwa karakter khusus seperti huruf Indonesia dapat disimpan dan ditampilkan dengan benar. Hal ini penting untuk aplikasi yang beroperasi di pasar Indonesia dimana banyak data customer menggunakan nama dalam bahasa Indonesia. MySQLi juga menyediakan prepared statements yang dapat digunakan untuk mencegah SQL injection, meskipun dalam implementasi saat ini program masih menggunakan interpolasi string langsung dalam query yang sebaiknya segera diperbaiki untuk production environment.

## 4. Arsitektur Database

### 4.1 Skema Tabel

Database `db_penjualan` terdiri dari enam tabel utama yang saling berelasi untuk menyimpan data operasional sistem penjualan. Tabel **barang** menyimpan informasi produk dengan kolom `kode_barang` sebagai primary key, `nama_barang`, dan `harga`. Data sampel yang tersedia meliputi lima produk yaitu Laptop Asus A416 (Rp 7.500.000), Laptop Lenovo V14 (Rp 6.800.000), PC Rakitan i5 Gen10 (Rp 8.200.000), Monitor Samsung 24" (Rp 1.850.000), dan Keyboard Mechanical RGB (Rp 450.000). Tabel **customer** menyimpan data pelanggan dengan `customer_id` sebagai primary key dan `nama_customer`. Data sampel mencakup lima customer yaitu Abdul, Gilang Erlangga, Maulana, Adira, dan Diza. Tabel **sales** menyimpan data sales atau marketing dengan `Sales_Id` sebagai primary key dan `nama_sales`, dengan lima sales sampel yaitu Andi Saputra, Dewi Lestari, Rama Pratama, Citra Angraini, dan Fauzan Rahman.

Tabel **transaksi** merupakan tabel utama yang menyimpan header data transaksi dengan `no_faktur` sebagai primary key, `tanggal`, `total_jumlah`, `jumlah_unit`, `debit_mandiri`, `customer_id` sebagai foreign key ke tabel customer, dan `sales_id` sebagai foreign key ke tabel sales. Tabel **detail_transaksi** menyimpan item-item transaksi dengan `no_faktur` sebagai foreign key ke tabel transaksi, `kode_barang` sebagai foreign key ke tabel barang, `banyak` untuk jumlah item, dan `Jumlah` untuk total per item. Desain ini mengikuti pola header-detail yang umum digunakan dalam sistem aplikasi penjualan. Tabel **log_aktivitas** digunakan untuk menyimpan catatan aktivitas sistem dengan kolom `id` auto-increment, `pesan` untuk deskripsi aktivitas, dan `waktu` untuk timestamp aktivitas tersebut.

### 4.2 Relasi dan Constraints

Database menerapkan relasi referensial melalui foreign key constraints yang memastikan integritas data antar tabel. Relasi antara tabel `transaksi` dan `customer` menggunakan constraint `ON DELETE SET NULL` yang berarti jika customer dihapus, data transaksi tidak akan hilang melainkan field `customer_id` menjadi NULL. Relasi antara `transaksi` dan `sales` juga menggunakan pola serupa. Untuk tabel `detail_transaksi`, relasi ke `transaksi` menggunakan `ON DELETE CASCADE` yang berarti penghapusan transaksi akan secara otomatis menghapus semua detail transaksi terkait. Relasi ke tabel `barang` menggunakan `ON DELETE CASCADE` dengan pertimbangan bahwa jika produk dihapus, item terkait dalam detail transaksi juga harus dihapus untuk menjaga konsistensi data.

Penerapan foreign key constraints ini menunjukkan pemahaman yang baik tentang desain database relasional. Constraints tidak hanya menjaga integritas data tetapi juga menyederhanakan operasi penghapusan karena database akan menangani penghapusan data terkait secara otomatis. Hal ini mengurangi kemungkinan terjadinya orphan records atau data yatim (data yang tidak memiliki referensi ke data master).

## 5. Fitur dan Fungsionalitas

### 5.1 Dashboard Utama

Halaman dashboard yang diakses melalui `index.php` menampilkan ringkasan statistik penjualan secara real-time menggunakan tiga kartu metrik utama. Kartu pertama menampilkan **Total Transaksi** yang dihitung menggunakan fungsi agregat `COUNT(*)`, memberikan gambaran tentang volume aktivitas penjualan. Kartu kedua menampilkan **Total Omset** yang dijumlahkan menggunakan `SUM(total_jumlah)`, menunjukkan total pendapatan dari semua transaksi. Kartu ketiga menampilkan **Rata-rata Transaksi** menggunakan `AVG(total_jumlah)`, memberikan insight tentang nilai transaksi rata-rata. Dashboard juga menampilkan tabel data transaksi terkini yang diurutkan berdasarkan tanggal secara descending dengan limit 10 records. Setiap baris transaksi menampilkan informasi lengkap meliputi nomor faktur, tanggal, nama barang, customer, sales, total transaksi, dan reward poin. Kolom aksi menyediakan tombol edit dan hapus untuk setiap transaksi.

Dashboard dilengkapi dengan panel log aktivitas yang menampilkan 10 aktivitas terakhir menggunakan data dari tabel `log_aktivitas`. Aktivitas yang dicatat meliputi penambahan customer baru dan pencatatan transaksi baru. Log aktivitas di-populate secara otomatis oleh trigger database yang akan dibahas selanjutnya. Dashboard juga menampilkan informasi sistem yang menjelaskan penggunaan SQL View, Stored Function, dan Trigger dalam sistem, memberikan edukasi bagi pengguna tentang teknologi yang digunakan di balik layar.

### 5.2 Pencatatan Transaksi

Fitur pencatatan transaksi baru diakses melalui `tambah.php` yang menyediakan formulir komprehensif untuk input data transaksi. Formulir meminta pengisian nomor faktur, tanggal transaksi, customer, sales, serta detail barang yang dibeli. Sistem menggunakan dropdown yang di-populate secara dinamis dari database untuk pemilihan customer, sales, dan barang. Fitur **perhitungan otomatis** diimplementasikan menggunakan JavaScript yang menghitung total harga berdasarkan harga barang dikalikan jumlah unit yang dipilih. Dropdown barang menyimpan data harga dalam attribute `data-harga` yang dibaca oleh JavaScript untuk melakukan kalkulasi secara real-time saat pengguna mengubah pilihan barang atau jumlah unit. Formulir juga menampilkan estimasi total dalam format Rupiah Indonesia yang mudah dibaca.

Setelah formulir disubmit, data diproses oleh `proses.php` dengan memanggil stored procedure `sp_tambah_transaksi`. Stored procedure ini menerima parameter berupa nomor faktur, tanggal, jumlah unit, ID customer, ID sales, dan kode barang. Di dalam procedure, sistem mengambil harga barang dari tabel master, menghitung total harga, dan melakukan insert ke tabel `transaksi` serta `detail_transaksi` dalam satu transaksi atomik. Pendekatan stored procedure ini memastikan bahwa kedua insert berhasil atau keduanya gagal bersama, menjaga konsistensi data transaksi.

### 5.3 Manajemen Customer

Program menyediakan fitur manajemen customer melalui `tambah_customer.php` yang memungkinkan penambahan customer baru ke sistem. Formulir meminta pengisian ID customer maksimal 2 karakter dan nama customer. Sistem menampilkan daftar ID yang sudah terpakai sebagai panduan untuk menghindari duplikasi ID. Proses penambahan customer ditangani oleh stored procedure `sp_tambah_customer` yang melakukan insert ke tabel customer. Trigger `tr_after_insert_customer` akan secara otomatis mencatat aktivitas penambahan customer ke tabel `log_aktivitas` dengan format pesan yang berisi nama customer dan ID customer yang baru ditambahkan.

Manajemen customer yang relatif sederhana ini sudah memenuhi kebutuhan dasar sistem penjualan dimana customer tidak memerlukan informasi detail seperti alamat, telepon, atau email. Namun untuk pengembangan ke depan, pertimbangkan untuk menambahkan field-field tersebut untuk keperluan pelacakan dan komunikasi dengan customer.

### 5.4 Sistem Laporan

Modul laporan yang diakses melalui `laporan.php` menyediakan kemampuan untuk melihat dan memfilter data penjualan berdasarkan periode tanggal. Antarmuka pengguna menampilkan formulir filter dengan dua input date untuk tanggal awal dan akhir periode yang ingin dilaporkan. Secara default, filter tanggal awal diset ke tanggal pertama bulan berjalan dan tanggal akhir ke tanggal saat ini, memudahkan pengguna untuk langsung melihat laporan bulan berjalan. Tabel laporan menampilkan data dari view `v_laporan_lengkap` yang menggabungkan data dari tabel transaksi, customer, sales, dan barang. Setiap baris menampilkan nomor urut, nomor faktur, tanggal, nama barang dengan jumlah unit, nama customer, dan total jumlah dalam format Rupiah.

Footer tabel menampilkan total omset untuk periode yang dipilih yang dihitung dengan menjumlahkan field `total_jumlah` dari semua records yang sesuai dengan filter. Sistem juga menyediakan tombol cetak yang membuka `cetak_laporan.php` di tab baru dengan parameter periode yang dipilih. Halaman cetak menggunakan CSS `@media print` untuk memastikan output sesuai dengan kertas A4 dan menghilangkan elemen-elemen non-penting saat pencetakan.

### 5.5 Edit dan Hapus Data

Fitur pengeditan transaksi tersedia melalui `edit.php` yang diakses dari dashboard dengan mengklik tombol edit pada transaksi yang ingin diubah. Halaman ini menampilkan formulir yang serupa dengan halaman tambah namun dengan data existing yang sudah terisi. Nomor faktur ditampilkan dalam kondisi read-only karena merupakan primary key yang tidak boleh diubah. Pengguna dapat mengubah tanggal transaksi, customer, sales, barang, dan jumlah unit. Saat formulir disubmit, `proses.php` akan memanggil stored procedure `sp_update_transaksi` yang melakukan pembaruan data di tabel `transaksi` dan `detail_transaksi` secara bersamaan. Procedure juga menghitung ulang total harga berdasarkan harga barang terkini dan jumlah unit baru.

Fitur penghapusan data diakses melalui link di dashboard dengan konfirmasi sebelum eksekusi. Proses penghapusan dilakukan di `proses.php` dengan dua langkah yaitu menghapus detail transaksi terlebih dahulu baru kemudian menghapus transaksi utama. Urutan ini diperlukan karena constraint foreign key dengan `ON DELETE CASCADE` memastikan bahwa penghapusan transaksi utama akan menghapus detail secara otomatis, namun implementasi saat ini melakukan penghapusan eksplisit untuk kedua tabel.

## 6. Fitur Database Lanjutan

### 6.1 Stored Procedures

Program mengimplementasikan tiga stored procedures untuk mengencapsulasi logika bisnis di sisi database. Stored procedure **sp_tambah_customer** menerima parameter ID dan nama customer, kemudian melakukan insert ke tabel customer. Procedure ini menyediakan satu titik kontrol untuk penambahan customer dan dapat dengan mudah diperluas untuk validasi atau logika tambahan. Stored procedure **sp_tambah_transaksi** menangani pencatatan transaksi baru dengan logika yang lebih kompleks. Procedure ini mendeklarasikan variabel lokal untuk menyimpan harga dan total, mengambil harga dari tabel barang berdasarkan kode barang yang dipilih, menghitung total harga, kemudian melakukan insert ke tabel `transaksi` dan `detail_transaksi`. Pendekatan ini memastikan konsistensi data karena kedua insert dilakukan dalam satu procedure yang sama.

Stored procedure **sp_update_transaksi** menangani pembaruan data transaksi dengan logika serupa dengan prosedur penambahan. Procedure ini mengambil harga barang terbaru, menghitung ulang total, kemudian melakukan update pada kedua tabel (`transaksi` dan `detail_transaksi`) berdasarkan nomor faktur yang diberikan. Penggunaan stored procedures untuk operasi CRUD memberikan beberapa keuntungan yaitu mengurangi network traffic karena logika bisnis dijalankan di server database, meningkatkan keamanan dengan membatasi akses langsung ke tabel, dan memudahkan pemeliharaan karena perubahan logika bisnis cukup dilakukan di satu tempat.

### 6.2 Triggers

Dua trigger diimplementasikan untuk otomatisasi pencatatan aktivitas sistem. Trigger **tr_after_insert_customer** dijalankan setelah operasi insert pada tabel `customer`. Trigger ini mengambil data customer yang baru diinsert (menggunakan NEW keyword) dan menyusun pesan log yang berisi nama customer dan ID-nya, kemudian melakukan insert ke tabel `log_aktivitas` dengan timestamp saat ini. Trigger **tr_after_insert_transaksi** dijalankan setelah operasi insert pada tabel `transaksi`. Trigger ini menyusun pesan log dengan format "Transaksi baru: [nomor_faktur]" dan mencatatnya ke tabel log aktivitas. Kedua trigger menggunakan fungsi `NOW()` untuk mendapatkan timestamp aktual dan `CONCAT()` untuk menyusun pesan log yang informatif.

Penggunaan trigger ini menunjukkan implementasi pola **audit trail** yang baik, dimana sistem secara otomatis mencatat aktivitas penting tanpa memerlukan kode tambahan di aplikasi. Trigger berjalan di level database sehingga akan aktif terlepas dari bagaimana data diinsert, baik melalui aplikasi web, query langsung, atau tools administrasi database. Hal ini memastikan konsistensi pencatatan aktivitas dalam semua skenario.

### 6.3 Views

View **v_laporan_lengkap** dibuat untuk menyederhanakan query pelaporan yang melibatkan join dari empat tabel. View ini melakukan left join antara tabel `transaksi` dengan `customer`, `sales`, `detail_transaksi`, dan `barang` untuk mendapatkan gambaran lengkap tentang setiap transaksi. Penggunaan `COALESCE()` pada kolom nama customer dan nama sales menangani kasus dimana customer atau sales mungkin sudah dihapus dengan menampilkan nilai default "Tanpa Nama" atau "-". Untuk nama barang, ditampilkan "Item Terhapus" jika barang terkait sudah tidak ada di master barang. View ini kemudian digunakan di berbagai tempat dalam aplikasi termasuk dashboard, halaman laporan, dan halaman cetak laporan, menyederhanakan kode PHP dan mengurangi kemungkinan kesalahan dalam penulisan query kompleks.

### 6.4 Stored Functions

Fungsi **f_hitung_poin** diimplementasikan untuk menghitung reward poin customer berdasarkan total pembelian. Fungsi ini menerima parameter total pembelian dan mengembalikan nilai poin berdasarkan kriteria yang telah ditetapkan. Customer dengan total pembelian >= 10 juta получит 100 poin, >= 5 juta получит 50 poin, >= 1 juta получит 10 poin, dan di bawah itu получит 0 poin. Sistem reward poin ini ditampilkan di dashboard dengan badge khusus untuk poin di atas 20. Penggunaan stored function memungkinkan kalkulasi poin dilakukan secara konsisten di seluruh aplikasi, baik di query dashboard maupun di tempat lain jika diperlukan, tanpa duplikasi logika.

## 7. Teknologi dan Framework

### 7.1 Backend

Program dibangun menggunakan **PHP** sebagai bahasa pemrograman backend dengan versi yang terdeteksi adalah PHP 8.3.6 berdasarkan informasi dari dump database. Kode menggunakan sintaks PHP modern seperti short echo tags `<?= ?>` yang tersedia sejak PHP 5.4, dan struktur kontrol alternatif seperti `endif` yang digunakan dalam beberapa tempat. Database yang digunakan adalah **MySQL 8.0.44** dengan engine penyimpanan InnoDB yang mendukung transaksi dan foreign key constraints. Koneksi database menggunakan MySQLi extension dengan mode prosedural, pilihan yang tepat untuk PHP versi modern. Penggunaan MySQLi memungkinkan prepared statements yang lebih aman, meskipun implementasi saat ini belum sepenuhnya memanfaatkan fitur ini.

### 7.2 Frontend

Antarmuka pengguna dibangun menggunakan **Bootstrap 5.3.0** sebagai framework CSS utama, memberikan tampilan modern dan responsif tanpa memerlukan penulisan CSS kustom yang extensive. Bootstrap Icons 1.11.1 digunakan untuk menambahkan ikon-ikon yang mempercantik tampilan antarmuka. Penggunaan komponen Bootstrap meliputi navbar untuk navigasi, cards untuk menampilkan data dalam kotak, tables dengan styling untuk data grids, forms dengan validasi visual, dan buttons dengan berbagai varian warna. Beberapa custom CSS ditambahkan untuk efek hover pada kartu statistik, styling tabel, dan scroll area untuk log aktivitas. JavaScript digunakan secara minimal untuk kalkulasi harga real-time di halaman tambah dan edit transaksi, menggunakan API Intl.NumberFormat untuk formatting Rupiah yang benar.

### 7.3 Kompatibilitas

Program didesain untuk berjalan di environment Linux (berdasarkan Versi Ubuntu yang terdeteksi di server database) dengan web server Apache atau Nginx. Tidak ada dependensi pada ekstensi PHP khusus yang tidak umum, sehingga kompatibilitas dengan berbagai konfigurasi hosting cukup baik. Database MySQL 8.0 memerlukan setting karakter set UTF-8 yang sudah diimplementasikan dalam dump SQL. Bootstrap 5 memerlukan browser modern yang mendukung CSS Grid dan Flexbox, namun secara umum sudah didukung oleh semua browser utama versi terbaru.

## 8. Data Sampel

Program dilengkapi dengan data sampel yang memungkinkan pengguna untuk langsung melihat dan menguji sistem tanpa harus memasukkan data manual. Data barang mencakup lima produk dengan harga yang realistis untuk toko komputer yaitu Laptop Asus A416 (Rp 7.500.000), Laptop Lenovo V14 (Rp 6.800.000), PC Rakitan i5 Gen10 (Rp 8.200.000), Monitor Samsung 24" (Rp 1.850.000), dan Keyboard Mechanical RGB (Rp 450.000). Data customer meliputi lima nama yaitu Abdul, Gilang Erlangga, Maulana, Adira, dan Diza. Data sales mencakup lima sales yaitu Andi Saputra, Dewi Lestari, Rama Pratama, Citra Angraini, dan Fauzan Rahman.

Satu transaksi sampel dicatat dengan nomor faktur F007, tanggal 22 Januari 2026, customer Diza (ID 6), sales Andi Saputra (ID 01), produk Laptop Asus A416 (B1), 1 unit, dengan total Rp 7.500.000 dan pembayaran debit mandiri Rp 1.350.000. Log aktivitas mencatat 14 aktivitas meliputi penambahan customer dan pencatatan transaksi. Data sampel ini memberikan gambaran yang cukup lengkap tentang bagaimana data akan ditampilkan di berbagai bagian aplikasi dan memungkinkan pengujian fitur CRUD dengan data yang sudah tersedia.

## 9. Kesimpulan dan Rekomendasi

### 9.1 Kekuatan Program

Program Aplikasi Penjualan ini menunjukkan implementasi yang solid untuk sistem informasi penjualan berbasis web. Penggunaan konsep database lanjutan seperti stored procedures, triggers, views, dan functions mendemonstrasikan pemahaman yang baik tentang kemampuan database MySQL dan cara memanfaatkannya untuk membangun sistem yang robust. Struktur file yang terorganisir memudahkan pemeliharaan dan pengembangan lebih lanjut. Integrasi Bootstrap 5 memberikan antarmuka yang profesional dan responsif tanpa effort development yang besar. Fitur pelaporan dengan filter periode dan kemampuan cetak memberikan nilai tambah yang signifikan untuk kebutuhan operasional sehari-hari.

### 9.2 Area Pengembangan

Untuk meningkatkan kualitas program, beberapa area dapat dipertimbangkan untuk pengembangan. Pertama, implementasi **prepared statements** untuk semua query akan meningkatkan keamanan terhadap SQL injection attacks. Kedua, penambahan sistem autentikasi dan otorisasi akan membatasi akses ke fungsi-fungsi administratif. Ketiga, penambahan fitur pencarian dan filter lanjutan di dashboard akan meningkatkan usability untuk dataset yang besar. Keempat, penambahan validasi form yang lebih robust di sisi server akan meningkatkan reliabilitas data. Kelima, migrasi ke pola MVC atau penggunaan framework PHP seperti Laravel akan memudahkan pengembangan skala besar. Keenam, penambahan fitur export ke format PDF dan Excel akan meningkatkan fleksibilitas laporan. Ketujuh, penambahan field tambahan untuk customer (alamat, telepon, email) akan memperkaya data untuk keperluan komunikasi dan analisis.

### 9.3 Penilaian Akhir

Secara keseluruhan, program ini merupakan **proyek akademik atau portofolio yang sangat baik** yang mendemonstrasikan kemampuan pengembangan web full-stack dengan pemahaman yang kuat tentang database. Kode bersih, terorganisir, dan terdokumentasi dengan baik melalui komentar inline. Konsep-konsep database yang diimplementasikan menunjukkan pemahaman tentang best practices dalam desain database relasional. Program ini cocok sebagai titik awal untuk sistem penjualan yang lebih kompleks atau sebagai referensi pembelajaran untuk memahami integrasi antara PHP, MySQL, dan Bootstrap dalam konteks aplikasi web nyata.
