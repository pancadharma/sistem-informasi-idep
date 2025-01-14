var map, featureList, bataskecSearch = [], batasdesaSearch = [], titikdesaSearch = [], theaterSearch = [], museumSearch = [];

$(window).resize(function() {
    sizeLayerControl();
});

$(document).on("click", ".feature-row", function(e) {
    $(document).off("mouseout", ".feature-row", clearHighlight);
    sidebarClick(parseInt($(this).attr("id"), 10));
});

if (!("ontouchstart"in window)) {
    $(document).on("mouseover", ".feature-row", function(e) {
        highlight.clearLayers().addLayer(L.circleMarker([$(this).attr("lat"), $(this).attr("lng")], highlightStyle));
    });
}

$(document).on("mouseout", ".feature-row", clearHighlight);

$("#about-btn").click(function() {
    $("#aboutModal").modal("show");
    $(".navbar-collapse.in").collapse("hide");
    return false;
});

$('#myModal').modal('show');

$("#full-extent-btn").click(function() {
    map.fitBounds(bataskec.getBounds());
    $(".navbar-collapse.in").collapse("hide");
    return false;
});

$("#legend-btn").click(function() {
    $("#legendModal").modal("show");
    $(".navbar-collapse.in").collapse("hide");
    return false;
});

$("#login-btn").click(function() {
    $("#loginModal").modal("show");
    $(".navbar-collapse.in").collapse("hide");
    return false;
});

$("#list-btn").click(function() {
    animateSidebar();
    return false;
});

$("#nav-btn").click(function() {
    $(".navbar-collapse").collapse("toggle");
    return false;
});

$("#sidebar-toggle-btn").click(function() {
    animateSidebar();
    return false;
});

$("#sidebar-hide-btn").click(function() {
    animateSidebar();
    return false;
});

function animateSidebar() {
    $("#sidebar").animate({
        width: "toggle"
    }, 350, function() {
        map.invalidateSize();
    });
}

function sizeLayerControl() {
    $(".leaflet-control-layers").css("max-height", $("#map").height() - 50);
}

function clearHighlight() {
    highlight.clearLayers();
}

function sidebarClick(id) {
    var layer = markerClusters.getLayer(id);
    map.setView([layer.getLatLng().lat, layer.getLatLng().lng], 17);
    layer.fire("click");
    /* Hide sidebar and go to the map on small screens */
    if (document.body.clientWidth <= 767) {
        $("#sidebar").hide();
        map.invalidateSize();
    }
}

function syncSidebar() {
    /* Empty sidebar features */
    $("#feature-list tbody").empty();
    /* Loop through theaters layer and add only features which are in the map bounds */
    theaters.eachLayer(function(layer) {
        if (map.hasLayer(theaterLayer)) {
            if (map.getBounds().contains(layer.getLatLng())) {
                $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="/sipetarung/assets/img/pkl.png"></td><td class="feature-name">' + layer.feature.properties.LABEL + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
            }
        }
    });
    titikdesa.eachLayer(function(layer) {
        if (map.hasLayer(titikdesaLayer)) {
            if (map.getBounds().contains(layer.getLatLng())) {
                $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="/sipetarung/assets/img/favicon-761.png"></td><td class="feature-name">' + layer.feature.properties.DESA + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
            }
        }
    });
    /* Loop through museums layer and add only features which are in the map bounds */
    museums.eachLayer(function(layer) {
        if (map.hasLayer(museumLayer)) {
            if (map.getBounds().contains(layer.getLatLng())) {
                $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="/sipetarung/assets/img/favicon-761.png"></td><td class="feature-name">' + layer.feature.properties.DESA + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
            }
        }
    });
    /* Update list.js featureList */
    featureList = new List("features",{
        valueNames: ["feature-name"]
    });
    featureList.sort("feature-name", {
        order: "asc"
    });
}

/* Basemap Layers */
var GoogleSatelit = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: 'Google Satellite'
});
var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
});
var GoogleStreet = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: 'Google Streets'
});
var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
});
var cartoLight = L.tileLayer("https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://cartodb.com/attributions">CartoDB</a>'
});
var OpenTopoMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
    maxZoom: 17,
    attribution: 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
});
var usgsImagery = L.layerGroup([L.tileLayer("http://basemap.nationalmap.gov/arcgis/rest/services/USGSImageryOnly/MapServer/tile/{z}/{y}/{x}", {
    maxZoom: 15,
}), L.tileLayer.wms("http://raster.nationalmap.gov/arcgis/services/Orthoimagery/USGS_EROS_Ortho_SCALE/ImageServer/WMSServer?", {
    minZoom: 16,
    maxZoom: 19,
    layers: "0",
    format: 'image/jpeg',
    transparent: true,
    attribution: "Aerial Imagery courtesy USGS"
})]);

/* Overlay Layers */
var highlight = L.geoJson(null);
var highlightStyle = {
    stroke: false,
    fillColor: "#00FFFF",
    fillOpacity: 0.7,
    radius: 10
};

var bataskec = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "white",
            //Warna tengah polygon
            fillOpacity: 0,
            color: "black",
            weight: 1,
            opacity: 0.6,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Kecamatan</th><td>" + feature.properties.KECAMATAN + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.KECAMATAN);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                bataskec.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/batas_kecamatan1.geojson", function(data) {
    bataskec.addData(data);
    map.addLayer(bataskec);
});

//Create a color dictionary based off of subway route_id

var transportcolors1 = {
    "Alur-Pelayaran Masuk Pelabuhan": "#00BEFA",
    "Alur-Pelayaran Sungai dan Alur-Pelayaran Danau": "#FF8C00",
    "Alur-Pelayaran Umum dan Perlintasan": "#005CE6",
    "Jalan Arteri Primer": "#FF5100",
    "Jalan Kolektor Primer": "#FF8C00",
    "Jalan Tol": "#F50000",
    "Jalur Pendaratan dan Penerbangan di Laut": "#9BFFDE",
    "Jaringan Jalur Kereta Api": "#000000",
    "Lintas Penyeberangan Antarkabupaten/Kota dalam Provinsi": "#FFC800",
    "Lintas Penyeberangan Antarprovinsi": "#FF7800"
};

