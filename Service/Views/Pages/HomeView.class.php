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

		<div>
			<div class="container white roboto blacksoft-text prin">
				<h3 class="izquierda germania">
					Bienvenido a SPPARH
				</h3>
				<h5>End Ponits</h5>
				<p>Documentos</p>
				<ul>
					<li><strong>GET</strong> <?= RAIZ ?>api/v2/documents</li>
					<li><strong>GET</strong> <?= RAIZ ?>api/v2/documents/{id}</li>
					<li><strong>POST</strong> <?= RAIZ ?>api/v2/documents</li>
					<li><strong>PUT</strong> <?= RAIZ ?>api/v2/documents/{id}</li>
					<li><strong>DELETE</strong> <?= RAIZ ?>api/v2/documents/{id}</li>
				</ul>
			</div>
		</div>
		<style>
			.prin{
				padding: 20px;
				border-radius: 5px;
				box-shadow: 4px 4px 16px black;
				margin-top: 20px;
			}
			.prin li{
				width: 100%;
				overflow: hidden;
				text-overflow: ellipsis;
				white-space: nowrap;
			}
		</style>

		<?php
	}
}

?>