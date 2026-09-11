n = int(input())

a = [list(map(int, input().split())) for _ in range(n)]
b = [list(map(int, input().split())) for _ in range(n)]

result = [[0] * n for _ in range(n)]
for i in range(n):
    for j in range(n):
        s = 0
        for k in range(n):
            s += a[i][k] * b[k][j]
        result[i][j] = s

for row in result:
    print(' '.join(map(str, row)))
