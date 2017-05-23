#!/bin/bash

### copy commit-msg on current repository
cp commit-msg `git rev-parse --show-toplevel`/.git/hooks/

### copy .gitmessage.txt on home
cp .gitmessage.txt ~/.gitmessage.txt
git config --global commit.template ~/.gitmessage.txt