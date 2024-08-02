import json
import os

script_dir = os.path.dirname(os.path.abspath(__file__))
data_provinsi = os.path.join(script_dir, 'provinsi.json')

with open(data_provinsi, 'r') as json_file:
    provinsi_data = json.load(json_file)

def transform_provinsi(provinsi_list):
    transformed_list = []
    for item in provinsi_list:
        id_value = item["id"]
        kode_value = item["kode"]
        nama_value = item["nama"]
        ibukota = item["kota"]
        latitude_val = item['latitude']
        longitude_val = item["longitude"]
        path = item["path"]
        aktif_val = 1
        transformed_item = [
            f'"id" => {id_value}',
            f'"kode" => {kode_value}',
            f'"nama" => "{nama_value}"',
            f'"kota" => "{ibukota}"',
            f'"latitude" => {latitude_val}',
            f'"longitude" => {longitude_val}',
            f'"path" => "{path}"',
            f'"aktif" => {aktif_val}'
        ]
        transformed_list.append(transformed_item)
    return transformed_list

# Transform the list
transformed_provinsi = transform_provinsi(provinsi_data)

# Prepare the output in the desired format
output = "[\n"
for item in transformed_provinsi:
    output += "    [\n"
    for field in item:
        output += f"        {field},\n"
    output += "    ],\n"
output += "]"

# Write the output to a file
with open('provinsi.txt', 'w') as file:
    file.write(output)

print("Data has been written to provinsi.txt")