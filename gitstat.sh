#!/bin/bash

echo -n "Total commits: "
git rev-list --all --count
echo "Per user: "
git shortlog -s -n
