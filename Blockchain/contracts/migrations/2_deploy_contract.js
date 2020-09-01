// requires the compiled code and other informations
const FirstContract = artifacts.require("CertificateRecord");
// deploys the contract named FirstContract from the JSON artifact
module.exports = function(deployer) {
	deployer.deploy(FirstContract);
};