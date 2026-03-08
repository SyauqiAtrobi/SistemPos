<div class="location-picker">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* Styling khusus Location Picker */
        .lp-search-wrapper {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 123, 255, 0.2);
            border-radius: 50px;
            padding: 4px;
            box-shadow: 0 4px 15px rgba(0, 86, 179, 0.05);
            transition: all 0.3s ease;
        }
        .lp-search-wrapper:focus-within {
            border-color: rgba(0, 123, 255, 0.5);
            box-shadow: 0 4px 20px rgba(0, 123, 255, 0.15);
        }
        
        .lp-input {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            font-size: 0.95rem;
            color: #334155;
        }

        .lp-btn-search {
            border-radius: 50px !important;
            padding: 8px 20px;
            font-weight: 600;
        }

        #lp-search-results {
            max-height: 200px;
            overflow-y: auto;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 86, 179, 0.15);
            border: 1px solid rgba(0, 123, 255, 0.1);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            scrollbar-width: thin;
        }

        #lp-search-results .list-group-item {
            background: transparent;
            border-left: none;
            border-right: none;
            border-top: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-size: 0.85rem;
            color: #475569;
            transition: background 0.2s;
        }
        #lp-search-results .list-group-item:last-child {
            border-bottom: none;
        }
        #lp-search-results .list-group-item:hover {
            background: rgba(0, 123, 255, 0.05);
            color: #0056b3;
        }

        #lp-map {
            width: 100%;
            height: 350px;
            border-radius: 16px;
            border: 1px solid rgba(0, 123, 255, 0.15);
            box-shadow: 0 8px 25px rgba(0, 86, 179, 0.08);
            z-index: 1; /* Pastikan map berada di bawah dropdown hasil search */
        }
    </style>

    <div class="mb-3 position-relative">
        <div class="d-flex align-items-center lp-search-wrapper">
            <span class="ps-3 pe-2 text-primary opacity-75">
                <i class="fa-solid fa-location-dot"></i>
            </span>
            <input type="search" id="lp-search" class="form-control lp-input" placeholder="Cari alamat, kota, atau patokan lokasi...">
            <button class="btn btn-custom-primary lp-btn-search shadow-sm" type="button" id="lp-search-btn">Cari</button>
        </div>
        
        <div id="lp-search-results" class="list-group position-absolute w-100 mt-2 z-3" style="display:none; top: 100%; left: 0;"></div>
    </div>

    <div id="lp-map"></div>

    <div class="mt-3 d-flex flex-wrap gap-2 justify-content-center">
        <button type="button" id="lp-use-location" class="btn btn-light rounded-pill border shadow-sm fw-semibold text-primary px-3 py-2 transition-smooth">
            <i class="fa-solid fa-location-crosshairs me-1"></i> Gunakan Lokasi Saya Saat Ini
        </button>
        <button type="button" id="lp-clear-location" class="btn btn-light rounded-pill border shadow-sm fw-semibold text-danger px-3 py-2 transition-smooth">
            <i class="fa-solid fa-eraser me-1"></i> Hapus Titik
        </button>
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
                    else {
                        // Tambahkan animasi mantul jika marker baru dibuat
                        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    }
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
                    searchBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>'; // Loading state
                    try {
                        const url = `https://nominatim.openstreetmap.org/search?format=json&limit=5&q=${encodeURIComponent(q)}`;
                        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) return [];
                        return res.json();
                    } catch(e) {
                        return [];
                    } finally {
                        searchBtn.innerHTML = 'Cari';
                    }
                }

                let searchTimeout = null;
                function showResults(list) {
                    resultsEl.innerHTML = '';
                    if (!list || !list.length) { 
                        resultsEl.style.display = 'none'; 
                        return; 
                    }
                    list.forEach(item => {
                        const a = document.createElement('button');
                        a.type = 'button';
                        a.className = 'list-group-item list-group-item-action d-flex align-items-start py-2 px-3';
                        a.innerHTML = `<i class="fa-solid fa-map-pin mt-1 me-2 text-primary opacity-50"></i> <span class="fw-medium">${item.display_name}</span>`;
                        a.addEventListener('click', function() {
                            setMarker(item.lat, item.lon);
                            map.setView([item.lat, item.lon], 16);
                            resultsEl.style.display = 'none';
                            searchInput.value = item.display_name; // Set text input to chosen value
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
                        if(q.length < 3) {
                            resultsEl.style.display = 'none';
                            return;
                        }
                        const results = await nominatimSearch(q);
                        showResults(results);
                    }, 600);
                });

                // Sembunyikan dropdown kalau klik di luar
                document.addEventListener('click', function(e) {
                    if(!resultsEl.contains(e.target) && e.target !== searchInput) {
                        resultsEl.style.display = 'none';
                    }
                });

                map.on('click', function(e) {
                    setMarker(e.latlng.lat, e.latlng.lng);
                });

                document.getElementById('lp-use-location').addEventListener('click', function() {
                    const btn = this;
                    const originalText = btn.innerHTML;
                    
                    if (!navigator.geolocation) { 
                        alert('Geolocation tidak didukung oleh browser Anda.'); 
                        return; 
                    }
                    
                    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin me-1"></i> Mendapatkan lokasi...';
                    btn.disabled = true;

                    navigator.geolocation.getCurrentPosition(function(pos) {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        map.setView([lat, lng], 16);
                        setMarker(lat, lng);
                        
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, function(err) { 
                        alert('Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan pada browser Anda.'); 
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
                });

                document.getElementById('lp-clear-location').addEventListener('click', function() {
                    if (marker) { map.removeLayer(marker); marker = null; }
                    latInput.value = '';
                    lngInput.value = '';
                    searchInput.value = '';
                });
            }

            // Jika digunakan di dalam Bootstrap modal, inisialisasi ketika modal muncul
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.addEventListener('shown.bs.modal', function (e) {
                        if (this.querySelector('#lp-map')) {
                            // Beri sedikit delay agar Leaflet bisa menghitung ulang ukuran container
                            setTimeout(() => {
                                initLocationPicker();
                                // Paksa map update size
                                const mapEl = document.getElementById('lp-map');
                                if(mapEl._leaflet_id) {
                                     window.dispatchEvent(new Event('resize'));
                                }
                            }, 150);
                        }
                    });
                });
            });
        })();
    </script>
</div>