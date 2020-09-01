pragma solidity >=0.5.0;
import "truffle/Assert.sol";
import "truffle/DeployedAddresses.sol";
import "../contracts/certificate.sol";
contract TestFirstContract {
	function testItWorks() public {
		// Get the deployed contract
		CertificateRecord firstcontract =
		CertificateRecord(DeployedAddresses.CertificateRecord());
		// Call getText function in deployed contract
		//(string memory text1,string memory text2,string memory text3,string memory text4) = firstcontract.getCertificate();
		string memory text1 = firstcontract.getHash();
		// Assert the function returns the correct initial text
		Assert.equal(text1, "hash", "it works");
		//Assert.equal(text2, "hash", "it works");
		//Assert.equal(text3, "student", "it works");
		//Assert.equal(text4, "2020-08-01", "it works");
	}
}