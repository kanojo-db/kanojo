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
for movie in movies_u18:
    # Make title field with title_en and title_jp
    movie['title'] = {
        'en': movie['title_en'],
        'jp': movie['title_jp']
    }
    # Delete title_en and title_jp
    del movie['title_en']
    del movie['title_jp']

# Iterate over movies_iv
for movie in movies_iv:
    # Make title field with title_en and title_jp
    movie['title'] = {
        'en': movie['title_en'],
        'jp': movie['title_jp']
    }
    # Delete title_en and title_jp
    del movie['title_en']
    del movie['title_jp']

# Iterate over movies_jav
for movie in movies_jav:
    # Make title field with title_en and title_jp
    movie['title'] = {
        'en': movie['title_en'],
        'jp': movie['title_jp']
    }
    # Delete title_en and title_jp
    del movie['title_en']
    del movie['title_jp']

# Iterate over people
for person in people:
    # Make title field with title_en and title_jp
    person['name'] = {
        'en': person['name_en'],
        'jp': person['name_jp']
    }
    # Delete title_en and title_jp
    del person['name_en']
    del person['name_jp']

# Write movies_u18.json
with open('movies_u18.json', 'w', encoding="utf-8") as f:
    json.dump(movies_u18, f, indent=2)

# Write movies_iv.json
with open('movies_iv.json', 'w', encoding="utf-8") as f:
    json.dump(movies_iv, f, indent=2)

# Write movies_jav.json
with open('movies_jav.json', 'w', encoding="utf-8") as f:
    json.dump(movies_jav, f, indent=2)
