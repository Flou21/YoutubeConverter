#!/bin/bash

working_dir="."



# check if download directory exists if not create it


if [ -d "$working_dir/youtube" ]; then
    # folder already exists
    echo ""
else
    cd $working_dir && mkdir youtube
fi

# build youtube-dl command
options=""
if [ $2 = "audio" ]; then
    options="-x"
fi

if [ $2 = "video" ]; then
    options="-f 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4'"
fi

# execute youtube-dl
command="youtube-dl ${options} $1"
eval $command

# move the generated file
eval "mv *.mp4 *.mp3 ./youtube/"

eval "mv \"youtube/$(ls -Art youtube | tail -n 1)\" \"youtube/$3\""