var jalan_arteri = L.geoJson(null, {
    style: function(feature) {
        return {
            color: transportcolors1[feature.properties.NAMOBJ],
            weight: 3,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Rencana Jaringan Transportasi</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                jalan_arteri.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/transport_ln.geojson", function(data) {
    jalan_arteri.addData(data);
});

var jalan_kolektor = L.geoJson(null, {
    style: function(feature) {
        return {
            color: "#FF8C00",
            weight: 3,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Status Jalan</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Nama Ruas Jalan</th><td>" + feature.properties.Nama_Jln + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.Nama_Jln);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                jalan_kolektor.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/jalan_kolektor.geojson", function(data) {
    jalan_kolektor.addData(data);
});

var jalan_lokal = L.geoJson(null, {
    style: function(feature) {
        return {
            color: "#FFFF00",
            weight: 1.2,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Status Jalan</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Nama Ruas Jalan</th><td>" + feature.properties.Nama_Jln + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.Nama_Jln);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                jalan_lokal.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/jalan_lokal.geojson", function(data) {
    jalan_lokal.addData(data);
});

var jalan_tol = L.geoJson(null, {
    style: function(feature) {
        return {
            color: "#F50000",
            weight: 5,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Status Jalan</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Nama Ruas Jalan</th><td>" + feature.properties.Nama_Jln + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.Nama_Jln);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                jalan_tol.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/jalan_tol.geojson", function(data) {
    jalan_tol.addData(data);
});

var jalan_desa = L.geoJson(null, {
    style: function(feature) {
        return {
            color: "#4E4E4E",
            weight: 0.3,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Status Jalan</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Nama Ruas Jalan</th><td>" + feature.properties.Nama_Jln + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.NAMOBJ);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                jalan_desa.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/jalan_desa.geojson", function(data) {
    jalan_desa.addData(data);
});

var jar_ka = L.geoJson(null, {
    style: function(feature) {
        return {
            color: "#000000",
            weight: 3,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Status Jaringan</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Nama Ruas</th><td>" + feature.properties.Nama_Jln + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.NAMOBJ);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                jar_ka.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/jar_ka.geojson", function(data) {
    jar_ka.addData(data);
});

var energicolors1 = {
    "Jaringan Minyak dan Gas Bumi": "#FF7F7F",
    "Jaringan Pipa/Kabel Bawah Laut Penyaluran Tenaga Listrik": "#824600",
    "Jaringan Transmisi Tenaga Listrik Antarsistem": "#FF9600"
};

var jaringangas = L.geoJson(null, {
    style: function(feature) {
        return {
            color: energicolors1[feature.properties.NAMOBJ],
            weight: 3,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Rencana Jaringan Energi</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                jaringangas.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/energi_ln.geojson", function(data) {
    jaringangas.addData(data);
});

// var colorlistrik = {"Saluran Udara Tegangan Ekstra Tinggi (SUTET)":"#c849d0", "Saluran Udara Tegangan Tinggi (SUTT)":"#ecc653", "Saluran Udara Tegangan Menengah (SUTM)":"#40bf95"};

// var jaringanlistrik = L.geoJson(null, {
//   style: function (feature) {
//       return {
//         color: colorlistrik[feature.properties.NAMOBJ],
//         weight: 3,
//         opacity: 1
//       };
//   },
//   onEachFeature: function (feature, layer) {
//     if (feature.properties) {
//       var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Fungsi Jaringan</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<table>";
//       layer.on({
//         click: function (e) {
//           $("#feature-title").html(feature.properties.NAMOBJ);
//           $("#feature-info").html(content);
//           $("#featureModal").modal("show");

//         }
//       });
//     }
//     layer.on({
//       mouseover: function (e) {
//         var layer = e.target;
//         layer.setStyle({
//           weight: 3,
//           color: "#00FFFF",
//           opacity: 1
//         });
//         if (!L.Browser.ie && !L.Browser.opera) {
//           layer.bringToFront();
//         }
//       },
//       mouseout: function (e) {
//         jaringanlistrik.resetStyle(e.target);
//       }
//     });
//   }
// });
// $.getJSON("/sipetarung/data/jaringanlistrik.geojson", function (data) {
//   jaringanlistrik.addData(data);
// });

var jaringantelkom = L.geoJson(null, {
    style: function(feature) {
        return {
            color: "#e3ec53",
            type: "dash",
            weight: 3,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Rencana Jaringan Telekomunikasi</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                jaringantelkom.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/telkom_ln.geojson", function(data) {
    jaringantelkom.addData(data);
});

var sdacolors1 = {
    "Jaringan Pengendalian Banjir": "#004DA8",
    "Sistem Jaringan Irigasi": "#005CE6"
};

var spam = L.geoJson(null, {
    style: function(feature) {
        return {
            color: sdacolors1[feature.properties.NAMOBJ],
            weight: 3,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Rencana Jaringan SDA</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                spam.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/sda_ln.geojson", function(data) {
    spam.addData(data);
});

var jalurevakuasi = L.geoJson(null, {
    style: function(feature) {
        return {
            color: "#e744d8",
            type: "dash",
            weight: 3,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Rencana Jaringan Lainnya</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                jalurevakuasi.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/lainnya_ln.geojson", function(data) {
    jalurevakuasi.addData(data);
});

var sungai = L.geoJson(null, {
    style: function(feature) {
        return {
            color: "#067ae5",
            weight: 2,
            opacity: 1
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Sungai</th><td>" + feature.properties.KETERANGAN + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.KETERANGAN);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");

                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                sungai.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/sungai.geojson", function(data) {
    sungai.addData(data);
});

/* Single marker cluster layer to hold all clusters */
var markerClusters = new L.MarkerClusterGroup({
    spiderfyOnMaxZoom: true,
    showCoverageOnHover: false,
    zoomToBoundsOnClick: true,
    disableClusteringAtZoom: 1,
});

/* Empty layer placeholder to add to layer control for listening when to add/remove theaters to markerClusters layer */
var iconpelayanan = {
    "PKW": "/sipetarung/assets/img/pkw.png",
    "PKL": "/sipetarung/assets/img/pkl.png",
    "PPK": "/sipetarung/assets/img/ppk.png",
    "PPL": "/sipetarung/assets/img/ppk.png"
};

var theaterLayer = L.geoJson(null);
var theaters = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: iconpelayanan[feature.properties.JENIS],
                iconSize: [24, 28],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.JENIS,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Jenis Pusat Pelayanan</th><td>" + feature.properties.JENIS + "</td></tr>" + "<tr><th>Nama Pusat Pelayanan</th><td>" + feature.properties.KECAMATAN + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.KECAMATAN);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
            $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="/sipetarung/assets/img/pkl.png"></td><td class="feature-name">' + layer.feature.properties.LABEL + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
            theaterSearch.push({
                name: layer.feature.properties.FGS_RENC,
                address: layer.feature.properties.LABEL,
                source: "Theaters",
                id: L.stamp(layer),
                lat: layer.feature.geometry.coordinates[1],
                lng: layer.feature.geometry.coordinates[0]
            });
        }
    }
});
$.getJSON("/sipetarung/data/puspelayanan.geojson", function(data) {
    theaters.addData(data);
    // map.addLayer(theaterLayer);
});

var titikdesaLayer = L.geoJson(null);
var titikdesa = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/favicon1.ico",
                iconSize: [24, 28],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.DESA,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Desa</th><td>" + feature.properties.DESA + "</td></tr>" + "<tr><th>Kecamatan</th><td>" + feature.properties.KECAMATAN + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.DESA);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
            // $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="/sipetarung/assets/img/favicon-761.png"></td><td class="feature-name">' + layer.feature.properties.DESA + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
            // titikdesaSearch.push({
            //   name: layer.feature.properties.DESA,
            //   address: layer.feature.properties.KECAMATAN,
            //   source: "Titik Desa",
            //   id: L.stamp(layer),
            //   lat: layer.feature.geometry.coordinates[1],
            //   lng: layer.feature.geometry.coordinates[0]
            // });
        }
    }
});
$.getJSON("/sipetarung/data/titik_desa.geojson", function(data) {
    titikdesa.addData(data);
    // map.addLayer(theaterLayer);
});

/* Empty layer placeholder to add to layer control for listening when to add/remove theaters to markerClusters layer */
var iconterminal = {
    "Terminal Penumpang Tipe A": "/sipetarung/assets/img/terminal_a.png",
    "Terminal Penumpang Tipe B": "/sipetarung/assets/img/terminal_b.png",
    "Bandar Udara Khusus": "/sipetarung/assets/img/bandara_khusus.png",
    "Bandar Udara Pengumpul": "/sipetarung/assets/img/bandara_pengumpul.png",
    "Jembatan Timbang": "/sipetarung/assets/img/jembatan_timbang.png",
    "Pangkalan Pendaratan Ikan": "/sipetarung/assets/img/ppi.png",
    "Pelabuhan Pengumpan": "/sipetarung/assets/img/pelabuhan_pengumpan.png",
    "Pelabuhan Pengumpul": "/sipetarung/assets/img/pelabuhan_pengumpul.png",
    "Pelabuhan Penyeberangan": "/sipetarung/assets/img/pelabuhan_penyeberangan.png",
    "Pelabuhan Perikanan Nusantara": "/sipetarung/assets/img/pelabuhan_perikanan.png",
    "Pelabuhan Sungai dan Danau": "/sipetarung/assets/img/pelabuhan_sungai.png",
    "Pelabuhan Utama": "/sipetarung/assets/img/pelabuhan_utama.png",
    "Stasiun Kereta Api": "/sipetarung/assets/img/stasiun_ka.png",
    "Terminal Barang": "/sipetarung/assets/img/terminal_barang.png",
    "Terminal Khusus": "/sipetarung/assets/img/terminal_khusus.png"
};

var terminalLayer = L.geoJson(null);
var terminal = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: iconterminal[feature.properties.NAMOBJ],
                iconSize: [24, 24],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAMOBJ,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Jenis Rencana</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
            // $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="/sipetarung/assets/img/pkl.png"></td><td class="feature-name">' + layer.feature.properties.Nama + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
            // theaterSearch.push({
            //   name: layer.feature.properties.FGS_RENC,
            //   address: layer.feature.properties.LABEL,
            //   source: "Theaters",
            //   id: L.stamp(layer),
            //   lat: layer.feature.geometry.coordinates[1],
            //   lng: layer.feature.geometry.coordinates[0]
            // });
        }
    }
});
$.getJSON("/sipetarung/data/transport_pt.geojson", function(data) {
    terminal.addData(data);
    // map.addLayer(theaterLayer);
});

var jembatantimbangLayer = L.geoJson(null);
var jembatantimbang = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/telkom.png",
                iconSize: [24, 24],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAMOBJ,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Objek</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/telkom_pt.geojson", function(data) {
    jembatantimbang.addData(data);
    // map.addLayer(theaterLayer);
});

var iconstasiun = {
    "Gardu Listrik": "/sipetarung/assets/img/gardu_listrik.png",
    "Infrastruktur Minyak dan Gas Bumi": "/sipetarung/assets/img/infrastruktur_minyak.png",
    "Infrastruktur Pembangkitan Tenaga Listrik dan Sarana Pendukung": "/sipetarung/assets/img/infrastruktur_pembangkit.png"
};

var stasiunLayer = L.geoJson(null);
var stasiun = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: iconstasiun[feature.properties.NAMOBJ],
                iconSize: [24, 24],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.REMARK,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Objek</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/energi_pt.geojson", function(data) {
    stasiun.addData(data);
    // map.addLayer(theaterLayer);
});

var iconpelabuhan = {
    "Bangunan Pengendalian Banjir": "/sipetarung/assets/img/bangunan_banjir.png",
    "Bangunan Sumber Daya Air": "/sipetarung/assets/img/bangunan_sda.png"
};

var pelabuhanLayer = L.geoJson(null);
var pelabuhan = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: iconpelabuhan[feature.properties.NAMOBJ],
                iconSize: [24, 24],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAMA,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Objek</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/sda_pt.geojson", function(data) {
    pelabuhan.addData(data);
    // map.addLayer(theaterLayer);
});

var bandaraLayer = L.geoJson(null);
var bandara = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/bandara.png",
                iconSize: [24, 24],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.KETERANGAN,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Jenis Bandara</th><td>" + feature.properties.Jenis + "</td></tr>" + "<tr><th>Nama Bandara</th><td>" + feature.properties.KETERANGAN + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.KETERANGAN);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/bandara.geojson", function(data) {
    bandara.addData(data);
    // map.addLayer(theaterLayer);
});

var dipogasLayer = L.geoJson(null);
var dipogas = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/dipogas.png",
                iconSize: [24, 30],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.KET,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Dipo</th><td>" + feature.properties.NAMA + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.KET + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.KET);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/dipogas.geojson", function(data) {
    dipogas.addData(data);
    // map.addLayer(theaterLayer);
});

var iconpembangkit = {
    "Infrastruktur Sistem Pengelolaan Air Limbah (SPAL)": "/sipetarung/assets/img/spal.png",
    "Infrastruktur Sistem Penyediaan Air Minum (SPAM)": "/sipetarung/assets/img/spam1.png",
    "Sistem Jaringan Persampahan": "/sipetarung/assets/img/sampah.png",
    "Sistem Pengelolaan Limbah Bahan Berbahaya dan Beracun (B3)": "/sipetarung/assets/img/b3.png"
};

var pembangkitLayer = L.geoJson(null);
var pembangkit = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: iconpembangkit[feature.properties.NAMOBJ],
                iconSize: [24, 24],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.REMARK,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Objek</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/lainnya_pt.geojson", function(data) {
    pembangkit.addData(data);
    // map.addLayer(theaterLayer);
});

var iconpermukiman = {
    "Pusat Kegiatan Nasional (PKN)": "/sipetarung/assets/img/PKN.png",
    "Pusat Kegiatan Wilayah (PKW)": "/sipetarung/assets/img/pkw.png",
    "Pusat Kegiatan Lokal (PKL)": "/sipetarung/assets/img/pkl.png"
};

var permukimanLayer = L.geoJson(null);
var permukiman = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: iconpermukiman[feature.properties.NAMOBJ],
                iconSize: [24, 24],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.REMARK,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Objek</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Keterangan</th><td>" + feature.properties.REMARK + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.REMARK);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/pusat_permukiman.geojson", function(data) {
    permukiman.addData(data);
    // map.addLayer(theaterLayer);
});

var garduindukLayer = L.geoJson(null);
var garduinduk = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/garduinduk.png",
                iconSize: [24, 30],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.namobj,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Gardu Induk</th><td>" + feature.properties.namobj + "</td></tr>" + "<tr><th>Alamat</th><td>" + feature.properties.alamat + "<tr><th>Tegangan</th><td>" + feature.properties.teggi + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.namobj);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/garduinduk.geojson", function(data) {
    garduinduk.addData(data);
    // map.addLayer(theaterLayer);
});

var btsLayer = L.geoJson(null);
var bts = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/bts.png",
                iconSize: [22, 30],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAMOBJ,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Jenis Menara</th><td>" + feature.properties.REMARK + "</td></tr>" + "<tr><th>Nama Menara</th><td>" + feature.properties.NAMOBJ + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.KET);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/bts.geojson", function(data) {
    bts.addData(data);
    // map.addLayer(theaterLayer);
});

var bendunganLayer = L.geoJson(null);
var bendungan = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/bendungan.png",
                iconSize: [22, 30],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAMOBJ,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Bendungan</th><td>" + feature.properties.NAMOBJ + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.NAMOBJ);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/bendungan.geojson", function(data) {
    bendungan.addData(data);
    // map.addLayer(theaterLayer);
});

