//Dependencias
const express = require('express');
const bodyParser = require('body-parser');
const Web3 = require('web3');
const fs = require('fs');

//Contrato compilado
const contract = JSON.parse(fs.readFileSync('../api2/build/contracts/CertificateRecord.json', 'utf8'));
const abiDecoder = require('abi-decoder');
abiDecoder.addABI(contract.abi);

//Confirguracion de red
const PORT = process.env.PORT || 3000;
const httpProvider = 'http://127.0.0.1:8545';
const accountOwner = '0x8d5e800a7bdfe43f4e2a9fe5fd5889d253fb25c8';
const abiAdsress = '0xECeb038627b938A820eDB8819a399bb4909c018d';
const app = express();


app.use(bodyParser.json());
let balance = 0;


//Conección
function init(){
	web3 = new Web3(httpProvider);
	web3.eth.getAccounts(console.log);
}

//Métodos auxiliares

function getBalance(){
	web3.eth.getBalance(accountOwner, (error, wei) => {
		console.log("WEI:",wei);
		balance = web3.utils.fromWei(wei,'ether');
	}).then( ()=>{console.log('balance',balance);} );
}

function validate(data,params){
	let output = {ok:true};
	params.forEach( item => {
		if(data[item] === undefined){
			output.missing = item;
			output.ok = false;
		}
	});
	return output;
}


//Métodos del contrato

function getHash(){
	let myContract = new web3.eth.Contract(contract.abi, abiAdsress);
	myContract.methods.getHash().call(function(error, result){
		console.error(error); // optional
		console.log(result);
	});
}

function getCertificate(block,callback=null){
	let myContract = new web3.eth.Contract(contract.abi, abiAdsress);
	try{
		web3.eth.getBlock(block,true)
		.then( (result) => {
			let input =result.transactions[0].input;
			let str = web3.utils.hexToAscii(input);
			let decodedData = abiDecoder.decodeMethod(input);
			if(callback !== null){
				callback({
					ok: true,
					data: {
						name: decodedData.params[0].value,
						student: decodedData.params[1].value,
						hash: decodedData.params[2].value,
						date: decodedData.params[3].value
					}
				});
			}
		})
		.catch( error => {
			if(callback !== null){
				callback({
					ok: false,
					data: {
						message: "Dont exist"
					}
				});
			}
		});
	} catch (error){
		if(callback !== null){
			callback({
				ok: false,
				data: {
					message: "Dont exist"
				}
			});
		}
	}
}

function setCertificate(name,hash,student,date,callback=null){
	let myContract = new web3.eth.Contract(contract.abi, abiAdsress);
	myContract.methods.setCertificate(name,hash,student,date)
	.send({ from: accountOwner })
	.then(receipt => { 
		if(callback !== null){
			callback(receipt);
		}
		/*let block = web3.eth.getBlock("latest").then( (error, result) =>{
			console.log(error);
			console.log(result);
		});*/
		console.log("OK",receipt);
	});
}


//Rutas del servicio

app.get('/certificates/:id',(req, res)=> {
	const {id} = req.params;
	getCertificate(id, data => {
		if(!data.ok){res.status(404);}
		res.json(data);
	});
});

app.post('/certificates',(req, res)=> {
	isComplete = validate(req.body,['name','hash','student','date']);
	if(isComplete.ok){
		const cert = {
			name : req.body.name,
			hash : req.body.hash,
			student : req.body.student,
			date : req.body.date,
		}
		setCertificate(cert.name,cert.hash,cert.student,cert.date, data => {
			res.status(201);
			res.json(data);
		});
	} else {
		res.status(400);
		res.json({
			ok: false,
			message: 'Missing or Wrong Parameter',
			parameter: isComplete.missing
		});
	}
	//res.send('Metodo post');
	//getBalance();
});



//INICIAR
init();
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));