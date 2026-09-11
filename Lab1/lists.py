n = int(input())
arr = []

for _ in range(n):
    command = input().split()
    name = command[0]

    if name == 'insert':
        arr.insert(int(command[1]), int(command[2]))
    elif name == 'print':
        print(arr)
    elif name == 'remove':
        arr.remove(int(command[1]))
    elif name == 'append':
        arr.append(int(command[1]))
    elif name == 'sort':
        arr.sort()
    elif name == 'pop':
        arr.pop()
    elif name == 'reverse':
        arr.reverse()
