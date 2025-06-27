@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

<div id="mapid" style="height: 400px;"></div>

@push('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        $(document).ready(function() {
            var map = L.map('mapid').setView([-2.548926, 118.0148634], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var locations = @json($kegiatan->lokasi);

            if (locations.length > 0) {
                var markers = [];
                locations.forEach(function(location) {
                    if (location.lat && location.long) {
                        var marker = L.circleMarker([location.lat, location.long], {
                            color: 'red',
                            fillColor: '#f03',
                            fillOpacity: 0.5,
                            radius: 8
                        }).addTo(map);
                        marker.bindPopup(
                            `<b>Lokasi:</b> ${location.lokasi}<br>` +
                            `<b>Desa:</b> ${location.desa.nama}<br>` +
                            `<b>Kecamatan:</b> ${location.desa.kecamatan.nama}`
                        );
                        markers.push(marker);
                    }
                });

                if (markers.length > 0) {
                    var group = new L.featureGroup(markers);
                    map.fitBounds(group.getBounds().pad(0.5));
                }
            }
        });
    </script>
@endpush
