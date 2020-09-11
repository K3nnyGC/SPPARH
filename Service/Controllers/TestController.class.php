<?php 

class TestController extends Controller {

	public $api;
	static public $num = 1;

	public function __construct($api){
        parent::__construct();
        $this->api = $api;
        $this->ini();
    }

    private function ini(){
    }

	public function getAll(){
		$curl = new MyCurl($this->api);
		$this->printString($curl->go(),"GET",$this->api);
		self::$num++;
	}

	public function get($id){
		$api = $this->api . "/{$id}";
		$curl = new MyCurl($api);
		$this->printString($curl->go(),"GET",$api);
		self::$num++;
	}

	public function create($params){
		$api = $this->api;
		$curl = new MyCurl($api,"POST");
		$this->printString($curl->postForm($params),"POST",$api);
		self::$num++;
	}

	public function createByJson($params){
		$api = $this->api;
		$curl = new MyCurl($api,"POST");
		$this->printString($curl->postData($params),"POST",$api);
		self::$num++;
	}

	public function update($id,$params){
		$api = $this->api . "/{$id}";
		$curl = new MyCurl($api,"PUT");
		$this->printString($curl->postData($params),"PUT",$api);
		self::$num++;
	}

	public function delete($id){
		$api = $this->api . "/{$id}";
		$curl = new MyCurl($api,"DELETE");
		$this->printString($curl->postData([]),"DELETE",$api);
		self::$num++;
	}




	public function printString($string,$method="",$api=""){
		?>
		<div class="container">
			<div class="block">
				<h5><?= self::$num ?>.- <?= $method ?>: <?= $api ?></h5>
				<p style="text-align: left;">
					<?php var_dump($string); ?>		
				</p>
			</div>
		</div>
		
		<?php
	}

	static public function printMaterialize(){
		?>
			<link rel="stylesheet" href="<?= RAIZ ?>Assets/css/MatDes.css">
			<!--<script src="<?= RAIZ ?>Assets/js/imp/MatDes.js"></script>-->
		<?php
	}

	static public function initMaterialize(){
		?>
			<script>
				document.addEventListener('DOMContentLoaded', function() {
				    /*var elems = document.querySelectorAll('.collapsible');
				    var instances = M.Collapsible.init(elems, {});*/
				});
			</script>
		<?php
	}

}



?>