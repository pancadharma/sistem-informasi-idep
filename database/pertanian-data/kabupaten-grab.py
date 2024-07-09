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
        data_kabupaten[kode] = kabupaten_list
        print(f"Berhasil mendapatkan data untuk provinsi dengan kode {kode}")
    else:
        print(f"Gagal mendapatkan data untuk provinsi dengan kode {kode}")

# Menyimpan hasil ke file JSON
with open('data_kabupaten.json', 'w') as f:
    json.dump(data_kabupaten, f, indent=4)

print("Data kabupaten/kota telah disimpan ke file 'data_kabupaten.json'")
