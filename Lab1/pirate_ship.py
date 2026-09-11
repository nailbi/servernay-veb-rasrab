def fmt(x):
    r = round(x, 2)
    if r == int(r):
        return str(int(r))
    return str(r)


first = input().split()
n = int(first[0])  # грузоподъёмность
m = int(first[1])  # количество наименований

items = []
for _ in range(m):
    parts = input().split()
    name = parts[0]
    weight = int(parts[1])
    value = int(parts[2])
    items.append((name, weight, value))

# Жадный алгоритм для дробного рюкзака: берём по убыванию удельной стоимости.
items.sort(key=lambda it: it[2] / it[1], reverse=True)

capacity = n
loaded = []
for name, weight, value in items:
    if capacity <= 0:
        break
    if weight <= capacity:
        loaded.append((name, weight, value))
        capacity -= weight
    else:
        fraction = capacity / weight
        loaded.append((name, capacity, value * fraction))
        capacity = 0

# Вывод в порядке убывания стоимости.
loaded.sort(key=lambda it: it[2], reverse=True)
for name, weight, value in loaded:
    print(f'{name} {fmt(weight)} {fmt(value)}')
