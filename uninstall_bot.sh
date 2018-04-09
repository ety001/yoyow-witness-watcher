#!/bin/bash
echo "stop bot container"
docker stop yoyow_witness_watcher
echo "remove bot container"
docker rm yoyow_witness_watcher
echo "remove bot watcher image"
docker rmi yoyow_witness_watcher
