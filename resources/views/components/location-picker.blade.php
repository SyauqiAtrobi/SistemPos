<div class="location-picker">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="mb-2">
        <div class="input-group">
            <input type="search" id="lp-search" class="form-control" placeholder="Cari lokasi (alamat, kota, titik acuan)...">
            <button class="btn btn-outline-secondary" type="button" id="lp-search-btn">Cari</button>
        </div>
        <div id="lp-search-results" class="list-group mt-2" style="max-height:140px;overflow:auto;display:none;"></div>
    </div>

    <div id="lp-map" style="width:100%;height:300px;border-radius:8px;border:1px solid rgba(0,0,0,0.08);"></div>

    <div class="mt-2 d-flex gap-2">
        <button type="button" id="lp-use-location" class="btn btn-sm btn-outline-secondary">Gunakan Lokasi Saya</button>
        <button type="button" id="lp-clear-location" class="btn btn-sm btn-outline-danger">Hapus Lokasi</button>
    </div>

    <input type="hidden" id="lp-lat" name="lat">
    <input type="hidden" id="lp-lng" name="lng">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        (function() {
            // Defer initialization until modal is shown to ensure container has size
            function initLocationPicker() {
                if (window.lpInitialized) return;
                window.lpInitialized = true;

                const mapEl = document.getElementById('lp-map');
                const latInput = document.getElementById('lp-lat');
                const lngInput = document.getElementById('lp-lng');

                const defaultLat = parseFloat('{{ $lat ?? '0' }}') || -6.200000;
                const defaultLng = parseFloat('{{ $lng ?? '0' }}') || 106.816666;

                const map = L.map(mapEl).setView([defaultLat, defaultLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);


                let marker = null;

                function setMarker(lat, lng) {
                    if (marker) marker.setLatLng([lat, lng]);
                    else marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    latInput.value = lat;
                    lngInput.value = lng;
                    marker.on('dragend', function(e) {
                        const p = e.target.getLatLng();
                        latInput.value = p.lat;
                        lngInput.value = p.lng;
                    });
                }

                // Simple Nominatim search
                const searchInput = document.getElementById('lp-search');
                const searchBtn = document.getElementById('lp-search-btn');
                const resultsEl = document.getElementById('lp-search-results');

                async function nominatimSearch(q) {
                    if (!q) return [];
                    const url = `https://nominatim.openstreetmap.org/search?format=json&limit=8&q=${encodeURIComponent(q)}`;
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return [];
                    return res.json();
                }

                let searchTimeout = null;
                function showResults(list) {
                    resultsEl.innerHTML = '';
                    if (!list || !list.length) { resultsEl.style.display = 'none'; return; }
                    list.forEach(item => {
                        const a = document.createElement('button');
                        a.type = 'button';
                        a.className = 'list-group-item list-group-item-action';
                        a.textContent = item.display_name;
                        a.addEventListener('click', function() {
                            setMarker(item.lat, item.lon);
                            map.setView([item.lat, item.lon], 16);
                            resultsEl.style.display = 'none';
                        });
                        resultsEl.appendChild(a);
                    });
                    resultsEl.style.display = 'block';
                }

                searchBtn.addEventListener('click', async function() {
                    const q = searchInput.value.trim();
                    const results = await nominatimSearch(q);
                    showResults(results);
                });

                searchInput.addEventListener('input', function() {
                    if (searchTimeout) clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(async () => {
                        const q = searchInput.value.trim();
                        const results = await nominatimSearch(q);
                        showResults(results);
                    }, 500);
                });

                map.on('click', function(e) {
                    setMarker(e.latlng.lat, e.latlng.lng);
                });

                document.getElementById('lp-use-location').addEventListener('click', function() {
                    if (!navigator.geolocation) { alert('Geolocation tidak didukung oleh browser Anda.'); return; }
                    navigator.geolocation.getCurrentPosition(function(pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        map.setView([lat, lng], 16);
                        setMarker(lat, lng);
                    }, function(err) { alert('Gagal mendapatkan lokasi: ' + err.message); });
                });

                document.getElementById('lp-clear-location').addEventListener('click', function() {
                    if (marker) { map.removeLayer(marker); marker = null; }
                    latInput.value = '';
                    lngInput.value = '';
                });
            }

            // If used inside a Bootstrap modal, initialize when modal shown
            document.addEventListener('DOMContentLoaded', function() {
                // Listen for any modal show events and init once
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.addEventListener('shown.bs.modal', function (e) {
                        // init when address modal contains our map
                        if (this.querySelector('#lp-map')) {
                            setTimeout(initLocationPicker, 120);
                        }
                    });
                });
            });
        })();
    </script>
</div>
