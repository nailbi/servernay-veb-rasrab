n = int(input())

records = []
for _ in range(n):
    name = input()
    score = float(input())
    records.append([name, score])

grades = sorted(set(score for _, score in records))
second_lowest = grades[1]

names = sorted(name for name, score in records if score == second_lowest)
for name in names:
    print(name)
