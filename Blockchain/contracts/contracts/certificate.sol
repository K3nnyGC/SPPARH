//pragma solidity ^0.4.25;
pragma solidity >=0.5.0;

contract CertificateRecord {
    //VARS
    string private name;
    string private hash;
    string private student;
    string private date;

    // struct certificate{
    //     string name;
    //     string hash;
    //     string student;
    //     string date;
    // }

    // certificate private crt_obj;

    //Constructor
    constructor() public{
        name = "name";
        hash = "hash";
        student = "studet";
        date = "2020-08-01";
        //crt_obj = certificate({name:name, hash: hash, student:student, date:date });
    }
    
    
    

    function setCertificate(string memory _name, string memory _hash, string memory _student, string memory _date) public {
        //crt_obj = certificate({name:_name, hash: _hash, student:_student, date:_date });
        name = _name;
        hash = _hash;
        student = _student;
        date = _date;
    }
  
    function getCertificate() public view returns (string memory, string memory, string memory, string memory) {
        //return (crt_obj.name, crt_obj.name, crt_obj.name, crt_obj.date);
        return (name, hash, student, date);
    }

    function getHash() public view returns (string memory) {
        return hash;
    }
}