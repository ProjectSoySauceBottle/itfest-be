from flask import Flask, jsonify
import requests
import pandas as pd

app = Flask(__name__)

LARAVEL_API = 'http://localhost:8000/api/statistik/menu'

@app.route('/rekomendasi', methods=['GET'])
def rekomendasi():
    print("Flask: Rekomendasi diminta...")
    try:
        response = requests.get(LARAVEL_API)
        data = response.json()
        df = pd.DataFrame(data)

        # Sort berdasarkan total_terjual
        df_sorted = df.sort_values(by='total_terjual', ascending=False)

        # Ambil 5 menu paling laris
        rekomendasi_menu_ids = df_sorted.head(5)['menu_id'].tolist()

        return jsonify({
            'rekomendasi_menu_id': rekomendasi_menu_ids
        })

    except Exception as e:
        return jsonify({ 'error': str(e) }), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)

