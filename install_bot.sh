#!/bin/bash

echo "Please input YOYOW ID:"
read YOYOID

echo ""
echo ""
echo "Please input Your Public Key:"
echo "If you have multiple public keys, separate them with commas"
echo "eg. YYW7TSRLZ9EXZps37Kt31qa7qi,YYW7TSRLZ9EXZpZqk25atoL2s37"
read PUBKEYS

echo ""
echo ""
echo "Please input wallet password:"
read PASSWORD

echo ""
echo ""
echo "Please input Discord webhook for notify (If you don't need it, leave empty.):"
read WEBHOOK

cd src/ && docker build -t yoyow_witness_watcher .

docker run --name yoyow_witness_watcher -dit --net yoyow --restart always -e YOYOID=${YOYOID} -e PUBKEYS=${PUBKEYS} -e PASS=${PASSWORD} -e WEBHOOK=${WEBHOOK} yoyow_witness_watcher

echo ""
echo "Get status"
sleep 5

docker logs yoyow_witness_watcher

echo "***********"
echo "Finish!!"
