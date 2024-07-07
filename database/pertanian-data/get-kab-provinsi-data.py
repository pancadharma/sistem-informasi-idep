import requests
import json
import os

# URL template for fetching kabupaten data
url_template = "https://sipedas.pertanian.go.id/api/wilayah/list_kab?thn=2024&lvl=11&pro={}"

# URL for fetching the province list
url_provinces = "https://sipedas.pertanian.go.id/api/wilayah/list_pro?thn=2024"

# Fetch the province data from the URL
response_provinces = requests.get(url_provinces)

# Check if the request was successful
if response_provinces.status_code == 200:
    province_data = response_provinces.json()
    
    # Get the directory of the current .py file
    script_dir = os.path.dirname(os.path.abspath(__file__))
    
    # Define the output file path
    output_file = os.path.join(script_dir, 'data-kabupaten.json')
    
    all_results = []

    # Iterate through the province data
    for kode_provinsi, nama_provinsi in province_data.items():
        # Fetch the kabupaten data for each province
        url = url_template.format(kode_provinsi)
        response_kabupaten = requests.get(url)
        
        if response_kabupaten.status_code == 200:
            kabupaten_data = response_kabupaten.json()
            
            # Transform and store the kabupaten data
            for kode_kabupaten, nama_kabupaten in kabupaten_data.items():
                all_results.append(
                {
                    'kode_provinsi': int(kode_provinsi),
                    'nama_provinsi': nama_provinsi,
                    'kode_kabupaten': int(kode_kabupaten),
                    'nama_kabupaten': nama_kabupaten
                })
        else:
            print(f"Failed to fetch kabupaten data for province {kode_provinsi}: {response_kabupaten.status_code}")
    
    # Write the result to a file in the script's directory
    with open(output_file, 'w', encoding='utf-8') as f:
        json.dump(all_results, f, ensure_ascii=False, indent=4)
        
    print(f"Data has been written to {output_file}")
else:
    print(f"Failed to fetch province data: {response_provinces.status_code}")
