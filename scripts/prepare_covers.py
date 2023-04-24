########################################################
# Python Cover Crop                                    #
# by MrTimscampi                                       #
########################################################
# v0.1 (07/19/2011) - Initial release                  #
# v0.2 (07/20/2011) - Added 20 presets                 #
# v0.3 (11/03/2022)                                    #
# - Port to Python 3                                   #
# - Removed all presets                                #
# - Handle files in a flat folder                      #
# - Better handle various cover dimensions             #
########################################################
# Released under GNU GPL v3                            #
# http://www.gnu.org/licenses/gpl-3.0.html             #
########################################################

# Imports
from PIL import Image
import os

# Settings
debugmode = 1  # Value of 0 = OFF and 1 = ON; Show useful debug messages during processing

# List of non-processed files
nonprocessed = []

# List all .jpg files with same name as directory
for dirname, dirnames, filenames in os.walk('.'):
    for filename in filenames:
        # Skip files with -front in their name
        if "-front" in filename:
            continue

        # Process all files matching the pattern <dirname>.jpg
        print("Processing " + os.path.join(dirname, filename))
        filepath = filename
        image = Image.open(filepath)

        # Get the image dimensions
        width, height = image.size
        # Show image dimensions if debug is ON
        if debugmode == 1:
            print("  Debug: Width:", width, "| Height:", height)

        ratio = width / height
        print("Ratio:", ratio)
        match ratio:
            case _ if 0 < ratio <= 0.75:
                # DVD - Front (w/h = 0.70)
                print("Detected: DVD - Front")
                nonprocessed.append(filepath + " (dvd front)")
                continue
            case _ if 0.75 < ratio <= 1:
                # Blu-ray - Front (w/h = 0.85)
                print("Detected: Blu-ray - Front")
                nonprocessed.append(filepath + " (blu-ray front)")
                continue
            case _ if 1.32 < ratio <= 1.34:
                # DVD - Blank padded (w/h = 1.33)
                print("Detected: DVD - Blank padded, skipping")
                nonprocessed.append(filepath + " (blank padded)")
                continue
            case _ if 1.40 < ratio <= 1.42:
                # DVD - No Spine (w/h = 1.41)
                print("Detected: DVD - No Spine")
                croppedwidth = round(width * 0.498)
                pass
            case _ if 1.43 < ratio <= 1.465:
                # DVD - Slim (w/h = 1.45)
                print("Detected: DVD - Slim")
                croppedwidth = round(width * 0.484)
                pass
            case _ if 1.465 < ratio <= 1.60:
                # DVD - Normal (w/h = 1.49)
                # 1.60 is a bit much, but some covers push it really far at 1.58 for a normal size case...
                print("Detected: DVD - Normal")
                croppedwidth = round(width * 0.472)
                pass
            case _ if 1.60 < ratio <= 1.64:
                # Special Boxset - Thin (w/h = 1.62)
                print("Detected: Special Boxset - Thin")
                croppedwidth = round(width * 0.452)
                pass
            case _ if 1.64 < ratio <= 1.67:
                # Special Boxset - Fat (w/h = 1.66)
                print("Detected: Special Boxset - Fat")
                croppedwidth = round(width * 0.424)
                pass
            case _ if 1.72 < ratio <= 1.74:
                # Blu-ray - 5mm spine (w/h = 1.73)
                print("Detected: Blu-ray - 5mm spine")
                croppedwidth = round(width * 0.49)
                pass
            case _ if 1.75 < ratio <= 1.785:
                # Blu-ray - 12mm spine (w/h = 1.78)
                print("Detected: Blu-ray - 12mm spine")
                croppedwidth = round(width * 0.477)
                pass
            case _ if 1.785 < ratio <= 1.83:
                # Blu-ray - 14mm spine (w/h = 1.79)
                print("Detected: Blu-ray - 14mm spine")
                croppedwidth = round(width * 0.473)
                pass
            case _ if 1.83 < ratio <= 1.88:
                # Blu-ray - 24mm spine (w/h = 1.86)
                print("Detected: Blu-ray - 24mm spine")
                croppedwidth = round(width * 0.456)
                pass
            case _:
                print("Failed to detect cover type")
                # Don't touch this, we don't know what it is
                nonprocessed.append(filepath + " (unknown)")
                continue

        croptupple = [(width - croppedwidth), 0, width, height]
        if debugmode == 1:
            print("  Debug: cropping dimensions:", croptupple)
        croppedimage = image.convert('RGB').crop(croptupple)
        if debugmode == 1:
            print("  Debug: cropped successfully !")

        basename = os.path.splitext(os.path.basename(filepath))[0]
        extension = os.path.splitext(os.path.basename(filepath))[1]

        filename = basename + "-front" + extension

        croppedimage.save(filename)
        if debugmode == 1:
            print("  Debug: " + filename + " saved !")

print("Process complete")

# Print list of non-processed files
if len(nonprocessed) > 0:
    # Sort the list
    nonprocessed.sort()

    print("The following files were not processed:")
    for i in nonprocessed:
        print("  " + i)
