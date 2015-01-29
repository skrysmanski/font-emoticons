#!/bin/sh

SOURCES="font *.css *.php readme.txt"
DEST_DIR="dist"

rm -rf $DEST_DIR

mkdir $DEST_DIR

rsync --recursive --human-readable --times --delete $SOURCES $DEST_DIR/