var situLayer = L.geoJson(null);
var situ = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/situ.png",
                iconSize: [24, 24],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAMOBJ,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Embung</th><td>" + feature.properties.NAMOBJ + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.NAMOBJ);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/situ.geojson", function(data) {
    situ.addData(data);
    // map.addLayer(theaterLayer);
});

var situ2Layer = L.geoJson(null);
var situ2 = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/situ2.png",
                iconSize: [24, 24],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAMOBJ,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Waduk</th><td>" + feature.properties.NAMOBJ + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.NAMOBJ);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/situ2.geojson", function(data) {
    situ2.addData(data);
    // map.addLayer(theaterLayer);
});

var mataairLayer = L.geoJson(null);
var mataair = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/mataair.png",
                iconSize: [24, 24],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAMOBJ,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Mata Air</th><td>" + feature.properties.NAMOBJ + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.NAMOBJ);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/mataair.geojson", function(data) {
    mataair.addData(data);
    // map.addLayer(theaterLayer);
});

var titikevakuasiLayer = L.geoJson(null);
var titikevakuasi = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/titikevakuasi.png",
                iconSize: [28, 20],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAMOBJ,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama Titik</th><td>" + feature.properties.NAMOBJ + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.NAMOBJ);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/titikevakuasii.geojson", function(data) {
    titikevakuasi.addData(data);
    // map.addLayer(theaterLayer);
});

