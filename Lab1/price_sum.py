import csv

adult = 0.0
pensioner = 0.0
child = 0.0

with open('products.csv', 'r', encoding='utf-8') as f:
    reader = csv.reader(f)
    next(reader)  # пропускаем заголовок
    for row in reader:
        if not row:
            continue
        adult += float(row[1])
        pensioner += float(row[2])
        child += float(row[3])

print(f'{round(adult, 2)} {round(pensioner, 2)} {round(child, 2)}')
