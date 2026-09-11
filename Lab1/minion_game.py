s = input()

vowels = 'AEIOU'
length = len(s)

kevin = 0
stuart = 0

for i in range(length):
    if s[i] in vowels:
        kevin += length - i
    else:
        stuart += length - i

if stuart > kevin:
    print('Стюарт', stuart)
elif kevin > stuart:
    print('Кевин', kevin)
else:
    print('Draw')
