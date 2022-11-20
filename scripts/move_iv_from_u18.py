# Moves titles with product codes listed in iv_titles.json from movies_u18.json to movies_iv.json
# The JJIGCDB had some IV titles listed in it, so move them to the IV JSON file

import json

with open("movies_u18.json", "r", encoding="utf-8") as f:
    u18_json = json.load(f)

with open("movies_iv.json", "r", encoding="utf-8") as f:
    iv_json = json.load(f)

with open("iv_titles.json", "r", encoding="utf-8") as f:
    iv_titles = json.load(f)

for title in u18_json:
    if title["product_code"] in iv_titles:
        iv_json.append(title)

        # Find the index of the title in the U18 JSON file and remove it
        index = u18_json.index(title)
        print("Removing " + title["product_code"] + " from U18 JSON file at index " + str(index))
        u18_json.pop(index)

genres_to_move = ["女子高生", "ティーンアイドル"]
genres_to_remove = ["アニメ", "一般作"]
genres_to_clean = [
    "EIC-BOOK独占DL商品",
    "セクシー女優",
    "復刻版",
    "Blu-ray（ブルーレイ）",
    "EIC-BOOK限定DL商品",
    "フルHD画質",
    "高画質DL対応",
    "配信限定",
    "ハイビジョン画質"
]

# If a product_code in iv_json is also in u18_json, remove it from iv_json
u18_product_codes = [title["product_code"] for title in u18_json]
for title in iv_json:
    if title["product_code"] in u18_product_codes:
        index = iv_json.index(title)
        print("Removing " + title["product_code"] + " from IV JSON file at index " + str(index))
        iv_json.pop(index)
        continue

    # Since the rest needs a genre key to work, skip if it doesn't exist
    if "genres" not in title:
        continue

    # If a title in iv_json has "女子高生" in its genres, move it to u18_json
    if any(genre in title["genres"] for genre in genres_to_move):
        u18_json.append(title)
        index = iv_json.index(title)
        print("Moving " + title["product_code"] + " to U18 JSON file at index " + str(index))
        iv_json.pop(index)
        continue

    # If the title has a genre from genres_to_remove, remove it from iv_json
    if any(genre in title["genres"] for genre in genres_to_remove):
        index = iv_json.index(title)
        print("Removing " + title["product_code"] + " from IV JSON file at index " + str(index))
        iv_json.pop(index)
        continue

# If an IV title has a genre from genres_to_clean, remove it from the title's genres
for title in iv_json:
    for genre in genres_to_clean:
        # Skip if we don't have a genres key
        if "genres" not in title:
            continue

        if genre in title["genres"]:
            title["genres"].remove(genre)

# If a U18 title has a genre from genres_to_clean, remove it from the title's genres
for title in u18_json:
    for genre in genres_to_clean:
        # Skip if we don't have a genres key
        if "genres" not in title:
            continue

        if genre in title["genres"]:
            title["genres"].remove(genre)

# Dedupe U18 titles based on product_code
print("Deduping U18 titles...")
u18_seen = set()
new_u18_json = []
for title in u18_json:
    if title["product_code"] not in u18_seen:
        new_u18_json.append(title)
        u18_seen.add(title["product_code"])

# Dedupe IV titles based on product_code
print("Deduping IV titles...")
iv_seen = set()
new_iv_json = []
for title in iv_json:
    if title["product_code"] not in iv_seen:
        new_iv_json.append(title)
        iv_seen.add(title["product_code"])

with open("movies_u18.json", "w", encoding="utf-8") as f:
    print("Writing U18 JSON file...")
    json.dump(new_u18_json, f, indent=4, ensure_ascii=False)

with open("movies_iv.json", "w", encoding="utf-8") as f:
    print("Writing IV JSON file...")
    json.dump(new_iv_json, f, indent=4, ensure_ascii=False)