var tpstLayer = L.geoJson(null);
var tpst = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/tpst.png",
                iconSize: [22, 26],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAMOBJ,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Nama TPA</th><td>" + feature.properties.NAMOBJ + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.NAMOBJ);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/tpst.geojson", function(data) {
    tpst.addData(data);
    // map.addLayer(theaterLayer);
});

/* Empty layer placeholder to add to layer control for listening when to add/remove museums to markerClusters layer */
var museumLayer = L.geoJson(null);
var museums = L.geoJson(null, {
    pointToLayer: function(feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/sipetarung/assets/img/favicon1.ico",
                iconSize: [28, 28],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.DESA,
            riseOnHover: true
        });
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Desa</th><td>" + feature.properties.DESA + "</td></tr>" + "<tr><th>Kecamatan</th><td>" + feature.properties.KECAMATAN + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.DESA);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
            $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="/sipetarung/assets/img/favicon-761.png"></td><td class="feature-name">' + layer.feature.properties.DESA + '</td><td style="vertical-align: middle;"><i class="fa fa-chevron-right pull-right"></i></td></tr>');
            museumSearch.push({
                name: layer.feature.properties.DESA,
                address: layer.feature.properties.KECAMATAN,
                source: "Museums",
                id: L.stamp(layer),
                lat: layer.feature.geometry.coordinates[1],
                lng: layer.feature.geometry.coordinates[0]
            });
        }
    }
});
$.getJSON("/sipetarung/data/titik_desa.geojson", function(data) {
    museums.addData(data);
    // map.addLayer(museumLayer);
});

var layerColors1 = {
    "Kawasan yang Memberikan Perlindungan terhadap Kawasan Bawahannya": "#224027",
    "Kawasan Ekosistem Mangrove": "#2D966E",
    "Kawasan Perlindungan Setempat": "#05D7D7",
    "Kawasan Pencadangan Konservasi di Laut": "#5A9696",
    "Badan Air": "#97DBF2",
    "Kawasan Konservasi": "#783CCD",
    "Kawasan Hutan Produksi": "#009B37",
    "Kawasan Pertanian": "#C8C83C",
    "Kawasan Pertambangan dan Energi": "#051937",
    "Kawasan Pergaraman": "#B49678",
    "Kawasan Pariwisata": "#FFA5FF",
    "Kawasan Perikanan": "#507DD2",
    "Kawasan Permukiman": "#FF7D00",
    "Kawasan Peruntukan Industri": "#690000",
    "Kawasan Transportasi": "#D73700",
    "Kawasan Pertahanan dan Keamanan": "#9B00FF"
};

