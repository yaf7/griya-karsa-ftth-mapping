@if($mode !== 'view')
<div class="mb-3">
    <label for="kordinat" class="form-label">Koordinat</label>
    <textarea name="kordinat" id="kordinat" rows="3"
              class="form-control"
              placeholder='[[-6.2,106.8],[-6.21,106.81]] atau -6.2,106.8'>{{ old('kordinat', $kordinat ?? '') }}</textarea>
    <small class="text-muted">
        @if($mode === 'create_line')
            Gunakan tool garis di peta untuk membuat jalur
        @elseif($mode === 'create_point')
            Klik peta untuk memilih titik
        @endif
    </small>
</div>
@endif


<div id="map" style="height: 500px; border:1px solid #ddd; border-radius:6px;"></div>

<!-- Modal Gambar Besar -->
<div id="imgModal" class="img-modal" style="display:none;">
    <span class="img-modal-close" onclick="closeImageModal()">&times;</span>
    <img class="img-modal-content" id="imgModalSrc" src="" alt="foto besar" />
</div>

@push('scripts')

{{-- Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

{{-- Leaflet Geocoder --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

{{-- Leaflet Draw --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

{{-- Geometry Util --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-geometryutil/0.10.0/leaflet.geometryutil.min.js"></script>

{{-- CSS untuk animasi garis & modal gambar --}}
<style>
    .animated-path {
        stroke-dasharray: 12 8;          /* panjang garis 12px, spasi 8px */
        animation: dashmove 1s linear infinite;
        stroke-linecap: round;          /* ujung tiap dash bulat */
        stroke-linejoin: round;  
    }
    @keyframes dashmove {
        to { stroke-dashoffset: 20; }
    }
    /* Modal Gambar */
    .img-modal {
        display: flex;
        position: fixed;
        z-index: 99999;
        left: 0; top: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.7);
        align-items: center; justify-content: center;
    }
    .img-modal-content {
        max-width: 90vw;
        max-height: 90vh;
        border-radius: 10px;
        box-shadow: 0 2px 16px #0008;
        background: #fff;
        padding: 8px;
        display: block;
        margin: auto;
    }
    .img-modal-close {
        position: absolute;
        top: 24px;
        right: 40px;
        color: #fff;
        font-size: 2.5em;
        font-weight: bold;
        cursor: pointer;
        z-index: 100000;
        text-shadow: 0 2px 8px #000a;
    }
</style>


<script>
document.addEventListener("DOMContentLoaded", function () {
    // =========================
    // BASE MAP
    // =========================
    let osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' });
    let satellite = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20, subdomains: ['mt0','mt1','mt2','mt3'], attribution: '© Google Satellite'
    });
    let map = L.map('map', {
        center: [-6.2, 106.8],
        zoom: 13,
        layers: [satellite]
    });
    window.map = map;
    L.control.layers({ "Standard": osm, "Satelit": satellite }).addTo(map);
    let drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    // =========================
    // MODE HANDLING
    // =========================
    @if($mode === 'create_line')
        let drawControl = new L.Control.Draw({
            draw: { polyline: true, polygon: false, rectangle: false, circle: false, circlemarker: false, marker: false },
            edit: { featureGroup: drawnItems }
        });
        map.addControl(drawControl);
        map.on(L.Draw.Event.CREATED, function (e) {
            let layer = e.layer;
            drawnItems.addLayer(layer);
            if (e.layerType === 'polyline') {
                let latlngs = layer.getLatLngs();
                let coords = latlngs.map(ll => [ll.lat, ll.lng]);
                document.getElementById('kordinat').value = JSON.stringify(coords);
                let length = L.GeometryUtil.length(latlngs);
                let km = (length / 1000).toFixed(2);
                layer.bindPopup("Panjang: " + km + " km").openPopup();
            }
        });
        map.on(L.Draw.Event.EDITED, function (e) {
            e.layers.eachLayer(function (layer) {
                let coords = layer.getLatLngs().map(ll => [ll.lat, ll.lng]);
                document.getElementById('kordinat').value = JSON.stringify(coords);
                let length = L.GeometryUtil.length(layer.getLatLngs());
                let km = (length / 1000).toFixed(2);
                layer.bindPopup("Panjang: " + km + " km").openPopup();
            });
        });
        map.on(L.Draw.Event.DELETED, function () {
            document.getElementById('kordinat').value = '';
        });
    @elseif($mode === 'create_point')
        let marker = null;
        map.on('click', function (e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('kordinat').value = e.latlng.lat + "," + e.latlng.lng;
        });
        // Polyline ukur jarak
        let drawControlCreatePoint = new L.Control.Draw({
            draw: { polyline: true, polygon: false, rectangle: false, circle: false, circlemarker: false, marker: false },
            edit: false
        });
        map.addControl(drawControlCreatePoint);
        map.on(L.Draw.Event.CREATED, function (e) {
            if (e.layerType === 'polyline') {
                let layer = e.layer;
                map.addLayer(layer);
                let latlngs = layer.getLatLngs();
                let length = L.GeometryUtil.length(latlngs);
                let km = (length / 1000).toFixed(2);
                layer.bindPopup("Jarak: " + km + " km").openPopup();
            }
        });
    @elseif($mode === 'edit_line')
        let oldData = document.getElementById('kordinat').value;
        if (oldData && oldData.startsWith("[")) {
            try {
                let parsed = JSON.parse(oldData);
                let existingLine = L.polyline(parsed, { color: 'blue' }).addTo(drawnItems);
                map.fitBounds(existingLine.getBounds());
                let drawControl = new L.Control.Draw({
                    draw: false,
                    edit: { featureGroup: drawnItems, remove: true }
                });
                map.addControl(drawControl);
                map.on(L.Draw.Event.EDITED, function (e) {
                    e.layers.eachLayer(function (layer) {
                        let coords = layer.getLatLngs().map(ll => [ll.lat, ll.lng]);
                        document.getElementById('kordinat').value = JSON.stringify(coords);
                        let length = L.GeometryUtil.length(layer.getLatLngs());
                        let km = (length / 1000).toFixed(2);
                        layer.bindPopup("Panjang: " + km + " km").openPopup();
                    });
                });
                map.on(L.Draw.Event.DELETED, function () {
                    document.getElementById('kordinat').value = '';
                });
            } catch (e) { console.error("invalid koordinat", e); }
        }
    @elseif($mode === 'edit_point')
        let oldData = document.getElementById('kordinat').value;
        let marker = null;
        if (oldData && oldData.includes(",")) {
            let coords = oldData.split(",");
            let latlng = [parseFloat(coords[0]), parseFloat(coords[1])];
            marker = L.marker(latlng, { draggable: true }).addTo(map);
            map.setView(latlng, 16);
            marker.on("dragend", function(e) {
                let pos = e.target.getLatLng();
                document.getElementById('kordinat').value = pos.lat + "," + pos.lng;
            });
        }
        map.on("click", function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng, { draggable: true }).addTo(map);
            document.getElementById('kordinat').value = e.latlng.lat + "," + e.latlng.lng;
            marker.on("dragend", function(e) {
                let pos = e.target.getLatLng();
                document.getElementById('kordinat').value = pos.lat + "," + pos.lng;
            });
        });
        // Polyline ukur jarak
        let drawControlEditPoint = new L.Control.Draw({
            draw: { polyline: true, polygon: false, rectangle: false, circle: false, circlemarker: false, marker: false },
            edit: false
        });
        map.addControl(drawControlEditPoint);
        map.on(L.Draw.Event.CREATED, function (e) {
            if (e.layerType === 'polyline') {
                let layer = e.layer;
                map.addLayer(layer);
                let latlngs = layer.getLatLngs();
                let length = L.GeometryUtil.length(latlngs);
                let km = (length / 1000).toFixed(2);
                layer.bindPopup("Jarak: " + km + " km").openPopup();
            }
        });
    @elseif($mode === 'view')
        let oldData = document.getElementById('kordinat').value;
        if (oldData) {
            try {
                if (oldData.startsWith("[")) {
                    let parsed = JSON.parse(oldData);
                    let line = L.polyline(parsed, {color: 'red'}).addTo(map);
                    map.fitBounds(line.getBounds());
                } else {
                    let coords = oldData.split(",");
                    if (coords.length === 2) {
                        let latlng = [parseFloat(coords[0]), parseFloat(coords[1])];
                        L.marker(latlng).addTo(map);
                        map.setView(latlng, 16);
                    }
                }
            } catch(e) { console.error("invalid koordinat", e); }
        }
        // Tambah fitur ukur jarak (polyline)
        let drawControlView = new L.Control.Draw({
            draw: { polyline: true, polygon: false, rectangle: false, circle: false, circlemarker: false, marker: false },
            edit: false
        });
        map.addControl(drawControlView);
        map.on(L.Draw.Event.CREATED, function (e) {
            if (e.layerType === 'polyline') {
                let layer = e.layer;
                map.addLayer(layer);
                let latlngs = layer.getLatLngs();
                let length = L.GeometryUtil.length(latlngs);
                let km = (length / 1000).toFixed(2);
                layer.bindPopup("Jarak: " + km + " km").openPopup();
            }
        });
    @endif

    // =========================
    // GEOCODER
    // =========================
    L.Control.geocoder({ defaultMarkGeocode: false })
        .on('markgeocode', function(e) { map.setView(e.geocode.center, 16); })
        .addTo(map);

    // =========================
    // LOCATE BUTTON
    // =========================
    let gpsMarker = null;
    let gpsIcon = L.icon({
        iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
        iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -28]
    });
    L.control.locate = function() {
        let control = L.control({position: 'topleft'});
        control.onAdd = function(map) {
            let button = L.DomUtil.create('button', 'btn btn-light btn-sm');
            button.innerHTML = '<span class="loc-icon"><i class="bi bi-geo-alt-fill"></i></span>';
            L.DomEvent.disableClickPropagation(button);
            L.DomEvent.disableScrollPropagation(button);
            // Tambah style overlay loading
            let style = document.createElement('style');
            style.innerHTML = `
                .overlay-loc {
                    position: fixed;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(255,255,255,0.7);
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .spinner-loc {
                    width: 3em;
                    height: 3em;
                    border: 6px solid #1976d2;
                    border-top: 6px solid #fff;
                    border-radius: 50%;
                    animation: spin-loc 0.7s linear infinite;
                }
                @keyframes spin-loc { 100% { transform: rotate(360deg); } }
            `;
            document.head.appendChild(style);
            // Fungsi untuk show/hide overlay
            function showLocOverlay() {
                let overlay = document.createElement('div');
                overlay.className = 'overlay-loc';
                overlay.id = 'overlay-loc';
                overlay.innerHTML = '<div class="spinner-loc"></div>';
                document.body.appendChild(overlay);
            }
            function hideLocOverlay() {
                let overlay = document.getElementById('overlay-loc');
                if (overlay) overlay.remove();
            }
            button.onclick = function(e){
                e.preventDefault();
                if (navigator.geolocation) {
                    showLocOverlay();
                    navigator.geolocation.getCurrentPosition(function(pos){
                        let latlng = [pos.coords.latitude, pos.coords.longitude];
                        map.setView(latlng, 16);
                        if (gpsMarker) map.removeLayer(gpsMarker);
                        gpsMarker = L.marker(latlng, {icon: gpsIcon}).addTo(map).bindPopup("Lokasi Saya");
                        hideLocOverlay();
                    }, function(){
                        hideLocOverlay();
                    });
                }
            };
            return button;
        };
        return control;
    };
    L.control.locate().addTo(map);

    // =========================
    // DATA CLIENTS, OPTICAL, LINES
    // =========================
    let dataClients = @json($clients);
    let dataOptical = @json($optical_distribution);
    let dataLine = @json($lines);

    // GARIS CLIENT → OPTICAL
    dataClients.forEach(function(client) {
    if (!client.kordinat || !client.id_optical_distribution) return;

    try {
        let clientCoords = client.kordinat.split(",");
        if (clientCoords.length !== 2) return;
        let clientLatLng = [parseFloat(clientCoords[0]), parseFloat(clientCoords[1])];

        let optical = dataOptical.find(o => o.id_optical_distribution == client.id_optical_distribution);
        if (!optical || !optical.kordinat) return;

        let opticalCoords = optical.kordinat.split(",");
        if (opticalCoords.length !== 2) return;
        let opticalLatLng = [parseFloat(opticalCoords[0]), parseFloat(opticalCoords[1])];

        // 1️⃣ Outline putih di belakang
        L.polyline([clientLatLng, opticalLatLng], {
            color: 'white',
            weight: 7,
            opacity: 1
        }).addTo(map);

        // 2️⃣ Garis utama hijau di atas
        L.polyline([clientLatLng, opticalLatLng], {
            color: "green",
            weight: 3,
            opacity: 1,
            dashArray: "6,8",
            lineCap: "round",
            className: "animated-path"
        }).addTo(map);

    } catch (e) {
        console.error("Gagal gambar garis client-optical", e);
    }
});


    // MARKER / POLYLINE LINE
    dataLine.forEach(function(item) {
        if (!item.kordinat) return;
        try {
            if (item.kordinat.startsWith("[")) {
                let parsed = JSON.parse(item.kordinat);
                let kategori = (item.optical_distribution?.kategori?.nama ?? "").toLowerCase();
                let warna = "red";
                if (kategori === "odp") warna = "orange";
                if (kategori === "odc") warna = "blue";
                L.polyline(parsed, { color: 'white', weight: 7, opacity: 1 }).addTo(map);
                let line = L.polyline(parsed, { color: warna, weight: 4, opacity: 1, dashArray: "10, 10", lineCap: "round", lineJoin: "round", className: 'animated-path' }).addTo(map);
                let latlngs = line.getLatLngs();
                let length = 0;
                for (let i = 0; i < latlngs.length - 1; i++) {
                    length += latlngs[i].distanceTo(latlngs[i + 1]);
                }
                let km = (length / 1000).toFixed(2);
                line.bindPopup(
                    "<b>ID Line:</b> " + item.id_line + "<br/>" +
                    "<b>Keterangan:</b> " + (item.keterangan ?? '-') + "<br/>" +
                    "<b>Kategori:</b> " + kategori.toUpperCase() + "<br/>" +
                    "<b>Panjang:</b> " + km + " km"
                );
            } else {
                let coords = item.kordinat.split(",");
                if (coords.length === 2) {
                    let latlng = [parseFloat(coords[0]), parseFloat(coords[1])];
                    L.marker(latlng).addTo(map).bindPopup(
                        "<b>ID Line:</b> " + item.id_line + "<br/>" +
                        "<b>Keterangan:</b> " + (item.keterangan ?? '-')
                    );
                }
            }
        } catch (e) {
            console.error("Invalid koordinat line", e);
        }
    });

    // MARKER ODP/ODC/SERVER
    dataOptical.forEach(function(item) {
        if (!item.kordinat) return;
        let coords = item.kordinat.split(",");
        if (coords.length !== 2) return;
        let latlng = [parseFloat(coords[0]), parseFloat(coords[1])];
        let iconUrl = "/images/optical.png";
        if (item.kategori?.nama) {
            let n = item.kategori.nama.toLowerCase();
            if (n.includes("odp")) iconUrl = "/images/odp.png";
            if (n.includes("odc")) iconUrl = "/images/odc.png";
            if (n.includes("server")) iconUrl = "/images/server.png";
        }
        let opticalIcon = L.icon({ iconUrl, iconSize:[32,32], iconAnchor:[16,32], popupAnchor:[0,-28] });
        L.marker(latlng, { icon: opticalIcon })
    .addTo(map)
    .bindPopup(
        "<b>ID Optical Distribution:</b> " + (item.id_optical_distribution ?? '-') + "<br>" +
        "<b>Kode:</b> " + (item.kode ?? '-') + "<br>" +
        "<b>ID Kategori:</b> " + (item.id_kategori ?? '-') + "<br>" +
        "<b>Inputan:</b> " + (item.inputan ?? '-') + "<br>" +
    "<b>Kordinat:</b> " + (item.kordinat ? `<a href='https://www.google.com/maps/search/?api=1&query=${item.kordinat}' target='_blank'>${item.kordinat}</a>` : '-') + "<br>" +
        "<b>Estimasi Redaman Input:</b> " + (item.estimasi_redaman_input ?? '-') + "<br>" +
        "<b>Estimasi Redaman Output:</b> " + (item.estimasi_redaman_output ?? '-') + "<br>" +
        "<b>Foto:</b> " + (item.foto 
            ? `<a href='#' onclick=\"showImageModal('/storage/${item.foto}')\"><img src='/storage/${item.foto}' alt='foto' style='max-width:80px;max-height:80px;border-radius:6px;cursor:pointer;'/></a>` 
            : '-') + "<br>" +
        "<b>Keterangan:</b> " + (item.keterangan ?? '-') + "<br>" +
        "<b>Dibuat:</b> " + (item.created_at ?? '-') + "<br>" +
        "<b>Update Terakhir:</b> " + (item.updated_at ?? '-')
    );

    });

    // MARKER CLIENT
    // Inisialisasi global marker client
    window._clientMarkers = window._clientMarkers || {};
    dataClients.forEach(function(client) {
        if (!client.kordinat) return;
        let coords = client.kordinat.split(",");
        if (coords.length !== 2) return;
        let latlng = [parseFloat(coords[0]), parseFloat(coords[1])];
        let clientIcon = L.icon({ iconUrl:"/images/homehijauputih.png", iconSize:[28,28], iconAnchor:[14,28], popupAnchor:[0,-25] });
        let marker = L.marker(latlng, {icon: clientIcon})
            .addTo(map)
            .bindPopup(
                `<b>ID Client:</b> ${client.id_client ?? '-'}<br/>
                <b>Kode:</b> ${client.kode ?? '-'}<br/>
                <b>Nomor:</b> ${client.nomor ?? '-'}<br/>
                <b>Nama:</b> ${client.nama ?? '-'}<br/>
                <b>Alamat:</b> ${client.alamat ?? '-'}<br/>
                <b>Kordinat:</b> ${client.kordinat ? `<a href='https://www.google.com/maps/search/?api=1&query=${client.kordinat}' target='_blank'>${client.kordinat}</a>` : '-'}<br/>
                <b>Foto:</b> ${client.foto ? `<a href='#' onclick=\"showImageModal('/storage/home/${client.foto}')\"><img src='/storage/home/${client.foto}' alt='foto' style='max-width:80px;max-height:80px;border-radius:6px;cursor:pointer;'/></a>` : '-'}<br/>
                <b>ID Optical Distribution:</b> ${client.optical_distribution?.kode ?? '-'}<br/>
                <b>User PPPoE:</b> ${client.user_pppoe ?? '-'}<br/>
                <b>Tanggal Registrasi:</b> ${client.tanggal_regis ?? '-'}<br/>
                <b>Tanggal Pembayaran:</b> ${client.tanggal_pembayaran ?? '-'}<br/>
                <b>Nama Paket:</b> ${client.paket?.nama_paket ?? '-'}<br/>
                <b>Kecepatan:</b> ${client.paket?.kecepatan ?? '-'}<br/>
                <b>Harga:</b> ${client.paket?.harga ?? '-'}<br/>
                <b>Status:</b> ${client.status ?? '-'}<br/>
                <b>Dibuat:</b> ${client.created_at ?? '-'}<br/>
                <b>Terahir Update At:</b> ${client.updated_at ?? '-'}<br/>
                `
            );
        window._clientMarkers[client.kordinat] = marker;
    });

    // GARIS CLIENT → OPTICAL (duplikat, bisa dihapus jika tidak perlu)
    // dataClients.forEach(function(client) {
    //     ...
    // });
});
// Modal Gambar Besar
function showImageModal(src) {
    var modal = document.getElementById('imgModal');
    var img = document.getElementById('imgModalSrc');
    img.src = src;
    modal.style.display = 'flex';
}
function closeImageModal() {
    var modal = document.getElementById('imgModal');
    var img = document.getElementById('imgModalSrc');
    modal.style.display = 'none';
    img.src = '';
}
// Tutup modal jika klik di luar gambar
window.onclick = function(event) {
    var modal = document.getElementById('imgModal');
    if (event.target === modal) {
        closeImageModal();
    }
}
</script>
<script>

    // =========================
    // GEOCODER
    // =========================
    L.Control.geocoder({ defaultMarkGeocode: false })
        .on('markgeocode', function(e) { map.setView(e.geocode.center, 16); })
        .addTo(map);

    // =========================
    // LOCATE BUTTON
    // =========================
    let gpsMarker = null;
    let gpsIcon = L.icon({
        iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
        iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -28]
    });
    L.control.locate = function() {
        let control = L.control({position: 'topleft'});
        control.onAdd = function(map) {
            let button = L.DomUtil.create('button', 'btn btn-light btn-sm');
            button.innerHTML = '<span class="loc-icon"><i class="bi bi-geo-alt-fill"></i></span>';
            L.DomEvent.disableClickPropagation(button);
            L.DomEvent.disableScrollPropagation(button);

            // Tambah style overlay loading
            let style = document.createElement('style');
            style.innerHTML = `
                .overlay-loc {
                    position: fixed;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background: rgba(255,255,255,0.7);
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .spinner-loc {
                    width: 3em;
                    height: 3em;
                    border: 6px solid #1976d2;
                    border-top: 6px solid #fff;
                    border-radius: 50%;
                    animation: spin-loc 0.7s linear infinite;
                }
                @keyframes spin-loc { 100% { transform: rotate(360deg); } }
            `;
            document.head.appendChild(style);

            // Fungsi untuk show/hide overlay
            function showLocOverlay() {
                let overlay = document.createElement('div');
                overlay.className = 'overlay-loc';
                overlay.id = 'overlay-loc';
                overlay.innerHTML = '<div class="spinner-loc"></div>';
                document.body.appendChild(overlay);
            }
            function hideLocOverlay() {
                let overlay = document.getElementById('overlay-loc');
                if (overlay) overlay.remove();
            }

            button.onclick = function(e){
                e.preventDefault();
                if (navigator.geolocation) {
                    showLocOverlay();
                    navigator.geolocation.getCurrentPosition(function(pos){
                        let latlng = [pos.coords.latitude, pos.coords.longitude];
                        map.setView(latlng, 16);
                        if (gpsMarker) map.removeLayer(gpsMarker);
                        gpsMarker = L.marker(latlng, {icon: gpsIcon}).addTo(map).bindPopup("Lokasi Saya");
                        hideLocOverlay();
                    }, function(){
                        hideLocOverlay();
                    });
                }
            };
            return button;
        };
        return control;
    };
    L.control.locate().addTo(map);
 
let dataClients = @json($clients);
let dataOptical = @json($optical_distribution);

// =========================
// GARIS CLIENT → OPTICAL
// =========================
dataClients.forEach(function(client) {
    if (!client.kordinat || !client.id_optical_distribution) return;

    try {
        let clientCoords = client.kordinat.split(",");
        if (clientCoords.length !== 2) return;
        let clientLatLng = [parseFloat(clientCoords[0]), parseFloat(clientCoords[1])];

        let optical = dataOptical.find(o => o.id_optical_distribution == client.id_optical_distribution);
        if (!optical || !optical.kordinat) return;

        let opticalCoords = optical.kordinat.split(",");
        if (opticalCoords.length !== 2) return;
        let opticalLatLng = [parseFloat(opticalCoords[0]), parseFloat(opticalCoords[1])];

        // Outline putih
        L.polyline([clientLatLng, opticalLatLng], {
            color: 'white',
            weight: 7,
            opacity: 1
        }).addTo(map);
        // Garis utama hijau
        L.polyline([clientLatLng, opticalLatLng], {
            color: "green",
            weight: 3,
            opacity: 1,
            dashArray: "6, 8",
            lineCap: "round",
            className: "animated-path"
        }).addTo(map);

    } catch (e) {
        console.error("Gagal gambar garis client-optical", e);
    }
});

   // =========================
// MARKER / POLYLINE LINE
// =========================
let dataLine = @json($lines);
dataLine.forEach(function(item) {
    if (!item.kordinat) return;

    try {
        if (item.kordinat.startsWith("[")) {
            let parsed = JSON.parse(item.kordinat);

            // ambil kategori dari relasi optical_distribution
            let kategori = (item.optical_distribution?.kategori?.nama ?? "").toLowerCase();

            // tentukan warna sesuai kategori
            let warna = "red"; // default
            if (kategori === "odp") warna = "orange";
            if (kategori === "odc") warna = "blue";

            // Outline putih
            L.polyline(parsed, { 
                color: 'white',
                weight: 7,
                opacity: 1
            }).addTo(map);

            // Garis utama (warna sesuai kategori, animasi putus-putus)
            let line = L.polyline(parsed, { 
                color: warna,
                weight: 4,
                opacity: 1,
                dashArray: "10, 10",
                lineCap: "round",
                lineJoin: "round",
                className: 'animated-path'
            }).addTo(map);

           // Hitung panjang manual
let latlngs = line.getLatLngs();
let length = 0;
for (let i = 0; i < latlngs.length - 1; i++) {
    length += latlngs[i].distanceTo(latlngs[i + 1]);
}
let km = (length / 1000).toFixed(2);

// Popup untuk polyline
line.bindPopup(
    "<b>ID Line:</b> " + item.id_line + "<br/>" +
    "<b>ID Optical Distribution:</b> " + (item.id_optical_distribution ?? '-') + "<br/>" +
    "<b>Keterangan:</b> " + (item.keterangan ?? '-') + "<br/>" +
    "<b>Kordinat:</b> " + (item.kordinat ?? '-') + "<br/>" +
    "<b>Full Texts:</b> " + (item.full_texts ?? '-') + "<br/>" +
    "<b>Created At:</b> " + (item.created_at ?? '-') + "<br/>" +
    "<b>Updated At:</b> " + (item.updated_at ?? '-') + "<br/>" +
    "<b>Panjang:</b> " + km + " km"
);

// Jika data berupa titik
let coords = item.kordinat.split(",");
if (coords.length === 2) {
    let latlng = [parseFloat(coords[0]), parseFloat(coords[1])];
    L.marker(latlng).addTo(map).bindPopup(
        "<b>ID Line:</b> " + item.id_line + "<br/>" +
        "<b>ID Optical Distribution:</b> " + (item.id_optical_distribution ?? '-') + "<br/>" +
        "<b>Keterangan:</b> " + (item.keterangan ?? '-') + "<br/>" +
        "<b>Kordinat:</b> " + (item.kordinat ?? '-') + "<br/>" +
        "<b>Full Texts:</b> " + (item.full_texts ?? '-') + "<br/>" +
        "<b>Created At:</b> " + (item.created_at ?? '-') + "<br/>" +
        "<b>Updated At:</b> " + (item.updated_at ?? '-')
    );
}

        }
    } catch (e) {
        console.error("Invalid koordinat line", e);
    }
}); 
     


</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
     

    // ==== LAYER DRAWN ITEMS ====
    let drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    

    // ==== MARKER ODP/ODC/SERVER ====
    dataOptical.forEach(function(item) {
        if (!item.kordinat) return;
        let coords = item.kordinat.split(",");
        if (coords.length !== 2) return;

        let latlng = [parseFloat(coords[0]), parseFloat(coords[1])];

        let iconUrl = "/images/optical.png";
        if (item.kategori?.nama) {
            let n = item.kategori.nama.toLowerCase();
            if (n.includes("odp")) iconUrl = "/images/odp.png";
            if (n.includes("odc")) iconUrl = "/images/odc.png";
            if (n.includes("server")) iconUrl = "/images/server.png";
        }

        let opticalIcon = L.icon({ iconUrl, iconSize:[32,32], iconAnchor:[16,32], popupAnchor:[0,-28] });
        L.marker(latlng, { icon: opticalIcon })
    .addTo(map)
    .bindPopup(
        "<b>ID Optical Distribution:</b> " + (item.id_optical_distribution ?? '-') + "<br>" +
        "<b>Kode:</b> " + (item.kode ?? '-') + "<br>" +
        "<b>ID Kategori:</b> " + (item.id_kategori ?? '-') + "<br>" +
        "<b>Inputan:</b> " + (item.inputan ?? '-') + "<br>" +
        "<b>Kordinat:</b> " + (item.kordinat ?? '-') + "<br>" +
        "<b>Estimasi Redaman Input:</b> " + (item.estimasi_redaman_input ?? '-') + "<br>" +
        "<b>Estimasi Redaman Output:</b> " + (item.estimasi_redaman_output ?? '-') + "<br>" +
        "<b>Foto:</b> " + (item.foto 
            ? `<a href='#' onclick=\"showImageModal('/storage/optical/${item.foto}')\"><img src='/storage/optical/${item.foto}' alt='foto' style='max-width:80px;max-height:80px;border-radius:6px;cursor:pointer;'/></a>` 
            : '-') + "<br>" +
        "<b>Keterangan:</b> " + (item.keterangan ?? '-') + "<br>" +
        "<b>Dibuat:</b> " + (item.created_at ?? '-') + "<br>" +
        "<b>Update Terakhir:</b> " + (item.updated_at ?? '-')
    );
});

    // ==== MARKER CLIENT ====
    dataClients.forEach(function(client) {
        if (!client.kordinat) return;
        let coords = client.kordinat.split(",");
        if (coords.length !== 2) return;

        let latlng = [parseFloat(coords[0]), parseFloat(coords[1])];
        let clientIcon = L.icon({ iconUrl:"/images/homehijauputih.png", iconSize:[28,28], iconAnchor:[14,28], popupAnchor:[0,-25] });
        L.marker(latlng, {icon: clientIcon})
            .addTo(map)
            .bindPopup("<b>Nama Client:</b> " + (client.nama ?? '-') + "<br>Alamat: " + (client.alamat ?? '-'));
    });

    // ==== GARIS CLIENT → OPTICAL ====
    dataClients.forEach(function(client) {
        if (!client.kordinat || !client.id_optical_distribution) return;

        let clientCoords = client.kordinat.split(",");
        if (clientCoords.length !== 2) return;
        let clientLatLng = [parseFloat(clientCoords[0]), parseFloat(clientCoords[1])];

        let optical = dataOptical.find(o => o.id_optical_distribution == client.id_optical_distribution);
        if (!optical?.kordinat) return;

        let opticalCoords = optical.kordinat.split(",");
        if (opticalCoords.length !== 2) return;
        let opticalLatLng = [parseFloat(opticalCoords[0]), parseFloat(opticalCoords[1])];

        L.polyline([clientLatLng, opticalLatLng], {
            color: "green",
            weight: 3,
            dashArray: "6,8",
            className: "animated-path"
        }).addTo(map);
    });
});

</script>

@endpush
