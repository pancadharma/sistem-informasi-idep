import json
import os

script_dir = os.path.dirname(os.path.abspath(__file__))
kec_data_path = os.path.join(script_dir, 'kec.json')

with open(kec_data_path, 'r') as json_file:
    kecamatan_data = json.load(json_file)

def transform_kode_to_id(kabupaten_list):
    transformed_list = []
    for item in kabupaten_list:
        id_val = item["id"]
        kode = item["kode"]
        nama = item["nama"]
        kabupaten_id = item["kabupaten_id"]
        aktif = item["aktif"]
        transformed_item = [
            f'"id" => {id_val}',
            f'"kode" => "{kode}"',
            f'"nama" => "{nama}"',
            f'"aktif" => {aktif}',
            f'"kabupaten_id" => {kabupaten_id}'
        ]
        transformed_list.append(transformed_item)
    return transformed_list

transformed_kabupaten = transform_kode_to_id(kecamatan_data)

# Or, if you want to write the output in the specified format:
output = "[\n"
for item in transformed_kabupaten:
    output += "    [\n"
    for field in item:
        output += f"        {field},\n"
    output += "    ],\n"
output += "]"

with open('kec.txt', 'w') as file:
    file.write(output)

print("Data has been written to kec.txt")
