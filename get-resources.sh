#!/bin/sh -e

while read line; do
  line=$(echo $line) # = trim($line)
  # skip empty lines and comments
  if [ "$line" = "" ] || [ ${line:0:1} = "#" ]; then
    continue
  fi
  filename=$(echo "$line"|cut -d " " -f1)
  url=$(echo "$line"|cut -d " " -f2)
  checksum=$(echo "$line"|cut -d " " -f3)
  label=$(echo $(echo "$line"|cut -d "#" -f2)) # use echo twice to trim leading whitespace
  if [ "$label" != "" ]; then
      label=" ($label)"
  fi
  if [ ! -f "/tmp/build/$filename" ]; then
    echo "downloading resource $filename$label"
    curl -L -o "/tmp/build/$filename" "$url"
  fi
  echo -n "sha256sum: "
  echo "$checksum  /tmp/build/$filename" | sha256sum -c -
done < /tmp/build/resources.list
