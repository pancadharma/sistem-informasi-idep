import requests
import json

# Daftar kode BPS untuk provinsi di Indonesia
provinsi_kode = [
    11, 12, 13, 14, 15, 16, 17, 18, 19, 21, 31, 32, 33, 34, 35, 36,
    51, 52, 53, 61, 62, 63, 64, 65, 71, 72, 73, 74, 75, 76, 81, 82,
    91, 92, 94, 95, 96, 97
]

# URL template untuk API
url_template = "https://sig.bps.go.id/rest-bridging/getwilayah?level=kabupaten&parent={}"

# Dictionary untuk menyimpan hasil
data_kabupaten = {}

# Loop untuk setiap kode provinsi
for kode in provinsi_kode:
    url = url_template.format(kode)
    response = requests.get(url)
    
    if response.status_code == 200:
        kabupaten_list = response.json()
        
        # List untuk menyimpan data kabupaten
        kabupaten_data = []
        
        for kabupaten in kabupaten_list:
            try:
                kabupaten_nama = kabupaten['nama_bps']
                kabupaten_data.append({'nama_bps': kabupaten_nama})
            except KeyError as e:
                print(f"KeyError: {e} occurred in kabupaten with data: {kabupaten}")
        
        data_kabupaten[kode] = kabupaten_data
        print(f"Berhasil mendapatkan data untuk provinsi dengan kode {kode}")
    else:
        print(f"Gagal mendapatkan data untuk provinsi dengan kode {kode}")

# Menghasilkan kode PHP untuk seeder Laravel
laravel_seeder = "<?php\n\nreturn [\n"

for kode, kabupaten_data in data_kabupaten.items():
    laravel_seeder += f"    // Data untuk provinsi dengan kode {kode}\n"
    laravel_seeder += f"    [\n"
    for kabupaten in kabupaten_data:
        kabupaten_nama = kabupaten['nama_bps']
        laravel_seeder += f"        ['nama_bps' => '{kabupaten_nama}'],\n"
    laravel_seeder += f"    ],\n"

laravel_seeder += "];\n"

# Menyimpan hasil ke file PHP
with open('DatabaseSeeder.php', 'w') as f:
    f.write(laravel_seeder)

print("Seeder Laravel telah di-generate ke file 'DatabaseSeeder.php'")
