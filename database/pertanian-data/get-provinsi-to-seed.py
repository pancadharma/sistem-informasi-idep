import requests
import json
import os

# URL containing the JSON data
url = "https://sipedas.pertanian.go.id/api/wilayah/list_pro?thn=2024"

# Fetch the data from the URL
response = requests.get(url)

# Check if the request was successful
if response.status_code == 200:
    response_data = response.json()
    
    # Transform the data with an auto-incrementing id
    result = [
        {
            'id': index + 1,
            'kode': int(kode),
            'nama': nama
        }
        for index, (kode, nama) in enumerate(response_data.items())
    ]
    
    # Get the directory of the current .py file
    script_dir = os.path.dirname(os.path.abspath(__file__))
    
    # Define the output file path
    output_file = os.path.join(script_dir, 'data-provinsi.json')
    
    # Write the result to a file in the script's directory
    with open(output_file, 'w', encoding='utf-8') as f:
        json.dump(result, f, ensure_ascii=False, indent=4)
        
    print(f"Data has been written to {output_file}")
else:
    print(f"Failed to fetch data: {response.status_code}")
