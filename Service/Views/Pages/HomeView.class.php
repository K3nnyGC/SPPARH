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

		<p>This is SPPARH Site</p>
		<?php
	}
}

?>