var rpr = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: layerColors1[feature.properties.NAMOBJ],
            fillOpacity: 0.8,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.NAMOBJ + "</td></tr>" + "<tr><th>Diizinkan</th><td>" + feature.properties.diizinkan + "</td></tr>" + "<tr><th>Bersyarat</th><td>" + feature.properties.bersyarat + "</td></tr>" + "<tr><th>Dilarang</th><td>" + feature.properties.dilarang + "</td></tr>" + "<tr><th>Ketentuan Tambahan</th><td>" + feature.properties.tambahan + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.NAMOBJ);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                rpr.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/rpr7.geojson", function(data) {
    rpr.addData(data);
});

var ketsuskkop = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "red",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_KKOP_1 + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_KKOP_1);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsuskkop.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/kkop.geojson", function(data) {
    ketsuskkop.addData(data);
});

var ketsuskp2b = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "green",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_KP2B_2 + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_KP2B_2);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsuskp2b.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/kp2b.geojson", function(data) {
    ketsuskp2b.addData(data);
});

var ketsuskrb = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "red",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_KRB_03 + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_KRB_03);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsuskrb.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/krb.geojson", function(data) {
    ketsuskrb.addData(data);
});

var ketsuscagbud = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "red",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_CAGBUD + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_CAGBUD);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsuscagbud.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/cagbud.geojson", function(data) {
    ketsuscagbud.addData(data);
});

var ketsusresair = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "red",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_RESAIR + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_RESAIR);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsusresair.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/resair.geojson", function(data) {
    ketsusresair.addData(data);
});

var ketsusksmpdn = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "red",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_KSMPDN + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_KSMPDN);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsusksmpdn.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/ksmpdn.geojson", function(data) {
    ketsusksmpdn.addData(data);
});

var ketsushankam = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "red",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_HANKAM + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_HANKAM);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsushankam.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/hankam.geojson", function(data) {
    ketsushankam.addData(data);
});

var ketsuskkarst = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "red",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_KKARST + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_KKARST);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsuskkarst.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/kkarst.geojson", function(data) {
    ketsuskkarst.addData(data);
});

var ketsusptbgmb = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "red",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_PTBGMB + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_PTBGMB);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsusptbgmb.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/ptbgmb.geojson", function(data) {
    ketsusptbgmb.addData(data);
});

var ketsusmgrsat = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "red",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_MGRSAT + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_MGRSAT);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsusmgrsat.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/mgrsat.geojson", function(data) {
    ketsusmgrsat.addData(data);
});

var ketsusdlkpel = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "red",
            fillOpacity: 0.5,
            opacity: 0,
            clickable: true
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.d_DLKPEL + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.d_DLKPEL);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
        layer.on({
            mouseover: function(e) {
                var layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function(e) {
                ketsusdlkpel.resetStyle(e.target);
            }
        });
    }
});
$.getJSON("/sipetarung/data/dlkpel.geojson", function(data) {
    ketsusdlkpel.addData(data);
});

// var kp2b = L.geoJson(null, {
//   style: function (feature) {
//     return {
//       fillColor: "green",
//       fillOpacity: 0.6,
//       opacity: 0,
//       clickable: true
//     };
//   },
//   onEachFeature: function (feature, layer) {
//     if (feature.properties) {
//       var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Peruntukan</th><td>" + feature.properties.NAMOBJ +"</td></tr>" + "<tr><th>Pengaturan 1</th><td>" + feature.properties.aturan_1 +"</td></tr>" + "<tr><th>Pengaturan 2</th><td>" + feature.properties.aturan_2 + "<tr><th>Pengaturan 3</th><td>" + feature.properties.aturan_3 +"</td></tr>"+"<table>";
//       layer.on({
//         click: function (e) {
//           $("#feature-title").html(feature.properties.NAMOBJ);
//           $("#feature-info").html(content);
//           $("#featureModal").modal("show");
//           highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
//         }
//       });
//     }
//     layer.on({
//       mouseover: function (e) {
//         var layer = e.target;
//         layer.setStyle({
//           weight: 3,
//           color: "#00FFFF",
//           opacity: 1
//         });
//         if (!L.Browser.ie && !L.Browser.opera) {
//           layer.bringToFront();
//         }
//       },
//       mouseout: function (e) {
//         kp2b.resetStyle(e.target);
//       }
//     });
//   }
// });
// $.getJSON("/sipetarung/data/kp2b.geojson", function (data) {
//   kp2b.addData(data);
// });

var batasdesaLayer = L.geoJson(null);
var batasdesa = L.geoJson(null, {
    style: function(feature) {
        return {
            fillColor: "black",
            fillOpacity: 0,
            color: "black",
            weight: 1,
            opacity: 0.6,
        };
    },
    onEachFeature: function(feature, layer) {
        if (feature.properties) {
            var content = "<table class='table table-striped table-bordered table-condensed'>" + "<tr><th>Desa</th><td>" + feature.properties.DESA + "</td></tr>" + "<tr><th>Kecamatan</th><td>" + feature.properties.KECAMATAN + "</td></tr>" + "<table>";
            layer.on({
                click: function(e) {
                    $("#feature-title").html(feature.properties.Sub_3);
                    $("#feature-info").html(content);
                    $("#featureModal").modal("show");
                    highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
                }
            });
        }
    }
});
$.getJSON("/sipetarung/data/batas_desa.geojson", function(data) {
    batasdesa.addData(data);
});

// "<tr><th>Desa</th><td>" + feature.properties.DESA + "<tr><th>Kecamatan</th><td>" + feature.properties.KECAMATAN +

map = L.map("map", {
    zoom: 11,
    center: [-8.398190, 115.188038],
    layers: [OpenStreetMap_Mapnik],
    // SET DEFAULT KLIK
    zoomControl: false,
    attributionControl: false
});

/* GeoSearch Provider */
const provider = new GeoSearch.OpenStreetMapProvider({
    params: {
        countrycodes: 'id',
        'accept-language': 'id, en',
        addressdetails: 1
    }
});

/* GeoSearch Leaflet Control */
const searchControl = new GeoSearch.GeoSearchControl({
    provider: provider,
    // required
    style: 'bar',
    // optional: bar|button  - default button
    showPopup: true,
    // optional: true|false  - default false
    searchLabel: 'Masukkan alamat',
    // optional: string - default 'Enter address'
});

map.addControl(searchControl);

