var http = require('http');
var ethers = require('ethers');
const TronWeb = require('tronweb');


console.log('Test');

var server = http.createServer(function(req, res) {
    if (req.method === 'POST') {
    var body = '';
    req.on('data', function(chunk) {
      body += chunk;
    });
    req.on('end', function() {
        var data = JSON.parse(body);
        console.log(data);
        console.log("http://127.0.0.1:18099")
        if(data.method == "createaddress")
        {
            const tronWeb = new TronWeb({
              fullHost: 'http://127.0.0.1:18099',
           });
            var trx_account = tronWeb.createAccount().then(
                result => {
            res.writeHead(200);
            res.end(JSON.stringify(result));
            });
            
        }
        else if(data.method == "createtransfer")
        {
            //console.log(data);
            const tronWeb = new TronWeb({
              fullHost: 'http://127.0.0.1:18099',
           });
            var privateKey = data.privateKey;
            var toAddress = data.to;
            var Amount = data.amount;
            var trx_transaction = tronWeb.trx.sendTransaction(toAddress,Amount,privateKey).then(
                result => {
                    console.log(result);
            res.writeHead(200);
            res.end(JSON.stringify(result));
            });
            
        }
        else if(data.method == "tokentransfer")
        {
            //console.log(data);
            var privateKey = data.privateKey;
            var toAddress = data.to;
            var Amount = data.amount;
            var Contract_address = data.contract_address;
            var Owner_address = data.owner_address;

            const HttpProvider = TronWeb.providers.HttpProvider;
            const fullNode = new HttpProvider("http://127.0.0.1:18099");
            const solidityNode = new HttpProvider("http://127.0.0.1:18099");
            const eventServer = new HttpProvider("http://127.0.0.1:18099");
            const tronWeb = new TronWeb(fullNode,solidityNode,eventServer,privateKey);
            async function triggerSmartContract() {

            try {
                let contract = await tronWeb.contract().at(Contract_address);
                //Use send to execute a non-pure or modify smart contract method on a given smart contract that modify or change values on the blockchain.
                // These methods consume resources(bandwidth and energy) to perform as the changes need to be broadcasted out to the network.
                var transfer = await contract.transfer(
                    toAddress, //address _to
                    Amount   //amount
                ).send({
                    feeLimit: 100000000
                });
                //console.log(transfer);

                var resp = transfer;

                return resp;

                
            } catch(error) {
                //console.error("trigger smart contract error",error)
            }

            
           }
              var resps =  triggerSmartContract().then(
                        result => {
                           // console.log(result);
                            var obj = {
                                "result":result
                            }
                            res.writeHead(200);
                            res.end(JSON.stringify(obj));
                    
                    });
            
            
        }
         else if(data.method == "tokenbalance")
        {
            console.log(data);
            var address = data.address;
            var contract_address = data.contract_address;
            var privateKey = data.privateKey;
            const HttpProvider = TronWeb.providers.HttpProvider;
            const fullNode = new HttpProvider("http://127.0.0.1:18099");
            const solidityNode = new HttpProvider("http://127.0.0.1:18099");
            const eventServer = new HttpProvider("http://127.0.0.1:18099");
            const tronWeb = new TronWeb(fullNode,solidityNode,eventServer,privateKey);
           // console.log(tronWeb);
            async function triggerSmartContract() {
            try {
                    let contract = await tronWeb.contract().at(contract_address);
                    //Use call to execute a pure or view smart contract method.
                    // These methods do not modify the blockchain, do not cost anything to execute and are also not broadcasted to the network.
                    
                    const result = await contract.balanceOf(address).call();

                   // console.log('My object: ', result);
                    if(result.balance){

                        var res = result.balance.toNumber();
                    }
                    else{

                        var res =result.toString();

                    }
                    
                    
                    console.log(res);

                    return res;
                     
                } catch(error) {
                    console.error("trigger smart contract error",error)
                }
                        
                    }
                  var balance =  triggerSmartContract().then(
                            result => {
                              
                                var obj = {
                                    "result":result
                                }
                                res.writeHead(200);
                                res.end(JSON.stringify(obj));
                        
                        });  

        
    }
    else if(data.method == "ercbalance")
    {
        // Include the packages
        console.log(data);
        const Web3 = require('web3');
        const abi = require('human-standard-token-abi');

        // Set up Infura as your RPC connection
        const web3 = new Web3('http://127.0.0.1:8545');
        //console.log("data",data.address);
        if(data.address != null && data.address != undefined && data.address != '0')
        {
           // console.log("data",data.address);
            // Define the contract addresses and the contract instance
        const contractAddress = data.contract_address;
        const contract = new web3.eth.Contract(abi, contractAddress);

        // Define the address and call the balanceOf method
        const address = data.address;
        contract.methods.balanceOf(address).call().then(
            result => {
                var obj = {
                        "result":result
                    }
                   // console.log(obj);
                    res.writeHead(200);
                    res.end(JSON.stringify(obj));
            });

        }
        
    }
    else if(data.method == "bnbbalance")
    {
        //console.log(data);
        // Include the packages
        const Web3 = require('web3');
        const abi = require('human-standard-token-abi');

        // Set up Infura as your RPC connection
        const web3 = new Web3('http://127.0.0.1:6545');
        //console.log("data",data.address);
        if(data.address != null && data.address != undefined && data.address != '0')
        {
           // console.log("data",data.address);
            // Define the contract addresses and the contract instance
        const contractAddress = data.contract_address;
        const contract = new web3.eth.Contract(abi, contractAddress);

        // Define the address and call the balanceOf method
        const address = data.address;
        contract.methods.balanceOf(address).call().then(
            result => {
                var obj = {
                        "result":result
                    }
                    res.writeHead(200);
                    res.end(JSON.stringify(obj));
            });

        }
        
    }
    });
  } else { 
    res.writeHead(404);
    res.end();
  }
});

server.listen(7003, '127.0.0.1', () => {
    console.log('node server connected');
});

