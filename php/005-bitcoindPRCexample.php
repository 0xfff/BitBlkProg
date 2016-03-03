<?php
/*
	bitcoind RPC over php example
	Albert Szmigielski
	Jan 30 2016
	MIT license

*/

require_once 'jsonRPCClient.php';
	// json RPC library

$bitcoin = new jsonRPCClient('http://bitcoin_user:qwertyu873nld9j4n09sn3mm@127.0.0.1:18332/');

$blockcount = ($bitcoin->getblockcount());
print "current block count is: $blockcount \n";

$info = ($bitcoin->getinfo());
print "version " . $info['version'] . "\n";


foreach ($info as $key => $val) {
    print "$key = $val\n";
}
//var_dump($info);


?>
