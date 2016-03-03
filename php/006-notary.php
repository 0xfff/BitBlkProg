<?php

/*
notary service
Albert Szmigielski
MIT license
Version 0.01 Proof of Concept
No error checking in V0.01

see http://blog.cryptoiq.ca/?p=331 for more info.
*/

require_once 'jsonRPCClient.php';
require 'OP_RETURN.php';

$SERVICE_FEE=0.01;
	//this fee can be dynamic, we can find out exchange rate then set it to something appropriate
$PAY_TO="mhyedidXewEt2Y55Lk9H9K3mfTjrqKPqnT";
$TX_FEE=0.0001; //tx_fee can also be calculated by a formula
$TESTNET=1;

$bitcoin = new jsonRPCClient('http://bitcoin_user:qwertyu873nld9j4n09sn3mm@192.168.0.19:18332/');
 
//echo "<pre>\n";
//print_r($bitcoin->getinfo());
$BALANCE=($bitcoin->getbalance("notary"));
print "Balance: $BALANCE \n";

if ($BALANCE < ($SERVICE_FEE + $TX_FEE) ) 
	{ echo "You need to add money to your account\n"; }
else
	{echo "funds are sufficient\n";}

/****** IDENTITY ******/
$ID = ($bitcoin-> getaccountaddress("notary"));
print "ID: $ID \n";

/****** HASH ******/
$FILE = "will.txt";
$HASH = hash_file ('sha256', $FILE);
print "HASH: $HASH \n";

/****** SIGN ******/
$DIGEST= hash( 'sha1', $HASH);
$SIG = ($bitcoin-> signmessage($ID, $DIGEST));

print "DIGEST: $DIGEST \n";
print "SIGNATURE: $SIG \n";
/******TRANSACTION ******/

//$TX_ID = OP_RETURN_SEND $PAY_TO $SERVICE_FEE $SIG $TESTNET;
	//remove 1 for mainnet

/*
	//PAY FEE FOR THE SERVICE
$TX_ID_SERVICE_FEE=($bitcoin->sendtoaddress "mkuCXBkcssDTFepwm7FUXkH5MdtKCS8Sxb" $SERVICE_FEE "fee for notary service" "to notary");
*/

$STORING = OP_RETURN_store ($SIG, $TESTNET);
$REF= $STORING['ref'];
$TX_ID= $STORING['txids'][0];
$TX_ID1=$STORING['txids'][1];
$TX_ID2=$STORING['txids'][2];
print  "REF: $REF \n";
print  "TX_IDs:\n $TX_ID \n $TX_ID1 \n $TX_ID2 \n";



/* VERIFY 
	take hash of the submited file, sign it and compare it to the retrieved signature.
*/

$RETRIEVED = OP_RETURN_retrieve($REF, $TESTNET);
$SIG_BLOCKCHAIN = $RETRIEVED[0]['data'];

$VER_FILE = "will.txt";
$VER_HASH = hash_file ('sha256', $VER_FILE);
$VER_DIGEST= hash( 'sha1', $VER_HASH);

print "
VER_DIGEST: $VER_DIGEST \n
SIG_BLOCKCHAIN: $SIG_BLOCKCHAIN \n";

$VER_SIG = ($bitcoin-> verifymessage($ID, $SIG_BLOCKCHAIN, $VER_DIGEST));

print "VER_SIG: $VER_SIG \n";
if ($VER_SIG)
	{print "Signature checks out file is authentic \n";}
else 
	{print "Signature does not check out, file is NOT authentic \n";}

?>	
