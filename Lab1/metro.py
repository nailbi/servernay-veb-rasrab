n = int(input())

intervals = []
for _ in range(n):
    a, b = map(int, input().split())
    intervals.append((a, b))

t = int(input())

count = 0
for a, b in intervals:
    if a <= t <= b:
        count += 1

print(count)
