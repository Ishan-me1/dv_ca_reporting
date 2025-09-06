import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import classification_report, accuracy_score
import joblib

# Load the dataset
data = pd.read_csv("crimes_against_women_2001-2014...csv")

# Drop unnecessary columns
data.drop(columns=['STATE/UT', 'DISTRICT', 'Year'], inplace=True)

# Encode Risk Level
le = LabelEncoder()
data['Risk Level'] = le.fit_transform(data['Risk Level'])

# TF-IDF for complain text
vectorizer = TfidfVectorizer(stop_words='english')
X = vectorizer.fit_transform(data['Complain'])
y = data['Risk Level']

# Train-Test split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Model
model = RandomForestClassifier(n_estimators=100, random_state=42)
model.fit(X_train, y_train)

# Predictions
y_pred = model.predict(X_test)

# Evaluation
print("Accuracy:", accuracy_score(y_test, y_pred))
print("Classification Report:\n", classification_report(y_test, y_pred, target_names=le.classes_))

# Save the model and vectorizer + encoder
joblib.dump(model, "dv_model.pkl")
joblib.dump(vectorizer, "tfidf_vectorizer.pkl")
joblib.dump(le, "label_encoder.pkl")

print("Model, Vectorizer, and Encoder saved successfully!")
