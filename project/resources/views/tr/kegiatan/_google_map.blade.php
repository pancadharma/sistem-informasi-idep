<div id="googleMap" style="height: 400px; width: 100%;"></div>

@push('js')
<script>
    function initMap() {
        const map = new google.maps.Map(document.getElementById("googleMap"), {
            zoom: 3,
            mapId: "7e7fb1bfd929ec61",
            center: { lat: -2.548926, lng: 118.0148634 } // Center of Indonesia
        });

        const locations = @json($kegiatan->lokasi);
        const bounds = new google.maps.LatLngBounds();
        const markers = [];

        if (locations.length > 0) {
            locations.forEach(function(location) {
                if (location.lat && location.long) {
                    const latLng = new google.maps.LatLng(parseFloat(location.lat), parseFloat(location.long));

                    const marker = new google.maps.Marker({
                        position: latLng,
                        // map: map,
                        title: location.lokasi,
                         icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 7,
                            fillColor: "red",
                            fillOpacity: 1,
                            strokeWeight: 1,
                            strokeColor: "white"
                        }
                    });


                    const infoWindowContent = `
                        <div class="info-window-content">
                            <strong>Lokasi:</strong> ${location.lokasi || '-'}<br>
                            <strong>Desa:</strong> ${location.desa ? location.desa.nama : '-'}<br>
                            <strong>Kecamatan:</strong> ${location.desa && location.desa.kecamatan ? location.desa.kecamatan.nama : '-'}
                        </div>`;

                    const infoWindow = new google.maps.InfoWindow({
                        content: infoWindowContent
                    });

                    marker.addListener('click', () => {
                        infoWindow.open(map, marker);
                    });

                    markers.push(marker);
                    bounds.extend(marker.getPosition());
                }
            });

            new markerClusterer.MarkerClusterer({ map, markers });

            map.fitBounds(bounds);
        }
    }
</script>

<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqxb0Be7JWTChc3E_A8rTlSmiVDLPUSfQ&callback=initMap">
</script>

{{-- <script async defer>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
({key: "AIzaSyCqxb0Be7JWTChc3E_A8rTlSmiVDLPUSfQ", v: "weekly"});</script> --}}


@endpush