// var searchControl = new L.Control.Search({
//   layer: batasdesa,
//   propertyName: 'DESA',
//   layer: rpr,
//   propertyName: 'NAMOBJ',
//   layer: jalan_kolektor,
//   propertyName: 'Nama_Jln',
//   layer: jalan_arteri,
//   propertyName: 'Nama_Jln',
//   layer: jalan_desa,
//   propertyName: 'Nama_Jln',
//   layer: jalan_lokal,
//   propertyName: 'Nama_Jln',
//   marker: false,
//   position: 'topleft',
//   moveToLocation: function(latlng, title, map) {
//     var zoom = map.getBoundsZoom(latlng.layer.getBounds());
//     map.setView(latlng, zoom);
//   }
// });
// searchControl.on('search:locationfound', function(e) {
//   e.layer.setStyle({
//     fillColor: 'cyan',
//     color: 'black'
//   });
// }).on('search:collapsed', function(e) {
//   featuresLayer.eachLayer(function(layer) {
//     featuresLayer.resetStyle(layer);
//   });
// });
// map.addControl(searchControl);

var searchControl2 = new L.Control.Search2({
    layer: batasdesa,
    propertyName: 'DESA',
    // layer: rpr,
    // propertyName: 'NAMOBJ',
    // layer: jalan_kolektor,
    // propertyName: 'Nama_Jln',
    // layer: jalan_arteri,
    // propertyName: 'Nama_Jln',
    // layer: jalan_desa,
    // propertyName: 'Nama_Jln',
    // layer: jalan_lokal,
    // propertyName: 'Nama_Jln',
    marker: false,
    position: 'topleft',
    moveToLocation: function(latlng, title, map) {
        var zoom = map.getBoundsZoom(latlng.layer.getBounds());
        map.setView(latlng, zoom);
    }
});
searchControl2.on('search:locationfound', function(e) {
    e.layer.setStyle({
        fillColor: 'cyan',
        color: 'black'
    });
}).on('search:collapsed', function(e) {
    featuresLayer.eachLayer(function(layer) {
        featuresLayer.resetStyle(layer);
    });
});
// map.addControl(searchControl2);

var searchControl1 = new L.Control.Search1({
    // layer: batasdesa,
    // propertyName: 'DESA',
    layer: rpr,
    propertyName: 'NAMOBJ',
    // layer: jalan_kolektor,
    // propertyName: 'Nama_Jln',
    // layer: jalan_arteri,
    // propertyName: 'Nama_Jln',
    // layer: jalan_desa,
    // propertyName: 'Nama_Jln',
    // layer: jalan_lokal,
    // propertyName: 'Nama_Jln',
    marker: false,
    position: 'topleft',
    moveToLocation: function(latlng, title, map) {
        var zoom = map.getBoundsZoom(latlng.layer.getBounds());
        map.setView(latlng, zoom);
    }
});
searchControl1.on('search:locationfound', function(e) {
    e.layer.setStyle({
        fillColor: 'cyan',
        color: 'black'
    });
}).on('search:collapsed', function(e) {
    featuresLayer.eachLayer(function(layer) {
        featuresLayer.resetStyle(layer);
    });
});
// map.addControl(searchControl1);

// var searchControl = new L.Control.Search({
//   layer: batasdesa,
//   propertyName: 'DESA',
//   layer: rpr,
//   propertyName: 'NAMOBJ',
//   marker: false,
//   position: 'topleft',
//   moveToLocation: function(latlng, title, map) {
//     var zoom = map.getBoundsZoom(latlng.layer.getBounds());
//     map.setView(latlng, zoom);
//   }
// });
// searchControl.on('search:locationfound', function(e) {
//   e.layer.setStyle({
//     fillColor: 'cyan',
//     color: 'black'
//   });
// }).on('search:collapsed', function(e) {
//   featuresLayer.eachLayer(function(layer) {
//     featuresLayer.resetStyle(layer);
//   });
// });
// map.addControl(searchControl);

L.control.coordinates().addTo(map);
// L.control.coordinates({
//   position:"bottomleft", //optional default "bootomright"
//   decimals:2, //optional default 4
//   decimalSeperator:".", //optional default "."
//   labelTemplateLat:"Latitude: {y}", //optional default "Lat: {y}"
//   labelTemplateLng:"Longitude: {x}", //optional default "Lng: {x}"
//   enableUserInput:true, //optional default true
//   useDMS:false, //optional default false
//   useLatLngOrder: true, //ordering of labels, default false-> lng-lat
//   markerType: L.marker, //optional default L.marker
//   markerProps: {}, //optional default {},
//   labelFormatterLng : function(lng){return lng+" lng"}, //optional default none,
//   labelFormatterLat : function(lat){return lat+" lat"}, //optional default none
//   customLabelFcn: function(latLonObj, opts) { "Geohash: " + encodeGeoHash(latLonObj.lat, latLonObj.lng)} //optional default none
// }).addTo(map);

/* Layer control listeners that allow for a single markerClusters layer */
map.on("overlayadd", function(e) {
    if (e.layer === theaterLayer) {
        markerClusters.addLayer(theaters);
        syncSidebar();
    }
    if (e.layer === museumLayer) {
        markerClusters.addLayer(museums);
        syncSidebar();
    }
});

map.on("overlayremove", function(e) {
    if (e.layer === theaterLayer) {
        markerClusters.removeLayer(theaters);
        syncSidebar();
    }
    if (e.layer === museumLayer) {
        markerClusters.removeLayer(museums);
        syncSidebar();
    }
});

/* Filter sidebar feature list to only show features in current map bounds */
map.on("moveend", function(e) {
    syncSidebar();
});

/* Clear feature highlight when map is clicked */
map.on("click", function(e) {
    highlight.clearLayers();
});

/* Attribution control */
function updateAttribution(e) {
    $.each(map._layers, function(index, layer) {
        if (layer.getAttribution) {
            $("#attribution").html((layer.getAttribution()));
        }
    });
}
map.on("layeradd", updateAttribution);
map.on("layerremove", updateAttribution);

var attributionControl = L.control({
    position: "bottomright"
});
attributionControl.onAdd = function(map) {
    var div = L.DomUtil.create("div", "leaflet-control-attribution");
    div.innerHTML = "<span class='hidden-xs'>Dikembangkan oleh <a href='http://baliprov.go.id'>baliprov.go.id</a> | </span><a href='#' onclick='$(\"#attributionModal\").modal(\"show\"); return false;'>Pemerintah Provinsi Bali</a>";
    return div;
}
;
map.addControl(attributionControl);

