def read_data_from_file(filename):
    with open(filename, 'r') as file:
        # Read the file content and remove surrounding parentheses
        file_content = file.read().strip().strip('()')
        
        # Split the content by '),(' to get each tuple separately
        lines = file_content.split('),(')
        
    data = []
    for line in lines:
        # Split the line by commas, taking care to not split within quotes
        parts = line.split(',')
        
        # Convert the parts into the appropriate types and remove quotes
        id = int(parts[0].strip())
        name = parts[1].strip().strip("'")
        iso1 = parts[2].strip().strip("'")
        iso2 = parts[3].strip().strip("'")
        flag = parts[4].strip().strip("'").strip(')')
        
        data.append((id, name, iso1, iso2, flag))
    return data

def convert_to_php_array(data):
    php_array = "$countries = [\n"
    for entry in data:
        php_array += f"    ['id' => {entry[0]}, 'nama' => '{entry[1]}', 'iso1' => '{entry[2]}', 'iso2' => '{entry[3]}', 'flag' => '{entry[4]}'],\n"
    php_array += "];"
    return php_array

def write_php_array_to_file(php_array, filename):
    with open(filename, 'w') as file:
        file.write(php_array)

# File paths
input_file = 'data.txt'
output_file = 'countries.php'

# Read the data
data = read_data_from_file(input_file)

# Convert the data to PHP array format
php_array = convert_to_php_array(data)

# Write the PHP array to a file
write_php_array_to_file(php_array, output_file)

print(f"PHP array written to {output_file}")
