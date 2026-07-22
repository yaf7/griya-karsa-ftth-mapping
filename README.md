# Griya Karsa FTTH Mapping

**Dikerjakan oleh:** Deyafa Arsetya

---

##  Tentang Aplikasi
**Griya Karsa FTTH Mapping** adalah sebuah aplikasi berbasis web yang dirancang khusus untuk memanajemen dan memetakan topologi jaringan *Fiber To The Home* (FTTH). Aplikasi ini memudahkan Internet Service Provider (ISP) atau pengelola jaringan lokal untuk memantau, mendata, serta memvisualisasikan persebaran infrastruktur fiber optik dan pelanggan di lapangan secara interaktif.

##  Fungsi Utama
Fungsi dari aplikasi ini adalah sebagai pusat pendataan infrastruktur jaringan FTTH, mulai dari titik pusat (Server), node distribusi (ODC), titik akhir distribusi (ODP), jalur tarikan kabel (line/route), hingga rumah pelanggan (Client). Dengan fitur pemetaan (map), admin jaringan dapat dengan mudah mengetahui letak kabel dan perangkat, serta jalur koneksi antar node ke rumah pelanggan, sehingga mempermudah proses pemeliharaan (maintenance) dan penyelesaian gangguan (troubleshooting).

##  Fitur Utama

- **📊 Dashboard Interaktif**  
  Menampilkan ringkasan informasi dan statistik jumlah pelanggan (client) serta jumlah perangkat distribusi (Optical Distribution).

- **🗺️ Pemetaan Interaktif (Map View)**  
  Visualisasi topologi jaringan secara visual pada peta. Menampilkan rute kabel jaringan (line), posisi perangkat distribusi (ODP/ODC/Server), dan lokasi letak pelanggan, semuanya saling terhubung dalam satu peta interaktif.

- **🖧 Manajemen Optical Distribution (ODP/ODC/Server)**  
  Fitur pendataan perangkat-perangkat penting dalam jaringan fiber optik lengkap dengan koordinat lokasinya.

- **🛤️ Manajemen Jalur Kabel (Line)**  
  Fitur untuk mendata dan menggambar rute tarikan kabel fiber optik dari satu titik ke titik lainnya.

- **👥 Manajemen Pelanggan (Client)**  
  Fitur untuk menyimpan data detail pelanggan, letak rumah (titik koordinat), dan menghubungkannya dengan ODP terdekat serta paket internet yang digunakan.

- **📦 Manajemen Paket & Kategori**  
  Pengaturan daftar paket layanan internet yang ditawarkan beserta kategori perangkat infrastrukturnya.

##  Teknologi yang Digunakan
- **Framework:** Laravel 12 (PHP ^8.2)
- **Pemetaan (Mapping):** Integrasi Peta/WebGIS (Leaflet)
- **Database:** Relational Database (MySQL)

---
*Dibuat dengan sepenuh hati oleh **Deyafa Arsetya**.*
