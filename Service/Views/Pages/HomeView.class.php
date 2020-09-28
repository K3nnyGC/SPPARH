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

		<div class="row m-0 justify-content-center align-items-center vh-100">
			<div class="card text-center">
            	<div class="card-header">
                	<h4 style="font-family: 'Open Sans Condensed', sans-serif; ">
                		spparh <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-box" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    		<path fill-rule="evenodd" d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                  		</svg>
              		</h4>  
           		</div>
    
           		<div class="card-body">

            		<h5 class="card-title">¡Bienvenidos!</h5>

            		<p class="card-text text-left">Endpoints:</p>

            		<h4 class="card-text text-left">Documentos</h4>

            		<ul class="list-group list-group-flush">
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">GET</span> <?= RAIZ ?>api/v2/documents</li>
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">GET</span> <?= RAIZ ?>api/v2/documents/{id}
              			</li>
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">POST</span> <?= RAIZ ?>api/v2/documents
              			</li>
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">PUT</span> <?= RAIZ ?>api/v2/documents/{id}
              			</li>
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">DELETE</span> <?= RAIZ ?>api/v2/documents/{id}
              			</li>
                    <li class="list-group-item text-left">
                      <span class="badge badge-primary badge-pill">GET</span> <?= RAIZ ?>api/v2/documents/pending
                    </li>
            		</ul>

            		<h4 class="card-text text-left">Usuarios</h4>

            		<ul class="list-group list-group-flush">
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">GET</span> <?= RAIZ ?>api/v2/users</li>
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">GET</span> <?= RAIZ ?>api/v2/users/{id}
              			</li>
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">POST</span> <?= RAIZ ?>api/v2/users
              			</li>
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">PUT</span> <?= RAIZ ?>api/v2/users/{id}
              			</li>
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">DELETE</span> <?= RAIZ ?>api/v2/users/{id}
              			</li>
            		</ul>
            		<h5 class="card-text text-left">Login</h5>

            		<ul class="list-group list-group-flush">
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">POST</span> <?= RAIZ ?>api/v2/users/login</li>
            		</ul>
            		<h5 class="card-text text-left">Institute</h5>

            		<ul class="list-group list-group-flush">
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">GET</span> <?= RAIZ ?>api/v2/users/institutes</li>
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">GET</span> <?= RAIZ ?>api/v2/users/institutes/{id}</li>
              			<li class="list-group-item text-left">
              				<span class="badge badge-primary badge-pill">GET</span> <?= RAIZ ?>api/v2/users/institutes/pendings</li>
            		</ul>

                <h5 class="card-text text-left">Firmas</h5>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-left">
                      <span class="badge badge-primary badge-pill">POST</span> <?= RAIZ ?>api/v2/users/signs</li>
                </ul>

                <h5 class="card-text text-left">Certificados (firmado)</h5>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-left">
                      <span class="badge badge-primary badge-pill">POST</span> <?= RAIZ ?>api/v2/certificates/create</li>
                </ul>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-left">
                      <span class="badge badge-primary badge-pill">POST</span> <?= RAIZ ?>api/v2/certificates/update</li>
                </ul>
          		</div>
          		<div class="card-footer text-muted">
            		© Todos los derechos reservados Team SPPARH 2020
          		</div>
    			</div>
			</div>
		<div>

		<style>
			body{
				background-image: radial-gradient(circle at 13% 47%, rgba(140, 140, 140,0.03) 0%, rgba(140, 140, 140,0.03) 25%,transparent 25%, transparent 100%),radial-gradient(circle at 28% 63%, rgba(143, 143, 143,0.03) 0%, rgba(143, 143, 143,0.03) 16%,transparent 16%, transparent 100%),radial-gradient(circle at 81% 56%, rgba(65, 65, 65,0.03) 0%, rgba(65, 65, 65,0.03) 12%,transparent 12%, transparent 100%),radial-gradient(circle at 26% 48%, rgba(60, 60, 60,0.03) 0%, rgba(60, 60, 60,0.03) 6%,transparent 6%, transparent 100%),radial-gradient(circle at 97% 17%, rgba(150, 150, 150,0.03) 0%, rgba(150, 150, 150,0.03) 56%,transparent 56%, transparent 100%),radial-gradient(circle at 50% 100%, rgba(25, 25, 25,0.03) 0%, rgba(25, 25, 25,0.03) 36%,transparent 36%, transparent 100%),radial-gradient(circle at 55% 52%, rgba(69, 69, 69,0.03) 0%, rgba(69, 69, 69,0.03) 6%,transparent 6%, transparent 100%),linear-gradient(90deg, rgb(255,255,255),rgb(255,255,255));

			

			}
		</style>

		<?php
	}
}

?>