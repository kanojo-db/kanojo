import json
import os

# Load the data from the JSON file
with open('movies_jav.json', encoding="utf-8") as f:
    jav = json.load(f)

# Load the models
with open('people.json', encoding="utf-8") as f:
    models = json.load(f)

# Iterate over the movies
for movie in jav:
    # Find if there is a person with the same name
    is_found = False

    for model in models:
        for name in movie['models']:
            if model['name_jp'] == name:
                print('Found: ' + name)
                is_found = True
                break

            # If there is no person with the same name, add the model to the people
            if not is_found:
                for name in movie['models']:
                    print('Not found: ' + name)
                    models.append({
                        'name_jp': name,
                        'name_en': '',
                        'birthdate': '',
                    })

# Save the data to the JSON file
print('Saving...')
with open('people.json', 'w', encoding="utf-8") as f:
    json.dump(jav, f, indent=4, ensure_ascii=False)
