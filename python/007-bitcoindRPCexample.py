''' 
	bitcoind RPC over Python example 
	Albert Szmigielski 
	Feb 28 2016 
	MIT license 

	see http://blog.cryptoiq.ca/?p=347 for more info.
'''

from bitcoinrpc.authproxy import AuthServiceProxy
 
access = AuthServiceProxy("http://bitcoin_user:qwertyu873nld9j4n09sn3mm@127.0.0.1:18332")

info = access.getinfo()
print"version:", info["version"]

for key in info: 
    print key, ':', info[key]
