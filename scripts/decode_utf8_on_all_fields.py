import json
import os

# Open movies_u18.json
with open('movies_u18.json', 'r', encoding="utf-8") as f:
    movies_u18 = json.load(f)

# Open movies_iv.json
with open('movies_iv.json', 'r', encoding="utf-8") as f:
    movies_iv = json.load(f)

# Open movies_jav.json
with open('movies_jav.json', 'r', encoding="utf-8") as f:
    movies_jav = json.load(f)

# Open people.json
with open('people.json', 'r', encoding="utf-8") as f:
    people = json.load(f)

# Iterate over movies_u18
for movie in movies_jav:
    # Convert contents of studio field to utf-8
    movie['studio'] = movie['studio'].encode('utf-8').decode('utf-8')
    print(movie['studio'])
    # Convert contents of label field to utf-8
    movie['label'] = movie['label'].encode('utf-8').decode('utf-8')
    print(movie['label'])
    # Convert contents of series field to utf-8
    movie['series'] = movie['series'].encode('utf-8').decode('utf-8')
    print(movie['series'])
