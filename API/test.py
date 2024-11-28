import requests
from cs50 import SQL
import os

# URL endpoint of the Flask API
url = "http://127.0.0.1:5000/predict"

# Connect to the database
db = SQL("mysql://root:@localhost/absensi_face_recognition")  # Default username: root, password: empty

# Get the ID dynamically (example: user input)
employee_id = int(input("Enter the Employee ID: "))

# Fetch the image for the given Employee ID
getImage = db.execute('''
 SELECT s.student_picture FROM siswa s 
                INNER JOIN kelas k ON s.kelas_id = k.kelas_id 
                WHERE s.user_id = ?;
''', employee_id)

print(getImage)

if not getImage:
    print(f"No image found for Employee ID {employee_id}.")
    exit()

# Extract the binary image data
image_data = getImage[0]["student_picture"]

# Write the binary data to a temporary file
temp_image_path = f"temp_image_{employee_id}.jpg"
with open(temp_image_path, "wb") as temp_file:
    temp_file.write(image_data)

# Send the image to the API
with open(temp_image_path, "rb") as image_file:
    files = {"image": image_file}
    data = {"user_id": str(employee_id)}
    response = requests.post(url, files=files, data=data)

# Handle the API response
if response.status_code == 200:
    print("Response:", response.json())
else:
    print("Error:", response.status_code, response.text)

# Clean up the temporary file
if os.path.exists(temp_image_path):
    os.remove(temp_image_path)
