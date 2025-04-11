var endpoint_url = "https://aset-dives-dev.ptpn1.co.id/weather";

let typed;
function getData() {
    // Ambil input dari elemen dengan ID #pertanyaan-lanjutan dan ubah ke huruf kecil
    let question = $('#pertanyaan-lanjutan').val().toLowerCase();

    // Deteksi kata kunci menggunakan NLP sederhana
    if (question.includes("kerjasama") || question.includes("kerja sama") || question.includes("aset")) {
        return getDataInfo();
    } else {
        return getDataAi();
    } 

    // else {
    //     return "Maaf, saya tidak memiliki data untuk pertanyaan ini.";
    // }
}



async function getDataInfo(){
    var data = $('#pertanyaan-lanjutan').val();
    $("#aiKesimpulan").html("<pre style='font-family: monospace; white-space: pre-wrap; word-wrap: break-word;'>Menganalisa....</pre>");

    // Tampilkan loading overlay sebelum mengirim permintaan
    document.getElementById('loading-overlay').style.display = 'block';

    try {
        let response = await $.ajax({
            url: endpoint_url+"Weather/ai_query", // Endpoint server
            type: "POST",
            dataType: "json",
            data: { tanya: data }
        });

        typed = startTyping(response);

        var data_map = response['data_map'];
        load_gjson(data_map);
    } catch (error) {
        console.error("Error: ", error);
        //alert('Maaf, terjadi kesalahan, harap klik tanya lagi');
        getDataInfo();
        return null; 
    } finally {
        setTimeout(() => {
            document.getElementById('loading-overlay').style.display = 'none';
        }, 1500);
    }
}


async function getDataAi(){
    var data = $('#pertanyaan-lanjutan').val();
    $("#aiKesimpulan").html("<pre style='font-family: monospace; white-space: pre-wrap; word-wrap: break-word;'>Menganalisa....</pre>");

    // Tampilkan loading overlay sebelum mengirim permintaan
    document.getElementById('loading-overlay').style.display = 'block';

    try {
        let response = await $.ajax({
            url: endpoint_url+"Weather/ai_jawab", // Endpoint server
            type: "POST",
            dataType: "json",
            data: { tanya: data }
        });

        
        typed = startTyping(response);

    } catch (error) {
        console.error("Error: ", error);
        getDataAi();
        return null; // Jika terjadi error, kembalikan null
    } finally {
        setTimeout(() => {
            document.getElementById('loading-overlay').style.display = 'none';
        }, 1500);
    }
}


function startTyping(response) {
    // Hapus Typed.js lama jika ada
    if (typed) {
        typed.destroy();  // Hancurkan instance sebelumnya
        typed = null;      // Pastikan instance benar-benar dihapus
    }

    // Kosongkan elemen sebelum mengetik ulang
    let target = document.querySelector('#aiKesimpulan');
    target.innerHTML = '';

    // Tunggu sebentar agar elemen benar-benar kosong sebelum Typed.js mulai
    setTimeout(() => {
        typed = new Typed('#aiKesimpulan', {
            strings: [response['response']],
            typeSpeed: 10,
            loop: false,
            onComplete: (self) => {
                self.cursor.remove(); // Hapus cursor setelah selesai mengetik
            }
        });
    }, 50); // Delay 50ms untuk memastikan elemen kosong
}