var zoomControl = L.control.zoom({
    position: "topleft"
}).addTo(map);

/* GPS enabled geolocation control set to follow the user's location */
var locateControl = L.control.locate({
    position: "topleft",
    drawCircle: true,
    follow: true,
    setView: true,
    keepCurrentZoomLevel: true,
    markerStyle: {
        weight: 1,
        opacity: 0.8,
        fillOpacity: 0.8
    },
    circleStyle: {
        weight: 1,
        clickable: false
    },
    icon: "fa fa-location-arrow",
    metric: false,
    strings: {
        title: "My location",
        popup: "You are within {distance} {unit} from this point",
        outsideMapBoundsMsg: "You seem located outside the boundaries of the map"
    },
    locateOptions: {
        maxZoom: 18,
        watch: true,
        enableHighAccuracy: true,
        maximumAge: 10000,
        timeout: 10000
    }
}).addTo(map);

/* Larger screens get expanded layer control and visible sidebar */
if (document.body.clientWidth <= 767) {
    var isCollapsed = true;
} else {
    var isCollapsed = false;
}

var baseLayers = {
    "Open Street Map": OpenStreetMap_Mapnik,
    "Bing Satelit": Esri_WorldImagery
};

var groupedOverlays = {
    "Basemaps": {
        "Open Street Map": OpenStreetMap_Mapnik,
        "Bing Satelit": Esri_WorldImagery
    },

    "Batas Administrasi": {
        "Batas Kecamatan": bataskec
    },

    "Ketentuan Khusus": {
        "Ketentuan Khusus KKOP": ketsuskkop,
        "Ketentuan Khusus KP2B": ketsuskp2b,
        "Ketentuan Khusus BENCANA": ketsuskrb,
        "Ketentuan Khusus CAGAR BUDAYA": ketsuscagbud,
        "Ketentuan Khusus RESAPAN AIR": ketsusresair,
        "Ketentuan Khusus SEMPADAN": ketsusksmpdn,
        "Ketentuan Khusus HANKAM": ketsushankam,
        "Ketentuan Khusus KARST": ketsuskkarst,
        "Ketentuan Khusus PERTAMBANGAN": ketsusptbgmb,
        "Ketentuan Khusus MIGRASI SATWA": ketsusmgrsat,
        "Ketentuan Khusus DLKP": ketsusdlkpel
    },

    "Sistem Pusat Permukiman": {
        "Sistem Pusat Permukiman <p> <img src='/sipetarung/assets/img/permukiman.png' width='160'>": permukiman
    },
    "Sistem Jaringan Transportasi": {
        "Jaringan Transportasi <p> <img src='/sipetarung/assets/img/arteri.png' width='260'>": jalan_arteri,
        "Infrastruktur Transportasi <p> <img src='/sipetarung/assets/img/transport_pt.png' width='210'>": terminal
    },

    "Sistem Jaringan Energi": {
        "Jaringan Energi <p> <img src='/sipetarung/assets/img/energi_ln.png' width='260'>": jaringangas,
        "Infrastruktur Energi <p> <img src='/sipetarung/assets/img/energi_pt.png' width='260'>": stasiun
    },

    // "Jaringan Transmisi Tenaga Listrik": {
    //   "Transmisi Listrik <p> <img src='/sipetarung/assets/img/transmisi.png' width='250'>": jaringanlistrik
    // },

    "Sistem Jaringan Telekomunikasi": {
        "<img src='/sipetarung/assets/img/telekomunikasi_ln.png' width='24' height='5'>&nbsp;Jaringan Tetap": jaringantelkom,
        "<img src='/sipetarung/assets/img/telekomunikasi_pt.png' width='20' height='24' height='5'>&nbsp;Infrastruktur Jaringan Tetap": jembatantimbang
    },

    "Sistem Jaringan Sumber Daya Air": {
        "Jaringan Sumber Daya Air <p> <img src='/sipetarung/assets/img/sda_ln.png' width='200'>": spam,
        "Infrastruktur Sumber Daya Air <p> <img src='/sipetarung/assets/img/sda_pt.png' width='200'>": pelabuhan
    },

    "Sistem Jaringan Prasarana Lainnya": {
        "Jaringan Prasarana Lainnya <p> <img src='/sipetarung/assets/img/lainnya_ln.png' width='230'>": jalurevakuasi,
        "Infrastruktur Prasarana Lainnya <p> <img src='/sipetarung/assets/img/lainnya_pt.png' width='230'>": pembangkit
    },

    "Rencana Pola Ruang": {
        "Rencana Pola Ruang <p> <img src='/sipetarung/assets/img/rpr.png' width='260'>": rpr
    }
};

var layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
    collapsed: isCollapsed
})
layerControl.addTo(map);

/* Highlight search box text on click */
$("#searchbox").click(function() {
    $(this).select();
});

/* Prevent hitting enter from refreshing the page */
$("#searchbox").keypress(function(e) {
    if (e.which == 13) {
        e.preventDefault();
    }
});

$("#featureModal").on("hidden.bs.modal", function(e) {
    $(document).on("mouseout", ".feature-row", clearHighlight);
});

