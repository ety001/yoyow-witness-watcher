# YOYOW Witness Watcher

This is a watcher for YOYOW witness node.

# Prepare
* Linux server
* docker

# How to deploy

1. Clone the code

```
git clone https://github.com/ety001/yoyow-witness-watcher.git
cd yoyow-witness-watcher
```

2. Deploy yoyow_client by `init.sh`. You need input yoyow_client download URL.

```
root@vmi161488:/yoyow/yoyow-witness-watcher# ./init.sh 

*********************************************
Welcome to use YOYOW witness watcher.
This tool is made by ETY001. (https://github.com/ety001)
My YOYOW ID is 485699321. It's pleasure to get your votes!
*********************************************

Please input current yoyow_client download URL (https://github.com/yoyow-org/yoyow-core/releases/latest):
https://github.com/yoyow-org/yoyow-core/releases/download/v0.2.1-180313/yoyow-client-v0.2.1-ubuntu-20180313.tgz
```

When docker images built successful, the script will let you create a wallet.

```
Create wallet
Logging RPC to file: logs/rpc/rpc.log
3505859ms th_a       main.cpp:120                  main                 ] key_to_wif( committee_private_key ): 5KCBDTcyDqzsqehcb52tW5nU6pXife6V2rX9Yf7c3saYSzbDZ5W 
3505862ms th_a       main.cpp:124                  main                 ] nathan_pub_key: YYW6MRyAjQq8ud7hVNYcfnVPJqcVpscN5So8BhtHuGYqET5GDW5CV 
3505862ms th_a       main.cpp:125                  main                 ] key_to_wif( nathan_private_key ): 5KQwrPbwdL6PhXujxW37FSSQZ1JiwsST4cqQzDeyXtP79zkvFD3 
Starting a new wallet with chain ID ae4f234c75199f67e526c9478cf499dd6e94c2b66830ee5c58d0868a3179baf6 (from egenesis)
3505863ms th_a       main.cpp:172                  main                 ] wdata.ws_server: wss://wallet.yoyow.org/ws 
3506840ms th_a       main.cpp:177                  main                 ] wdata.ws_user:  wdata.ws_password:  
Please use the set_password method to initialize a new wallet before continuing
3509652ms th_a       main.cpp:243                  main                 ] Listening for incoming HTTP RPC requests on 0.0.0.0:9999
new >>> 
```

Set a password for wallet(eg. 123456)

```
set_password 123456
```

Unlock wallet

```
unlock 123456
```

Import your YOYOW account

```
import_key YOUR_YOYOW_ID  YOUR_YOYOW_ACTIVE_KEY
```

Press `ctrl + D` to exit wallet and the script will restart yoyow_client.

```
Get Status
00200e8bde55        yoyow_client                     "/bin/sh -c '/data/y…"    10 seconds ago      Up 10 seconds                                                  yoyow_client

Logs
Logging RPC to file: logs/rpc/rpc.log
140909ms th_a       main.cpp:120                  main                 ] key_to_wif( committee_private_key ): 5K**********************5W 
140910ms th_a       main.cpp:124                  main                 ] nathan_pub_key: YY************************CV 
140910ms th_a       main.cpp:125                  main                 ] key_to_wif( nathan_private_key ): 5****************************3 
140912ms th_a       main.cpp:172                  main                 ] wdata.ws_server: wss://wallet.yoyow.org/ws 
141820ms th_a       main.cpp:177                  main                 ] wdata.ws_user:  wdata.ws_password:  
145239ms th_a       main.cpp:243                  main                 ] Listening for incoming HTTP RPC requests on 0.0.0.0:9999

************
Finish!
```

Now the yoyow_client deployed successful.

3. Deploy the watcher by `install_bot.sh`

```
./install_bot.sh
```

Input your YOYOW ID

```
root@vmi161488:/yoyow/yoyow-witness-watcher# ./install_bot.sh 
Please input YOYOW ID:
485699321
```

Input your sign public key. If you have multiple public keys, separate them with commas

```
Please input Your Public Key:
If you have multiple public keys, separate them with commas
eg. YYW7TSRLZ9EXZps37Kt31qa7qi,YYW7TSRLZ9EXZpZqk25atoL2s37
YYW7TSRLZ9EXZpZqk25atoL2s37Kt31qa7qi78ZR368kCN969rFiT,YYW733FxEEaAFTHxdTJdowZyQzJ3JnPocsVmdq4aSsm1gSd1VkYDC
```

Input your wallet password.

```
Please input wallet password:
123456
```

When you see these messages, it is successful.

```
Get status
total_produced: 1096, total_missed: 1
2018-04-10 00:07:29


***********
Finish!!
```

# How to uninstall

```
./uninstall_bot.sh
./uninstall.sh
```

# Vote ME

I am very grateful that you can vote for me. My YOYOW ID is 485699321.

# Bugs

If you get errors, please leave an issue on issues page.
