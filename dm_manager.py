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

# Удаление таблиц
tables_to_drop = ["yourls_url_utm", "yourls_utm_log"]

for table in tables_to_drop:
    cursor.execute(f"DROP TABLE IF EXISTS {table};")
    print(f"Таблица {table} удалена (если существовала)")

conn.commit()
cursor.close()
conn.close()
