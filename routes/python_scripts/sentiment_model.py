import sys
import tensorflow as tf
import numpy as np
from keras.preprocessing.sequence import pad_sequences
from keras.preprocessing.text import Tokenizer

# Path ke model yang disimpan
MODEL_PATH = 'path/to/sentiment_model.h5'

# Load model RNN
model = tf.keras.models.load_model(MODEL_PATH)

# Load tokenizer
with open('path/to/tokenizer.json') as f:
    tokenizer_data = f.read()

tokenizer = Tokenizer.from_json(tokenizer_data)

def preprocess_text(text):
    # Preprocessing teks (tokenisasi, padding, dll.)
    sequences = tokenizer.texts_to_sequences([text])
    padded = pad_sequences(sequences, maxlen=100)  # Pastikan maxlen sesuai model
    return padded

def predict_sentiment(text):
    processed_text = preprocess_text(text)
    prediction = model.predict(processed_text)
    sentiment = np.argmax(prediction)  # Indeks ke kategori (0=Negatif, 1=Netral, 2=Positif)
    sentiment_mapping = {0: 'Negatif', 1: 'Netral', 2: 'Positif'}
    return sentiment_mapping[sentiment]

if __name__ == "__main__":
    input_text = sys.argv[1]
    result = predict_sentiment(input_text)
    print(result)
