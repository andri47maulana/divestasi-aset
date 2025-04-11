var drawnItems = new L.FeatureGroup();
	map.addLayer(drawnItems);
var drawControl_marker = new L.Control.Draw({
        draw: {
            polygon: false,   // Nonaktifkan Polygon
            polyline: false,  // Nonaktifkan Garis
            rectangle: false, // Nonaktifkan Persegi
            circle: false,    // Nonaktifkan Lingkaran
            circlemarker: false,
            marker: true      // Aktifkan Marker
        },
        edit: {
            featureGroup: drawnItems, // Edit marker yang sudah digambar
            remove: true
        }
    });

var drawControl_polygon = new L.Control.Draw({
        draw: {
            polygon: true,   // Aktifkan Polygon
            polyline: false, // Nonaktifkan Garis
            rectangle: false, // Nonaktifkan Persegi
            circle: false,    // Nonaktifkan Lingkaran
            circlemarker: false,
            marker: false     // Nonaktifkan Marker
        },
        edit: {
            featureGroup: drawnItems, // Edit polygon yang sudah digambar
            remove: true
        }
    });

var drawControl_line = new L.Control.Draw({
        draw: {
            polygon: false,   // Aktifkan Polygon
            polyline: true, // Nonaktifkan Garis
            rectangle: false, // Nonaktifkan Persegi
            circle: false,    // Nonaktifkan Lingkaran
            circlemarker: false,
            marker: false     // Nonaktifkan Marker
        },
        edit: {
            featureGroup: drawnItems, // Edit polygon yang sudah digambar
            remove: true
        }
    });




