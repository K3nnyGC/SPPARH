<?php 

/**
 * 
 */
class HomeLayout extends Layout{
	
	function __construct(){
		parent::__construct();
		//$this->title = __CLASS__;
		$this->view = new HomeView();
		$this
			->setCss(["Comun","Home","MatDes","css/all","animate","../fonts/css/roboto","../fonts/css/germania"])
			->setJs(["cus/Home","imp/MatDes","imp/jq","imp/noti","imp/is","imp/vue-p","imp/trans"])
			->setJsFooter(["cus/HomeEnd"]);
		$this->footer = new FooterView();
	}

	public function putHead(){
		?>
		<head>
			<title><?php echo $this->title; ?></title>
			<meta charset="utf-8">
			<!-- Nuevo extra tags color y google analytics -->
			<meta description='Description de mi App'/>
			<!--<link rel="manifest" href="../Assets/json/manifest.json">-->
			<meta name='theme-color' content='#4CAF50'/><!--Chrome,Firefox OS y Opera-->
			<meta name='msapplication-navbutton-color' content='#4CAF50'/><!--Windows Phone-->
			<meta name='apple-mobile-web-app-capable' content='yes'/><!--iOS Safari-->
			<meta name='apple-mobile-web-app-status-bar-style' content='black-translucent'/><!--sigue safari-->


			<?php 
				if (ENVIROMENT != "DEV") {
					?>
			<!-- Tags solo para versiones fuera de desarrollo como Ads o Analitics -->
					<?php
				}
			?>

			<meta name='viewport' content='width=device-width, initial-scale=1'/>
			<link rel="icon" type="image/png" href="<?= RAIZ ?>Assets/img/icon.png" />
			<?php
				$this->putCss();
				$this->putJs();
			?>
		</head>
		<?php
	}



}

?>