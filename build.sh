#!/bin/sh

DIST_DIR="dist"

SOURCES="font *.css *.php readme.txt"
DEST_DIR="$DIST_DIR/trunk"

mkdir -p $DEST_DIR
rsync --recursive --human-readable --times --delete --exclude=.svn $SOURCES $DEST_DIR/

mkdir -p "$DIST_DIR/assets"
rsync --recursive --human-readable --times --delete --exclude=.svn "assets/" "$DIST_DIR/assets"

ZIP_DIR="$DIST_DIR/font-emoticons"
ZIP_FILE="font-emoticons.zip"
mkdir -p $ZIP_DIR
rsync --recursive --human-readable --times --delete $SOURCES $ZIP_DIR/
cd $DIST_DIR
rm $ZIP_FILE || true
zip -9 -r $ZIP_FILE "font-emoticons"
cd -
rm -rf "$ZIP_DIR"
