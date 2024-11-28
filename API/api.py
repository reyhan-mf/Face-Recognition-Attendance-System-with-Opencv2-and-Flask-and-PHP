
from flask import Flask, request, jsonify
import cv2
import face_recognition
import tensorflow as tf
import os
from cs50 import SQL
import io
from PIL import Image
import numpy as np
import datetime

# Initialize the Flask app
app = Flask(__name__)

# Initialize the SQLite database connection
db = SQL("mysql://root:@localhost/absensi_face_recognition")

# Define an endpoint for face recognition
@app.route('/predict', methods=['POST'])
def predict():
        # Validate and parse the user ID
        user_id = request.form.get('user_id')
        print(user_id)
        if not user_id:
            return jsonify({"error": "User ID is required"}), 400

        # Get user data from the database for specific user
        getUser = db.execute('''
            SELECT s.student_picture, s.nis, s.nama 
            FROM siswa s 
            INNER JOIN kelas k ON s.kelas_id = k.kelas_id 
            WHERE s.user_id = ?
        ''', user_id)

        # Prepare known face encodings and names
        known_face_encodings = []
        known_face_names = []

        for row in getUser:
            employee_id = row["nis"]
            name = row["nama"]
            image_data = row["student_picture"]
            
            # Skip if no image data
            if not image_data:
                continue

            image_stream = io.BytesIO(image_data)
            known_person_image = Image.open(image_stream).convert("RGB")
            known_person_image_np = np.array(known_person_image)
            
            # Handle potential face encoding errors
            face_encodings = face_recognition.face_encodings(known_person_image_np)
            if not face_encodings:
                continue

            known_person_encoding = face_encodings[0]
            known_face_encodings.append(known_person_encoding)
            known_face_names.append(name)

        # Validate and parse the uploaded image
        if 'image' not in request.files:
            return jsonify({"error": "No image file found"}), 400

        image_file = request.files['image']
        image = Image.open(image_file).convert("RGB")
        frame = np.array(image)

        # Detect face locations
        face_locations = face_recognition.face_locations(frame)

        if not face_locations:
            return jsonify({"error": "No faces detected"}), 404

        # Use GPU for face encoding if available
        with tf.device('/GPU:0'):
            face_encodings = face_recognition.face_encodings(frame, face_locations)

        # Enable TensorFlow memory growth for GPU
        physical_devices = tf.config.list_physical_devices('GPU')
        if physical_devices:
            tf.config.experimental.set_memory_growth(physical_devices[0], True)

        predictions = []
        for (top, right, bottom, left), face_encoding in zip(face_locations, face_encodings):
            # Use face distance for more accurate matching
            face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
            
            # Set a threshold for face recognition confidence
            best_match_index = np.argmin(face_distances)
            
            if face_distances[best_match_index] < 0.6:
                name = known_face_names[best_match_index]
                confidence = 1 - face_distances[best_match_index]
            else:
                name = "Unknown"
                confidence = 0

            # Log attendance if recognized
            if name != "Unknown":
                current_time = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
                try:
                    db.execute("""
                        INSERT INTO attendance (nama, user_id, timestamp) 
                        VALUES (?, ?, ?)
                    """, name, user_id, current_time)
                except Exception as log_error:
                    print(f"Error logging attendance: {log_error}")

            predictions.append({
                "name": name,
                "confidence": float(confidence),
                "location": {
                    "top": top,
                    "right": right,
                    "bottom": bottom,
                    "left": left
                }
            })

        return jsonify({"predictions": predictions})


# Run the app
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)