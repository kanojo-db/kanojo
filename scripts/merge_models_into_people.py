import json

# Load the data from the JSON file
with open('people.json', encoding="utf-8") as f:
    data = json.load(f)

# Load the U18 models
with open('models_u18.json', encoding="utf-8") as f:
    models_u18 = json.load(f)

# Iterate over the U18 models
for model in models_u18:
    # Find if there is a person with the same name
    is_found = False

    for person in data:
        if person['name_jp'] == model:
            print('Found: ' + model)
            is_found = True
            break

    # If there is no person with the same name, add the model to the people
    if not is_found:
        print('Not found: ' + model)
        data.append({
            'name_jp': model,
            'name_en': '',
            'birthdate': '',
        })

# Save the data to the JSON file
print('Saving...')
with open('people.json', 'w', encoding="utf-8") as f:
    json.dump(data, f, indent=4, ensure_ascii=False)
