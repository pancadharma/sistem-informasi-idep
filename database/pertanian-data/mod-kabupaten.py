import json
import os

# Get the directory where the script is located
script_dir = os.path.dirname(os.path.abspath(__file__))

# Define input and output file paths
input_file_path = os.path.join(script_dir, 'transformed_kabupaten.json')
output_file_path = os.path.join(script_dir, 'kab.php')

# Read the data from the input file
with open(input_file_path, 'r') as file:
    data = json.load(file)

# Transform the data
transformed_data = []
for entry in data:
    new_entry = {
        "kode": f"{entry['kode_provinsi']}.{entry['kode_kabupaten']}",
        "nama": entry["nama_kabupaten"],
        "provinsi_id": entry["kode_provinsi"],
        "aktif": 1,
    }
    transformed_data.append(new_entry)

# Prepare the output for Laravel seeder
output_data = transformed_data

# Print the transformed data
print(json.dumps(output_data, indent=4))

# Write the transformed data to the output file
with open(output_file_path, 'w') as file:
    json.dump(output_data, file, indent=4)
