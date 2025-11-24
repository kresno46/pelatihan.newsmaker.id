# TODO: Implement Separate Pages for Adding and Editing Jadwal Absensi

## Tugas Utama

-   [x] Add routes for absensi.create (GET /laporan/absensi/create) and absensi.edit (GET /laporan/absensi/{id}/edit) in routes/web.php
-   [x] Update JadwalAbsensiController: change create() to return view('jadwal.create'), and edit() to return view('jadwal.edit')
-   [x] Create resources/views/jadwal/create.blade.php with provided content
-   [x] Create resources/views/jadwal/edit.blade.php with provided content
-   [x] Update resources/views/jadwal/index.blade.php: remove add and edit modals, change "Tambah Sesi" button to link to absensi.create, change edit button to link to absensi.edit

## Additional UI Updates

-   [x] Move "Tambah Sesi" button to header
-   [x] Add search bar between separator and body
-   [x] Update JadwalAbsensiController index method to handle search filtering

## Status

-   [x] Plan approved by user
-   [x] Implementation started
-   [x] Implementation completed
-   [x] Additional UI updates completed
