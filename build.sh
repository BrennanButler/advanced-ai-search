#!/bin/bash

# First build the AdminApp
echo "Building AdminApp..."

cd ./src/Admin/AdminApp/ && npm run build && cd ../../../

echo "Building commercial algolia application..."

npm run build

echo "Combining asset files for admin scripts..."

# Input PHP file locations
file1="./build/admin.asset.php"
file2="./src/Admin/AdminApp/build/adminApp.asset.php"
output_file="./build/admin.asset.php"

# Validate input files
if [ ! -f "$file1" ]; then
  echo "Error: File $file1 not found!"
  exit 1
fi

if [ ! -f "$file2" ]; then
  echo "Error: File $file2 not found!"
  exit 1
fi

# Extract dependencies and version from the first file
version1=$(php -r "\$config = include('$file1'); echo \$config['version'];")
dependencies1=$(php -r "\$config = include('$file1'); echo json_encode(\$config['dependencies']);")

# Extract dependencies from the second file (only dependencies will be merged)
dependencies2=$(php -r "\$config = include('$file2'); echo json_encode(\$config['dependencies']);")

# Merge dependencies (we assume that they are simple arrays of strings or values)
merged_dependencies=$(php -r "
\$deps1 = json_decode('$dependencies1');
\$deps2 = json_decode('$dependencies2');
\$merged = array_merge(\$deps1, \$deps2);
\$merged = array_unique(\$merged);
\$merged = array_map(function(\$value){
                return '\'' . \$value . '\'';
            }, \$merged);
echo implode(', ',\$merged);
")

# Create the combined output array (using version from file1)
combined_array="'dependencies' => array( $merged_dependencies ), 'version' => '$version1'"

# Output combined array to the specified file
echo "<?php return array( $combined_array );" > "$output_file"

echo "Build complete."

