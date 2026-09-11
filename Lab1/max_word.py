with open('example.txt', 'r', encoding='utf-8') as f:
    text = f.read()

words = []
for token in text.split():
    cleaned = ''.join(ch for ch in token if ch.isalpha())
    if cleaned:
        words.append(cleaned)

max_len = max(len(word) for word in words)

seen = set()
for word in words:
    if len(word) == max_len and word not in seen:
        seen.add(word)
        print(word)
