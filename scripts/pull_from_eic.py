#!/usr/bin/env python
# -*- coding: utf-8 -*-

from bs4 import BeautifulSoup
from datetime import date
from dateutil.relativedelta import relativedelta
import requests
import json
import os
import re


def clean_title(title):
    """Removes format information from the title."""
    replacement_map = {
        "　": " ",
        " BD": "",
        " [Blu-ray]": "",
        " [DVD]": "",
        " 【特価】": "",
        "　＊ｻｲﾝﾁｪｷ": "",
        "　＊ﾁｪｷ（大判）": "",
        "　＊大判ﾁｪｷ": "",
        "　＊生写真": "",
        " Blu-ray版": "",
        " DVD版": "",
        "【10%POINTBACK】": "",
        "【予約特典】": "",
        "【特典】": ""
    }
    substrings = sorted(replacement_map, key=lambda k: len(k), reverse=True)
    regexp = re.compile('|'.join(map(re.escape, substrings)))
    return regexp.sub(lambda match: replacement_map[match.group(0)], title)


poster_dir = "../dataase/data/covers/"
poster_out_dir = "new_posters"

# Ensure poster_out_dir exists
if not os.path.exists(poster_out_dir):
    os.makedirs(poster_out_dir)

eic_title_url_template = 'https://www.eic-book.com{}'

# Start by getting all the U18 titles
# U18 models search result page template URL
search_url_template = 'https://www.eic-book.com/search?sort=asc_sale_day&size=120&ym={0}&current_page={1}'

titles_urls = []

current_date = date.fromisocalendar(2022, 9, 1)
current_page = 1
while True:
    # Get the first page of results
    search_url = search_url_template.format(current_date.strftime('%Y-%m'), current_page)
    search_page = requests.get(search_url)
    search_soup = BeautifulSoup(search_page.content, 'html.parser')

    print("Processing date {0} page {1}".format(current_date.strftime('%Y-%m'), current_page))

    total_titles = 0
    # In div with class "list", find all the links with class "list"
    for title_link in search_soup.find('div', id='mainCon').find('div', class_='cmnList').find_all('div', class_='list'):
        # Get the URL for the title
        title_url = title_link.find('a').get('href')
        titles_urls.append(title_url)
        total_titles += 1

    print("Found {0} titles".format(total_titles))
    print("Total titles: {0}".format(len(titles_urls)))


    # Find a link with the text "次へ"
    next_link = search_soup.find('a', text='次へ')
    # If there is no next link, we're done
    if next_link is None:
        print("No more pages")

        # If we have no more pages and we're on the current month, we're done.
        if current_date.year == date.today().year and current_date.month == date.today().month:
            break

        # Otherwise, move to the next month
        current_date = current_date + relativedelta(months=1)
        current_page = 1
        continue

    # Increase the page counter
    current_page += 1

print("Found {0} titles".format(len(titles_urls)))

with open('movies_iv.json', encoding="utf8") as f:
    iv_json_data = json.load(f)

with open('movies_u18.json', encoding="utf8") as f:
    u18_json_data = json.load(f)

total_genres = []
total_models = []

