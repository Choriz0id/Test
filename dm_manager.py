import mysql.connector

# Подключение к базе данных
conn = mysql.connector.connect(
    host="nozomi.proxy.rlwy.net",
    port=37244,
    user="root",
    password="jIHoLbyEuHNKuaNlVUUbDhEawiBXThVq",
    database="railway"
)

cursor = conn.cursor()

# Создание таблицы для MySQL
create_table_query = """
CREATE TABLE IF NOT EXISTS yourls_utm_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    keyword VARCHAR(255) NOT NULL,
    utm_data TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
"""

cursor.execute(create_table_query)
conn.commit()

print("Таблица создана!")

cursor.close()
conn.close()
