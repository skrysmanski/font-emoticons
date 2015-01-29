#!/bin/sh

SOURCES="font *.css *.php readme.txt"
DEST_DIR="dist/font-emoticons"

rm -rf $DEST_DIR

mkdir -p $DEST_DIR

rsync --recursive --human-readable --times --delete $SOURCES $DEST_DIR/

zip -9 -r dist/font-emoticons.zip $DEST_DIR/
