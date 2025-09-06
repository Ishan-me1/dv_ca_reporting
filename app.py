from flask import Flask, request, jsonify
import joblib

app = Flask(__name__)

# Load model and vectorizer
model = joblib.load("dv_model.pkl")
vectorizer = joblib.load("tfidf_vectorizer.pkl")
labels = {0: "High", 1: "Low", 2: "Medium"}  # Based on your LabelEncoder mapping

@app.route('/predict', methods=['POST'])
def predict():
    data = request.json
    complaint = data.get("complain")

    if not complaint:
        return jsonify({"error": "No complaint provided"}), 400

    # Transform and predict
    X = vectorizer.transform([complaint])
    prediction = model.predict(X)[0]

    return jsonify({"risk_level": labels[prediction]})

if __name__ == "__main__":
    app.run(port=5000, debug=True)