# Iterate over all the titles
for title_url in titles_urls:
    # Get the title's page
    title_page = requests.get(eic_title_url_template.format(title_url))
    title_soup = BeautifulSoup(title_page.content, 'html.parser')

    title_info = {}
    is_u18 = False
    is_general = False

    # Get the title's name
    try:
        title = title_soup.find("div", {"id": "con20"}).find("h1").get_text()
    except AttributeError:
        title = title_soup.find("h1").get_text()

    title_info['title'] = clean_title(title)

    print("Processing title: {0}".format(title_info['title']))

    try:
        poster = title_soup.find(class_="dtMainPic").get('href')
        print("Found poster")
    except AttributeError:
        print("No poster found")
        # No poster exists
        poster = None

    try:
        info_table = title_soup.find("div", attrs={"id": "dtSpecR"}).find("table") \
            .find_all("tr")
    except AttributeError:
        print("No info table found, skipping")
        continue

    for row in info_table:
        header = row.find("th").get_text()

        match header:
            case "出演者":
                # Get the models
                models = []
                for model_link in row.find("td").find_all("a"):
                    model = model_link.get_text()
                    models.append(model)
                title_info['models'] = models
                total_models.extend(models)
            case "発売日":
                # Get the release date
                release_date = row.find("td").get_text()
                title_info['release_date'] = release_date
            case "メーカー":
                # Get the studio
                studio = row.find("td").get_text()
                title_info['studio'] = studio
            case "レーベル":
                # Get the label
                label = row.find("td").get_text()
                title_info['label'] = label
            case "収録時間":
                # Get the runtime
                runtime = row.find("td").get_text().replace("未定", "").replace(
                    "(予定)", "").replace("分予定", "").replace("分", "")
                if runtime == "":
                    runtime = None
                else:
                    try:
                        runtime = int(runtime)
                    except ValueError:
                        runtime = None
                title_info['runtime'] = runtime
            case "商品コード":
                # Get the product code
                product_code = row.find("td").get_text().split(" ")[0]
                title_info['product_code'] = product_code
            case "シリーズ":
                # Get the series
                series = row.find("td").get_text()
                title_info['series'] = series
            case "ジャンル":
                # Get the genres
                genres = []
                for genre_link in row.find("td").find_all("a"):

                    genre = genre_link.get_text()
                    genres.append(genre)

                # The genre "一般作" contains trash we don't want, so skip items with that genre
                if "一般作" in genres:
                    is_general = True

                title_info['genres'] = genres
                total_genres.extend(genres)

                # U18 titles have a "ティーンアイドル" genre
                if "ティーンアイドル" in genres:
                    is_u18 = True
            case _:
                continue

    # If it's a general title, skip it
    if is_general:
        continue

    # Check if the product code is already in the JSON file
    if is_u18:
        filtered_dict = [
            x for x in u18_json_data if x['product_code'] == title_info['product_code']]
    else:
        filtered_dict = [
            x for x in iv_json_data if x['product_code'] == title_info['product_code']]

    if len(filtered_dict) == 0:
        print("Adding {0}".format(title))
        # Add the movie to the JSON file
        if is_u18:
            u18_json_data.append({
                "product_code": title_info['product_code'],
                "release_date": title_info['release_date'],
                "title_jp": title_info['title'],
                "title_en": "",
                "studio": title_info['studio'],
                "label": title_info['label'],
                "runtime": title_info['runtime'],
                "series": title_info['series'],
                "models": title_info['models'],
                "genres": title_info['genres'],
            })
        else:
            iv_json_data.append({
                "product_code": title_info['product_code'],
                "release_date": title_info['release_date'],
                "title_jp": title_info['title'],
                "title_en": "",
                "studio": title_info['studio'],
                "label": title_info['label'],
                "runtime": title_info['runtime'],
                "series": title_info['series'],
                "models": title_info['models'],
                "genres": title_info['genres'],
            })
    else:
        print("Checking {0}".format(title_info['product_code']))
        # Find the movie in the JSON file
        if is_u18:
            for index, movie in enumerate(u18_json_data):
                if movie['product_code'] == title_info['product_code']:
                    print("Updating {0}".format(movie['title_jp']))
                    # If any of the fields are empty or missing, add them
                    if movie['title_jp'] == "":
                        u18_json_data[index]['title_jp'] = title_info['title']
                    if movie['models'] == []:
                        u18_json_data[index]['models'] = title_info['models']
                    if movie['release_date'] == "":
                        u18_json_data[index]['release_date'] = title_info['release_date']
                    if movie['studio'] == "":
                        u18_json_data[index]['studio'] = title_info['studio']
                    if movie['label'] == "":
                        u18_json_data[index]['label'] = title_info['label']
                    if movie['runtime'] == "":
                        u18_json_data[index]['runtime'] = title_info['runtime']
                    if movie['series'] == "":
                        u18_json_data[index]['series'] = title_info['series']
                    if movie['genres'] == []:
                        u18_json_data[index]['genres'] = title_info['genres']

                break
        else:
            for index, movie in enumerate(iv_json_data):
                if movie['product_code'] == title_info['product_code']:
                    print("Updating {0}".format(movie['title_jp']))
                    # If any of the fields are empty or missing, add them
                    if movie['title_jp'] == "":
                        iv_json_data[index]['title_jp'] = title_info['title']
                    if movie['models'] == []:
                        iv_json_data[index]['models'] = title_info['models']
                    if movie['release_date'] == "":
                        iv_json_data[index]['release_date'] = title_info['release_date']
                    if movie['studio'] == "":
                        iv_json_data[index]['studio'] = title_info['studio']
                    if movie['label'] == "":
                        iv_json_data[index]['label'] = title_info['label']
                    if movie['runtime'] == "":
                        iv_json_data[index]['runtime'] = title_info['runtime']
                    if movie['series'] == "":
                        iv_json_data[index]['series'] = title_info['series']
                    if movie['genres'] == []:
                        iv_json_data[index]['genres'] = title_info['genres']

                break

    # Check if the poster exists
    if poster is not None:
        # Get the poster's filename
        poster_filename = title_info['product_code'] + ".jpg"
        # Check if the poster exists
        if not os.path.exists(os.path.join(poster_dir, poster_filename)):
            print("Downloading poster for {0}".format(title_info['product_code']))
            # Download the poster
            poster_file = requests.get(poster)
            with open(os.path.join(poster_out_dir, poster_filename), 'wb') as f:
                f.write(poster_file.content)

    # Write the U18 JSON file
    with open('movies_u18.json', 'w', encoding="utf8") as outfile:
        json.dump(u18_json_data, outfile, indent=4, ensure_ascii=False)

    # Write the IV JSON file
    with open('movies_iv.json', 'w', encoding="utf8") as outfile:
        json.dump(iv_json_data, outfile, indent=4, ensure_ascii=False)

    # Write the genres JSON
    with open('genres_u18.json', 'w', encoding="utf8") as outfile:
        json.dump(list(set(total_genres)), outfile, indent=4, ensure_ascii=False)

    # Write the models JSON
    with open('models_u18.json', 'w', encoding="utf8") as outfile:
        json.dump(list(set(total_models)), outfile, indent=4, ensure_ascii=False)
