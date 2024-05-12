#!/bin/sh -e

CACHE_DIR=docker/build-cache
DIR=/tmp/build
LIST=$(dirname $0)/docker-dependencies.list

if [ -d "$CACHE_DIR" ]; then
    DIR=$CACHE_DIR
fi

if [ -d "$1" ]; then
    DIR=${1%/}
fi

if [ -f "$2" ]; then
    LIST=$2
fi

while read line; do
  line=$(echo $line) # = trim($line)
  # skip empty lines and comments
  case "$line" in
      ""|\#*)
        continue
        ;;
  esac
  url=$(echo "$line"|cut -d " " -f1)
  filename=$(basename "$url")
  checksum=$(echo "$line"|cut -d " " -f2)
  label=$(echo $(echo "$line"|cut -d "#" -f2)) # use echo twice to trim leading whitespace
  if [ "$label" != "" ]; then
      label=" ($label)"
  fi
  if [ ! -f "$DIR/$filename" ]; then
    echo "downloading resource $filename$label"
    curl -L -o "$DIR/$filename" "$url"
  fi
  echo -n "sha256sum: "
  echo "$checksum  $DIR/$filename" | sha256sum -c -
done < "$LIST"
