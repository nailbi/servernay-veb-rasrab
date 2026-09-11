s = input()

result = []
for ch in s:
    if ch.isupper():
        result.append(ch.lower())
    elif ch.islower():
        result.append(ch.upper())
    else:
        result.append(ch)

print(''.join(result))
