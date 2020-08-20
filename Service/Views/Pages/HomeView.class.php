<?php 

/**
 * 
 */
class HomeView extends View {
	
	function __construct(){
		parent::__construct();
		$this->isOnline = false;
	}

	public function printview(){
		$this->isOnline = Auth::isOnline();
		if (!$this->checkAccess()) {
			return false;
		}
		//parent::printview();
		?>

		<nav>
		    <div class="nav-wrapper padre white">
		      	<img  class="hijo" src="<?= ASSETS ?>img/logo_ecop.png" style="padding-left: 20px; height:100%;" />
		      	<a href="#" data-target="mobi" class="sidenav-trigger right">
		      		<i class="fas fa-bars black-text"></i>
		      	</a>
		    	<ul id="nav-mobile" class="right hide-on-med-and-down">
		    		<li>
		    			<a class="red-text"  onclick="goHome()">
		    				Home&nbsp;<i class="fas fa-home red-text"></i>
		    			</a>
		    		</li>
		    		<li>
		    			<a class="red-text"  onclick="goExit()">
		    				Salir&nbsp;<i class="fas fa-door-open red-text"></i>
		    			</a>
		    		</li>
		    	</ul>
		    </div>
		</nav>

		<ul class="sidenav" id="mobi">
		    <li>
		    	<a class="red-text"  onclick="goExit()">
		    		Salir&nbsp;<i class="fas fa-door-open red-text"></i>
		    	</a>
		    </li>
		    <li>
		    	<a class="red-text"  onclick="goHome()">
		    		Home&nbsp;<i class="fas fa-home red-text"></i>
		    	</a>
		    </li>
		</ul>

		<?php
			(new AppView())->printview();
			if ($this->isOnline) {
			} else {
			}
			
		?>
		<script>
			function goExit(){
				location.href ="<?= RAIZ ?>exit";
			}
			function goHome(){
				location.href ="<?= RAIZ ?>home";
			}
		</script>
		<?php
	}
}

?>