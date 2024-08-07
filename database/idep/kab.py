import json
import os

script_dir = os.path.dirname(os.path.abspath(__file__))
kab_data_path = os.path.join(script_dir, 'kab.json')

with open(kab_data_path, 'r') as json_file:
    kabupaten_data = json.load(json_file)

def transform_kabupaten(kabupaten_list):
    transformed_list = []
    for item in kabupaten_list:
        id_value = item["kode"].replace(".", "")[:4]
        kode_value = item["kode"]
        provinsi_id = item["kode"][:2]
        lat = item['latitude']
        long = item['longitude']
        type_value = item["type"]
        nama_value = item["nama"]
        kota = item['kota']
        path = item['path']
        aktif = 1
        transformed_item = [
            f'"id" => {id_value}',
            f'"kode" => "{kode_value}"',
            f'"type" => "{type_value}"',
            f'"nama" => "{nama_value}"',
            f'"kota" => "{kota}"',
            f'"latitude" => {lat}',
            f'"longitude" => {long}',
            f'"path" => "{path}"',
            f'"aktif" => {aktif}',
            f'"provinsi_id" => {provinsi_id}'
        ]
        transformed_list.append(transformed_item)
    return transformed_list

transformed_kabupaten = transform_kabupaten(kabupaten_data)

# Or, if you want to write the output in the specified format:
output = "[\n"
for item in transformed_kabupaten:
    output += "    [\n"
    for field in item:
        output += f"        {field},\n"
    output += "    ],\n"
output += "]"

with open('kab.txt', 'w') as file:
    file.write(output)

print("Data has been written to kab.txt")