//Event Saat Marker atau Polygon Ditambahkan
map.on('draw:created', function (e) {
    var layer = e.layer;
    drawnItems.addLayer(layer);

    // Buat input text untuk deskripsi
    var inputTitle = document.createElement("input");
    inputTitle.type = "text";
    inputTitle.placeholder = "Masukkan Judul/Topik...";
    inputTitle.style.width = "200px";


    // Buat textarea untuk deskripsi
    var textarea = document.createElement("textarea");
    textarea.rows = 2;
    textarea.cols = 30;
    textarea.placeholder = "Masukkan deskripsi...";

    
    
    // Tombol simpan
    var saveButton = document.createElement("button");
    saveButton.innerText = "Simpan";
    saveButton.style.display = "block";



    // Buat popup Leaflet
    var popupContent = document.createElement("div");
    popupContent.appendChild(inputTitle);

    var uploadButton = document.createElement("button");
    uploadButton.innerText = "Upload File";


    popupContent.appendChild(textarea);
    popupContent.appendChild(uploadButton);
    popupContent.appendChild(saveButton);

    layer.bindPopup(popupContent).openPopup();



    var popupContainer = document.createElement("div");
    popupContainer.style.display = "none"; // Sembunyikan awalnya
    popupContainer.style.position = "fixed";
    popupContainer.style.top = "50%";
    popupContainer.style.left = "50%";
    popupContainer.style.transform = "translate(-50%, -50%)";
    popupContainer.style.background = "#fff";
    popupContainer.style.padding = "20px";
    popupContainer.style.boxShadow = "0px 4px 6px rgba(0,0,0,0.1)";
    popupContainer.style.borderRadius = "10px";
    popupContainer.style.zIndex = "1000";

    // Buat tombol close di popup
    var closeButton = document.createElement("button");
    closeButton.innerText = "Tutup";
    closeButton.style.display = "block";
    closeButton.style.marginTop = "10px";
    popupContainer.appendChild(closeButton);

    // Buat div untuk Uppy Dashboard
    var uppyDashboard = document.createElement("div");
    popupContainer.appendChild(uppyDashboard);
    document.body.appendChild(popupContainer);

    // Inisialisasi Uppy
    var uppy = new Uppy.Uppy({
        restrictions: {
            maxNumberOfFiles: 5,
            allowedFileTypes: ['image/*', 'application/pdf']
        }
    });

    uppy.use(Uppy.Dashboard, {
        target: uppyDashboard,
        inline: true,
        proudlyDisplayPoweredByUppy: false
    });

    uppy.use(Uppy.XHRUpload, {
        endpoint: 'https://your-server.com/upload',
        fieldName: 'files'
    });

    // Progress upload
    uppy.on('upload-progress', (file, progress) => {
        console.log(`Progress ${file.name}: ${progress.bytesUploaded}/${progress.bytesTotal}`);
    });

   
     // Event tombol "Upload File"
    uploadButton.addEventListener("click", function () {
        popupContainer.style.display = "block"; // Munculkan popup
    });

    // Event tombol "Tutup"
    closeButton.addEventListener("click", function () {
        popupContainer.style.display = "none"; // Sembunyikan popup
    });

    // Event saat upload selesai
    uppy.on("complete", (result) => {
        console.log("Upload selesai!", result.successful);
        alert("File berhasil diunggah!");
        popupContainer.style.display = "none"; // Tutup popup setelah upload
    });



    // Event tombol simpan
    saveButton.addEventListener("click", function () {
        var description = textarea.value || "";
        var title = inputTitle.value || "";
        var type = e.layerType;
		var polylineData = [];
		var saveData = [];


        var data = [];
		var markerData = [];
		var polygonData = [];

		if (e.layerType === 'marker') {
		    // Jika marker ditambahkan
		    var latlng = layer.getLatLng();
		    markerData.push({ 
		        type: "Point", 
		        coordinates: {
		            latitude: latlng.lat.toString(), 
		            longitude: latlng.lng.toString()
		        }
		    });
		    data = markerData;

		} else if (e.layerType === 'polygon') {
		    // Jika polygon ditambahkan
		    var latlngs = layer.getLatLngs()[0];
		    var polygonInfo = latlngs.map(latlng => ({
		        latitude: latlng.lat.toString(),
		        longitude: latlng.lng.toString()
		    }));

		    polygonData.push({ 
		        type: "Polygon", 
		        coordinates: polygonInfo
		    });
		    data = polygonData;
		} else if (e.layerType === 'polyline') {
		    // Jika polyline ditambahkan
		    var latlngs = layer.getLatLngs();
		    var polylineInfo = latlngs.map(latlng => ({
		        latitude: latlng.lat,
		        longitude: latlng.lng
		    }));

		    polylineData.push({ type,coordinates: polylineInfo,description });
		    data = polylineData;

		}

		saveData={title,
			      data: data,
			      desc: description
			     };

        // Tutup popup setelah simpan
        layer.closePopup();
        saveJSONToMysql(saveData);
	});
});

//Event Saat Objek Dihapus
map.on('draw:deleted', function (e) {
    var layers = e.layers;
    layers.eachLayer(function (layer) {
        if (layer instanceof L.Marker) {
            // Jika yang dihapus adalah marker, hapus dari markerData
            var latlng = layer.getLatLng();
            markerData = markerData.filter(m => !(m.lat === latlng.lat && m.lng === latlng.lng));
        } else if (layer instanceof L.Polygon) {
            // Jika yang dihapus adalah polygon, hapus dari polygonData
            var latlngs = layer.getLatLngs()[0];
            polygonData = polygonData.filter(poly => {
                return JSON.stringify(poly) !== JSON.stringify(latlngs.map(latlng => ({
                    latitude: latlng.lat,
                    longitude: latlng.lng
                })));
            });
        }
    });

    
});

// //Fungsi untuk memperbarui tampilan JSON di halaman
// function updateJSONDisplay() {
// 	saveJSONToMysql(saveData);
// 	saveData = [];
// }



function saveJSONToMysql(jsonData){
        $.ajax({
            url: "map_ai/saveJson",
            type: "POST",
            data: { jsonData },
            success: function (response) {
                alert(response);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        }); 

        
}



map.addControl(drawControl_polygon);
map.addControl(drawControl_marker);
map.addControl(drawControl_line);
