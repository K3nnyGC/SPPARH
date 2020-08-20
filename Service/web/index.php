<?php 
$tiempo_inicio = microtime(true);
include_once("../Core/autoload.php");

$wc = new WebController();
$wc->deployLayout();

exit();
 ?>


<div class="row">
	<div class="col s12">
		<div class="card-panel">
			<?php var_dump($_SESSION); ?>
			<?php
				function convert($size){
					    $unit=array('b','kb','mb','gb','tb','pb');
					    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
					}
				echo convert(memory_get_usage(true));

				echo "<br><br>";
				$tiempo_fin = microtime(true);
				echo "Tiempo empleado: " . ($tiempo_fin - $tiempo_inicio); 
			 ?>
		</div>
		
	</div>
</div>

<br>
<br>
<br>
<br>
<br>



