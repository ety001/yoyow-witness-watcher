FROM ubuntu:16.04
Workdir /data
ENV WSAPI "wss://wallet.yoyow.org/ws"
RUN apt-get update && apt-get install -y ca-certificates && apt-get clean
CMD /data/yoyow_client -s ${WSAPI} -w /data/wallet.json -H 0.0.0.0:9999
