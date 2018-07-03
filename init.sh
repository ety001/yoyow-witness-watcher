#!/bin/bash

BASE_PATH=`pwd`"/"
DATA_PATH="./data/"
YOYO_FILE_TGZ="yoyow_client.tgz"
YOYO_FILE="yoyow_client"

echo ""
echo "*********************************************"
echo "Welcome to use YOYOW witness watcher."
echo "This tool is made by ETY001. (https://github.com/ety001)"
echo "My YOYOW ID is 485699321. It's pleasure to get your votes!"
echo "*********************************************"
echo ""

echo "Please input current yoyow_client download URL (https://github.com/yoyow-org/yoyow-core/releases/latest):"
read DownloadURL

echo "Please input API url (Default: wss://wallet.yoyow.org/ws)"
read ApiURL

if  [ ! -n "$ApiURL" ] ;then
    ApiURL="wss://wallet.yoyow.org/ws"
fi

echo "Begin to download yoyow_client"
wget -O "${DATA_PATH}${YOYO_FILE_TGZ}" ${DownloadURL}

if [ ! -f "${DATA_PATH}${YOYO_FILE_TGZ}" ]
then
    echo ""
    echo "ERROR: ${YOYO_FILE_TGZ} not found."
    exit
fi

echo "Decompress..."
cd ./data && tar zxvf ${YOYO_FILE_TGZ} && cd ..

if [ ! -f "${DATA_PATH}${YOYO_FILE}" ]
then
    echo ""
    echo "ERROR: ${YOYO_FILE} not found."
fi

rm -f "${DATA_PATH}${YOYO_FILE_TGZ}"

echo ""
echo "Build docker image..."

docker build -t yoyow_client .

echo ""
echo "Create docker subnetwork"

docker network create -d bridge --subnet=172.20.99.0/24 --gateway=172.20.99.1 yoyow

echo ""
echo "Create wallet"

docker run -it --rm -e "WSAPI=${ApiURL}" -v ${BASE_PATH}${DATA_PATH}:/data --net yoyow --ip 172.20.99.2 yoyow_client

echo ""
echo "Run container"

docker run -dit --name yoyow_client --restart always -e "WSAPI=${ApiURL}" -v ${BASE_PATH}${DATA_PATH}:/data --net yoyow --ip 172.20.99.2 yoyow_client

echo ""
echo "Get Status"
sleep 10

docker ps | grep yoyow_client

echo ""
echo "Logs"

docker logs yoyow_client

echo ""
echo "************"
echo "Finish!"
