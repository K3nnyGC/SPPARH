<?php 

/**
 * 
 */
class ErrorView extends View {
	
	function __construct(){
		parent::__construct();
		$this->error=false;
	}

	public function printview(){
		if (!$this->checkAccess()) {
			return false;
		}
		if (!$this->error) {
			return false;
		}
		?>
		<div class="row margin-bottom-cero" id="<?php echo "view" . $this->idview; ?>">
			<div class="col s12">
				<div class="card red margin-bottom-cero scale-transition">
					<?php echo $this->error; ?><span class="right closeboton" id="cerraralert<?php echo $this->idview; ?>"><i class="fas fa-times-circle"></i></span>
					<script>
						$("#cerraralert<?php echo $this->idview; ?>").click(function(){
							$(".scale-transition").addClass("scale-out");
							$(".scale-transition").remove();
						});
					</script>
				</div>
			</div>
		</div>
		<?php
	}

	public function setError($error=false){
		$this->error = $error;
	}
}


?>