/* Typeahead search functionality */
$(document).one("ajaxStop", function() {
    $("#loading").hide();
    sizeLayerControl();
    /* Fit map to boroughs bounds */
    map.fitBounds(bataskec.getBounds());
    featureList = new List("features",{
        valueNames: ["feature-name"]
    });
    featureList.sort("feature-name", {
        order: "asc"
    });

    var bataskecBH = new Bloodhound({
        name: "Batas Kecamatan",
        datumTokenizer: function(d) {
            return Bloodhound.tokenizers.whitespace(d.name);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: bataskecSearch,
        limit: 10
    });

    var rprBH = new Bloodhound({
        name: "Rencana Pola Ruang",
        datumTokenizer: function(d) {
            return Bloodhound.tokenizers.whitespace(d.name);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: rprSearch,
        limit: 10
    });

    var theatersBH = new Bloodhound({
        name: "Theaters",
        datumTokenizer: function(d) {
            return Bloodhound.tokenizers.whitespace(d.name);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: theaterSearch,
        limit: 10
    });

    var titikdesaBH = new Bloodhound({
        name: "Titik Desa",
        datumTokenizer: function(d) {
            return Bloodhound.tokenizers.whitespace(d.name);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: titikdesaSearch,
        limit: 10
    });

    var museumsBH = new Bloodhound({
        name: "Museums",
        datumTokenizer: function(d) {
            return Bloodhound.tokenizers.whitespace(d.name);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: museumSearch,
        limit: 10
    });

    var geonamesBH = new Bloodhound({
        name: "GeoNames",
        datumTokenizer: function(d) {
            return Bloodhound.tokenizers.whitespace(d.name);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: "http://api.geonames.org/searchJSON?username=bootleaf&featureClass=P&maxRows=5&countryCode=US&name_startsWith=%QUERY",
            filter: function(data) {
                return $.map(data.geonames, function(result) {
                    return {
                        name: result.name + ", " + result.adminCode1,
                        lat: result.lat,
                        lng: result.lng,
                        source: "GeoNames"
                    };
                });
            },
            ajax: {
                beforeSend: function(jqXhr, settings) {
                    settings.url += "&east=" + map.getBounds().getEast() + "&west=" + map.getBounds().getWest() + "&north=" + map.getBounds().getNorth() + "&south=" + map.getBounds().getSouth();
                    $("#searchicon").removeClass("fa-search").addClass("fa-refresh fa-spin");
                },
                complete: function(jqXHR, status) {
                    $('#searchicon').removeClass("fa-refresh fa-spin").addClass("fa-search");
                }
            }
        },
        limit: 10
    });
    bataskecBH.initialize();
    // rprBH.initialize();
    theatersBH.initialize();
    titikdesaBH.initialize();
    museumsBH.initialize();
    geonamesBH.initialize();

    /* instantiate the typeahead UI */
    $("#searchbox").typeahead({
        minLength: 3,
        highlight: true,
        hint: false
    }, {
        name: "Batas Kecamatan",
        displayKey: "KECAMATAN",
        source: bataskecBH.ttAdapter(),
        templates: {
            header: "<h4 class='typeahead-header'>Boroughs</h4>"
        }
    }, {
        name: "Pompa P2AT",
        displayKey: "full_id",
        source: theatersBH.ttAdapter(),
        templates: {
            header: "<h4 class='typeahead-header'><img src='/sipetarung/assets/img/pju.png' width='24' height='28'>&nbsp;Theaters</h4>",
            suggestion: Handlebars.compile(["{{name}}<br>&nbsp;<small>{{address}}</small>"].join(""))
        }
    }, {
        name: "Titik Desa",
        displayKey: "full_id",
        source: titikdesaBH.ttAdapter(),
        templates: {
            header: "<h4 class='typeahead-header'><img src='/sipetarung/assets/img/favicon-761.png' width='24' height='28'>&nbsp;Desa</h4>",
            suggestion: Handlebars.compile(["{{name}}<br>&nbsp;<small>{{address}}</small>"].join(""))
        }
    }, {
        name: "Museums",
        displayKey: "name",
        source: museumsBH.ttAdapter(),
        templates: {
            header: "<h4 class='typeahead-header'><img src='/sipetarung/assets/img/app.gif' width='24' height='28'>&nbsp;Museums</h4>",
            suggestion: Handlebars.compile(["{{name}}<br>&nbsp;<small>{{address}}</small>"].join(""))
        }
    }, {
        name: "GeoNames",
        displayKey: "name",
        source: geonamesBH.ttAdapter(),
        templates: {
            header: "<h4 class='typeahead-header'><img src='/sipetarung/assets/img/globe.png' width='25' height='25'>&nbsp;GeoNames</h4>"
        }
    }).on("typeahead:selected", function(obj, datum) {
        if (datum.source === "Batas Kecamatan") {
            map.fitBounds(datum.bounds);
        }
        if (datum.source === "Rencana Pola Ruang") {
            map.fitBounds(datum.bounds);
        }
        if (datum.source === "Theaters") {
            if (!map.hasLayer(theaterLayer)) {
                map.addLayer(theaterLayer);
            }
            map.setView([datum.lat, datum.lng], 17);
            if (map._layers[datum.id]) {
                map._layers[datum.id].fire("click");
            }
        }
        if (datum.source === "Titik Desa") {
            if (!map.hasLayer(titikdesaLayer)) {
                map.addLayer(titikdesaLayer);
            }
            map.setView([datum.lat, datum.lng], 17);
            if (map._layers[datum.id]) {
                map._layers[datum.id].fire("click");
            }
        }
        if (datum.source === "Museums") {
            if (!map.hasLayer(museumLayer)) {
                map.addLayer(museumLayer);
            }
            map.setView([datum.lat, datum.lng], 17);
            if (map._layers[datum.id]) {
                map._layers[datum.id].fire("click");
            }
        }
        if (datum.source === "GeoNames") {
            map.setView([datum.lat, datum.lng], 14);
        }
        if ($(".navbar-collapse").height() > 50) {
            $(".navbar-collapse").collapse("hide");
        }
    }).on("typeahead:opened", function() {
        $(".navbar-collapse.in").css("max-height", $(document).height() - $(".navbar-header").height());
        $(".navbar-collapse.in").css("height", $(document).height() - $(".navbar-header").height());
    }).on("typeahead:closed", function() {
        $(".navbar-collapse.in").css("max-height", "");
        $(".navbar-collapse.in").css("height", "");
    });
    $(".twitter-typeahead").css("position", "static");
    $(".twitter-typeahead").css("display", "block");
});

// Leaflet patch to make layer control scrollable on touch browsers
var container = $(".leaflet-control-layers")[0];
if (!L.Browser.touch) {
    L.DomEvent.disableClickPropagation(container).disableScrollPropagation(container);
} else {
    L.DomEvent.disableClickPropagation(container);
}

var measureControl = new L.Control.Measure({
    primaryLengthUnit: 'meters',
    secondaryLengthUnit: 'kilometers',
    primaryAreaUnit: 'hectares',
    secondaryAreaUnit: 'sqmeters',
    activeColor: 'green',
    completedColor: 'blue'
});
measureControl.addTo(map);

// L.easyPrint({
//    title: 'Print',
//    sizeModes: ['CurrentSize', 'A4Landscape', 'A4Portrait'],
//    position: "topleft",
// }).addTo(map);

// L.Control.Watermark = L.Control.extend({
//    onAdd: function(map) {
//       var img = L.DomUtil.create('img');
//       img.src = '/sipetarung/assets/img/bojonegoro.png';
//       img.style.width = '50px';
//       return img;
//    },
//    onRemove: function(map) {
//       // Nothing to do here
//    }
// });
// L.control.watermark = function(opts) {
//    return new L.Control.Watermark(opts);
// }
// L.control.watermark({ position: 'bottomleft' }).addTo(map);
