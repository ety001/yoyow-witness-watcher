#!/bin/bash
echo "stop container"
docker stop yoyow_client
echo "remove container"
docker rm yoyow_client
echo "remove docker image"
docker rmi yoyow_client
echo "remove custom network"
docker network rm yoyow
