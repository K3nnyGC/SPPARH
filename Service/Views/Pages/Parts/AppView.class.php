<?php 

/**
 * 
 */
class AppView extends View {
	
	function __construct(){
		parent::__construct();
		$this->isOnline = Auth::isOnline();
	}

	public function printview(){
	    /*
		if (!$this->checkAccess()) {
			return false;
		}
		if (!$this->error) {
			return false;
		}
		*/
		//echo file_get_contents("C:/wamp/www/Proyectos/25_mascara/web/index.php");
		//echo realpath(".");

		$semanas = [];
		for ($i=0; $i < 53 ; $i++) {
			$diaSemana=date("w",mktime(0,0,0,1,1 + $i*7,2019));
			if($diaSemana==0){
				$diaSemana=7;
			}
			$primerDia=date("d-m-Y",mktime(0,0,0,1,1 + $i*7-$diaSemana+1,2019));
    		$ultimoDia=date("d-m-Y",mktime(0,0,0,1,1 + $i*7+(7-$diaSemana),2019));
    		$semanas[] = ["semana" => $i +1 , "texto" => ($i +1). ". {$primerDia} - {$ultimoDia}"];
		}
		?>

<script>
	Utiles.token = "<?=  Auth::getToken() ?>";
	Utiles.semanas = <?= json_encode($semanas); ?>;
   Utiles.assets = "<?= ASSETS ?>";
</script>

<div id="app">
<noscript>
	<strong>We're sorry but vue-route doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
</noscript>
<div>
	<div class="row">
		<div class="col s12 m10 offset-m1 l8 offset-l2 xl6 offset-xl3">
			<div class="card-panel">
				<h3>Tareo</h3>
				
					<div class="row">
		         		<div class="col s12 marco">
							   <div class="input-field">
   								<span>Obra OT</span>
   								<select class="browser-default" v-model="ot" @change="loadCategorias2(ot.cod_zona)">
   									<option v-for="item in ots" :value="item">{{item.obra}}</option>
   								</select>
		         			</div>
		         		</div>
                     <!--<div class="col s1 marco">
                        <div>
                           <p></p>
                           <a class="btn waves-effect green but"><i class="fas fa-check"></i></a>
                        </div>
                     </div>-->
		         		<div class="col s12 marco">
							<div class="input-field">
								<span>Semana</span>
								<select class="browser-default" v-model="semana">
									<option
									v-for="item in cronograma"
									:value="item">{{item.semana}}. {{item.fec_ini}} {{item.fec_fin}}</option>
								</select>
		         			</div>
		         		</div>
		         		<div class="col s12 marco">
							<div class="input-field">
								<input id="dni" type="number" class="validate" v-model="doc" v-on:keyup.13="consultar" :disabled=load>
		         				<label for="dni">Documento</label>
		         				<!--<span>{{ fullname }}</span>-->
                           <ul class="collapsible">
                              <li>
                                 <div class="collapsible-header">
                                    {{ fullname }}
                                 </div>
                                 <div class="collapsible-body center">
                                    <div class="row">
                                       <div class="col s8 offset-s2 m6 offset-m3 l4 offset-l4  center">
                                          <img :src="imagen" class="userimg">
                                       </div>
                                    </div>
                                    
                                 </div>
                              </li>
                           </ul>
		         			</div>
		         		</div>
		         		<div class="col s12 marco">
							<div class="input-field">
								<span>Categoria</span>
								<select class="browser-default" v-model="categoria">
									<option
									v-for="item in categorias"
									 :value="item">{{item.categoria}}</option>
								</select>
		         			</div>
		         		</div>
		         	</div>
         			<h3>Horas</h3>

         			<!-- Tabla -->
         			<div class="row">
         				<div class="col s12">
         					<div class="fullt">
         						<table  id="tabla">
         							<thead>
         								<tr>
         									<th>Tipo</th>
         									<th>Lun</th>
         									<th>Mar</th>
         									<th>Mie</th>
         									<th>Jue</th>
         									<th>Vie</th>
         									<th>Sab</th>
         									<th>Dom</th>
         								</tr>
         							</thead>
         							<tbody>
         								<tr>
         									<td>Horas normales</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="1" step=0.1 v-model="datos.hno1">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="4" step=0.1 v-model="datos.hno2">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="7" step=0.1 v-model="datos.hno3">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="10" step=0.1 v-model="datos.hno4">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="13" step=0.1 v-model="datos.hno5">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="16" step=0.1 v-model="datos.hno6">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="19" step=0.1 v-model="datos.hno7">
         									</td>
         								</tr>
         								<tr>
         									<td>Horas 60%</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="2" step=0.1 v-model="datos.he11">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="5" step=0.1 v-model="datos.he12">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="8" step=0.1 v-model="datos.he13">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="11" step=0.1 v-model="datos.he14">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="14" step=0.1 v-model="datos.he15">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="17" step=0.1 v-model="datos.he16">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="20" step=0.1 v-model="datos.he17">
         									</td>
         								</tr>
         								<tr>
         									<td>Horas 80%</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="3" step=0.1 v-model="datos.he21">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="6" step=0.1 v-model="datos.he22">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="9" step=0.1 v-model="datos.he23">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="12" step=0.1 v-model="datos.he24">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="15" step=0.1 v-model="datos.he25">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="18" step=0.1 v-model="datos.he26">
         									</td>
         									<td>
         										<input type="number" class="validate" onkeypress='return event.charCode >= 46 && event.charCode <= 57' value=0 id="21" step=0.1 v-model="datos.he27">
         									</td>
         								</tr>
         							</tbody>
         						</table>
         					</div>
         				</div>
         			</div>
         			<div class="row">
         				<div class="col s6 center">
         					<a class="btn waves-effect green"
                           :class="[load ? 'disabled' : '']"
                           @click="salvar"
                        >Aceptar</a>
         				</div>
         				<div class="col s6 center">
         					<a class="btn waves-effect red"
                           @click="goDash"
                        >Cancelar</a>
         				</div>
         			</div>
				
			</div>
		</div>
	</div>
</div>		
</div>
<script src="<?= ASSETS ?>js/cus/Vue/VueTra.js"></script>

<style>
   .marco{
      /*border: solid 1px #7e717178;*/
   }
   .fullt{
      overflow-x: auto;
   }
   .but {
      margin-top: 22px;
      margin-left: -20px;
      height: 44px;
      line-height: 44px;
   }
   .userimg{
      max-width: 100%;
      height: auto;
   }
</style>
		<?php
	}
}