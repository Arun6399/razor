
var ripple = require('ripple-lib');
var Remote = ripple.RippleAPI;

var Ripple = new Remote({
    server: 'ws://127.0.0.1:6006'
    //server: "wss://s1.ripple.com"
});

Ripple.on('error', function (errorCode, errorMessage) {
//console.log(errorCode + ': ' + errorMessage);
resp.json('{"status":0,"msg":"Unable to withdraw, problem occured. '+errorMessage+'."}');
});

Ripple.on('connected', function () {
//console.log('connected');process.exit(-1);
});
Ripple.on('disconnected', function (code) {
// code - close code sent by the server
// will be 1000 if this was normal closure
console.log('disconnected, code:', code);
});
Ripple.connect().then(function () {
// console.log('ripple connected');
return Ripple.getServerInfo();
// insert code here //
}).then(function (server_info) {

var rippleAddress = Ripple.generateAddress();

console.log(rippleAddress);process.exit(-1);

//var rippleAddress = {secret: 'ssDcKVdNMjDHgeq3a6H1i3rrr9xAM', address: 'rLmHU2KBqnHrBuZJjeqxCS8ktkBCrbYrbn' };
//var rippleAddress = {secret: 'snxrVzgqmhVZPDAn7YfHfExsVHN3A', address: 'rJ1HQYxSh89iaH4JzbMPMxpFAbznNx2y6m' };
//var rippleAddress1 = { secret: 'ssyDKNgM193GMHNbHu1tJW3fCBP4a', address: 'rMUQTuECsNBnudRBnJhu42MYThGUaEDHt5' };
// var rippleaddress = { secret: 'shpaCtvktxPfGM1duhgc4FCJzYihu', address: 'r4feKNVW9y4CaYv8kf7L9HRJsDgRZjJKyL' };
//var rippleaddress = { secret: 'saDt8rJaC2FejbXkti4X8j52XnfxC', address: 'rsGBsAeb3HtYqVNZGdR6S2mp78chaprqpH' };
//var rippleaddress = { secret: 'shBXKP8vBywnBhNepPYSWYKaBvFeM', address: 'rBj3EXoqDN5MmKTWBuJoxHKc1dveonYNCV' };
/*
//create transaction
const payment = {
"source": {
"address": rippleaddress.address,
"maxAmount": {
"value": "70",
"currency": "XRP",
}
},
"destination": {
"address": RippleAccountDetails.address,
"amount": {
"value": "70",
"currency": "XRP",
},
"tag":15
}
};
console.log(payment);
Ripple.preparePayment(rippleaddress.address, payment).then(function (prepared) {
console.log('prepared', prepared);
var txJSON =prepared.txJSON;
var secret = rippleaddress.secret; //'ssHK5mmmHqdwUcxPeaivxrgazmeiA';
var signed = Ripple.sign(txJSON, secret);
console.log(signed);
Ripple.submit(signed.signedTransaction).then(function (result) {
// resultCode:'tesSUCCESS'
//resultMessage:
console.log('final',result);
}).catch(console.error);
}).catch(console.error);
* 
* */
/*Ripple.getBalances('rLmHU2KBqnHrBuZJjeqxCS8ktkBCrbYrbn').then(function (balance) {
console.log('balance', balance);process.exit(-1);
}); */

/*Ripple.getLedgerVersion('rLmHU2KBqnHrBuZJjeqxCS8ktkBCrbYrbn').then(function (info) {
console.log(info);
}).catch(console.error);
*/

/*Ripple.getTransactions('rLmHU2KBqnHrBuZJjeqxCS8ktkBCrbYrbn').then(function (transaction) {
console.log(JSON.stringify(transaction));process.exit(-1);
}).catch(console.error);*/
/*
Loptions = {};
Loptions.includeAllData = true;
Loptions.includeTransactions = true;
//Ripple.getLedgerVersion().then(function (LedgerVersion) {
//Loptions.ledgerVersion = LedgerVersion;
Ripple.getLedger(Loptions).then(function (info) {
//console.log(info);
}).catch(console.error); 
// }).catch(console.error);
/
/
Toptions = {};
//Toptions.minLedgerVersion = 1;
//Toptions.maxLedgerVersion = 7695450;
Toptions.types = ["payment"];
Ripple.getTransactions('rsGBsAeb3HtYqVNZGdR6S2mp78chaprqpH', Toptions).then(function (transactions) {
if(transactions.length){
  transactions.forEach(function(transaction){
  });
}
console.log(transactions[0].specification.destination.amount);
console.log(transactions[0].specification.destination.address);
console.log(transactions[0].specification.destination.tag);
}).catch(console.error);  
 */
//console.log('test');
//return api.disconnect();
}).catch(console.error);