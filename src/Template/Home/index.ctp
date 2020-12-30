<div class="container-fluid" section id="mission-vision" class="section-about">
	<div class="slider-container">
		<div class="item">
			<div class="owl-slider-item">
				<?= $this->Html->Image('mission.png', ['class'=>'img-responsive']) ?>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid" section id="map" class="section-features">
	<div class="slider-container">
		<div class="item">
			<div class="owl-slider-item">
				<?= $this->Html->Image('MAPPPFINAL.jpg', ['class'=>'img-responsive']) ?>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid" section id="schedule" class="section-about">
	<div class="slider-container">
		<div class="item">
			<?= $this->element('schedule') ?>
		</div>
	</div>
</div>
<section id="faculty" class="light-bg">
			<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="section-title">
						<h2>Faculty</h2>
					</div>
				</div>
			</div>
			<div class="row row-0-gutter">
			<!-- start portfolio item -->
			<div class="col-md-3">
				<div class="ot-portfolio-item">
					<figure class="effect-bubba">
						<?= $this->Html->Image('AP.png', ['class'=>'img-responsive']) ?>
						<figcaption>
							<h2>View</h2>
							<a href="#" data-toggle="modal" data-target="#modalAP">View more</a>
						</figcaption>
					</figure>
				</div>
			</div>
			<div class="col-md-3">
				<div class="ot-portfolio-item">
					<figure class="effect-bubba">
						<?= $this->Html->Image('ENGLISH.png', ['class'=>'img-responsive']) ?>
						<figcaption>
							<h2>View</h2>
							<a href="#" data-toggle="modal" data-target="#modalENGLISH">View more</a>
						</figcaption>
					</figure>
				</div>
			</div>
			<div class="col-md-3">
				<div class="ot-portfolio-item">
					<figure class="effect-bubba">
						<?= $this->Html->Image('MAPEH.png', ['class'=>'img-responsive']) ?>
						<figcaption>
							<h2>View</h2>
							<a href="#" data-toggle="modal" data-target="#modalMAPEH">View more</a>
						</figcaption>
					</figure>
				</div>
			</div>
			<div class="col-md-3">
				<div class="ot-portfolio-item">
					<figure class="effect-bubba">
						<?= $this->Html->Image('MATH.png', ['class'=>'img-responsive']) ?>
						<figcaption>
							<h2>View</h2>
							<a href="#" data-toggle="modal" data-target="#modalMATH">View more</a>
						</figcaption>
					</figure>
				</div>
			</div>
			<div class="col-md-3">
				<div class="ot-portfolio-item">
					<figure class="effect-bubba">
						<?= $this->Html->Image('PILIPINO.png', ['class'=>'img-responsive']) ?>
						<figcaption>
							<h2>View</h2>
							<a href="#" data-toggle="modal" data-target="#modalPILIPINO">View more</a>
						</figcaption>
					</figure>
				</div>
			</div>
			<div class="col-md-3">
				<div class="ot-portfolio-item">
					<figure class="effect-bubba">
						<?= $this->Html->Image('SCIENCE.png', ['class'=>'img-responsive']) ?>
						<figcaption>
							<h2>View</h2>
							<a href="#" data-toggle="modal" data-target="#modalSCIENCE">View more</a>
						</figcaption>
					</figure>
				</div>
			</div>
			<div class="col-md-3">
				<div class="ot-portfolio-item">
					<figure class="effect-bubba">
						<?= $this->Html->Image('SHC.png', ['class'=>'img-responsive']) ?>
						<figcaption>
							<h2>View</h2>
							<a href="#" data-toggle="modal" data-target="#modalSHC">View more</a>
						</figcaption>
					</figure>
				</div>
			</div>
			<div class="col-md-3">
				<div class="ot-portfolio-item">
					<figure class="effect-bubba">
						<?= $this->Html->Image('TLE.png', ['class'=>'img-responsive']) ?>
						<figcaption>
							<h2>View</h2>
							<a href="#" data-toggle="modal" data-target="#modalTLE">View more</a>
						</figcaption>
					</figure>
				</div>
			</div>
</section>
<div class="container-fluid" section id="master-lists" class="section-about">
	<div class="slider-container">
		<div class="item">
		<h1>Master Lists</h1>		
		<table class="table table-condensed" id="faculty">
		<thead>
			<th>Principal</th>
		</thead>
		<tr>
			<th>Lastname</th>
			<th>Firstname</th>
			<th>Middle</th>
		</tr>
		<?php
			foreach ($master_lists as $master_list) {
				if ($master_list->position_id == 5) {
		?>
		<tr>
			<td><?= $master_list->lastname ?></td>
			<td><?= $master_list->firstname ?></td>
			<td><?= $master_list->middle ?></td>
		</tr>
		<?php
				}
			}
		?>
		</table>

		<table class="table table-condensed">
		<thead>
			<th>ADAS 3</th>
		</thead>
		<tr>
			<th>Lastname</th>
			<th>Firstname</th>
			<th>Middle</th>
		</tr>
		<?php
			foreach ($master_lists as $master_list) {
				if ($master_list->position_id == 6) {
		?>
		<tr>
			<td><?= $master_list->lastname ?></td>
			<td><?= $master_list->firstname ?></td>
			<td><?= $master_list->middle ?></td>
		</tr>
		<?php
				}
			}
		?>
		</table>

		<table class="table table-condensed">
		<thead>
			<th>ADAS 2</th>
		</thead>
		<tr>
			<th>Lastname</th>
			<th>Firstname</th>
			<th>Middle</th>
		</tr>
		<?php
			foreach ($master_lists as $master_list) {
				if ($master_list->position_id == 7) {
		?>
		<tr>
			<td><?= $master_list->lastname ?></td>
			<td><?= $master_list->firstname ?></td>
			<td><?= $master_list->middle ?></td>
		</tr>
		<?php
				}
			}
		?>
		</table>

		<table class="table table-condensed">
		<thead>
			<th>Guidance Counselor</th>
		</thead>
		<tr>
			<th>Lastname</th>
			<th>Firstname</th>
			<th>Middle</th></tr>
		<?php
			foreach ($master_lists as $master_list) {
				if ($master_list->position_id == 8) {
		?>
		<tr>
			<td><?= $master_list->lastname ?></td>
			<td><?= $master_list->firstname ?></td>
			<td><?= $master_list->middle ?></td>
		</tr>
		<?php
				}
			}
		?>
		</table>

		<table class="table table-condensed">
		<thead>
			<th>Master-Teacher 1</th>
		</thead>
		<tr>
			<th>Lastname</th>
			<th>Firstname</th>
			<th>Middle</th>
			<th>Major</th>
			<th>Teaching Loads</th>
		</tr>
		<?php
			foreach ($master_lists as $master_list) {
				if ($master_list->position_id == 1) {
		?>
		<tr>
			<td><?= $master_list->lastname ?></td>
			<td><?= $master_list->firstname ?></td>
			<td><?= $master_list->middle ?></td>
			<td><?= $master_list->subject->subject ?></td>
			<td><?= $master_list->loads ?></td>
		</tr>
		<?php
				}
			}
		?>
		</table>

		<table class="table table-condensed">
		<thead>
			<th>Teacher 3</th>
		</thead>
		<tr>
			<th>Lastname</th>
			<th>Firstname</th>
			<th>Middle</th>
			<th>Major</th>
			<th>Teaching Loads</th>
		</tr>
		<?php
			foreach ($master_lists as $master_list) {
				if ($master_list->position_id == 2) {
		?>
		<tr>
			<td><?= $master_list->lastname ?></td>
			<td><?= $master_list->firstname ?></td>
			<td><?= $master_list->middle ?></td>
			<td><?= $master_list->subject->subject ?></td>
			<td><?= $master_list->loads ?></td>
		</tr>
		<?php
				}
			}
		?>
		</table>

		<table class="table table-condensed">
		<thead>
			<th>Teacher 2</th>
		</thead>
		<tr>
			<th>Lastname</th>
			<th>Firstname</th>
			<th>Middle</th>
			<th>Major</th>
			<th>Teaching Loads</th>
		</tr>
		<?php
			foreach ($master_lists as $master_list) {
				if ($master_list->position_id == 3) {
		?>
		<tr>
			<td><?= $master_list->lastname ?></td>
			<td><?= $master_list->firstname ?></td>
			<td><?= $master_list->middle ?></td>
			<td><?= $master_list->subject->subject ?></td>
			<td><?= $master_list->loads ?></td>
		</tr>
		<?php
				}
			}
		?>
		</table>

		<table class="table table-condensed">
		<thead>
			<th>Teacher 1</th>
		</thead>
		<tr>
			<th>Lastname</th>
			<th>Firstname</th>
			<th>Middle</th>
			<th>Major</th>
			<th>Teaching Loads</th>
		</tr>
		<?php
			foreach ($master_lists as $master_list) {
				if ($master_list->position_id == 4) {
		?>
		<tr>
			<td><?= $master_list->lastname ?></td>
			<td><?= $master_list->firstname ?></td>
			<td><?= $master_list->middle ?></td>
			<td><?= $master_list->subject->subject ?></td>
			<td><?= $master_list->loads ?></td>
		</tr>
		<?php
				}
			}
		?>
		</table>
		</div>
	</div>
</div>
<!-- Araling Panlipunan Teachers Modal -->
<div class="modal fade" id="modalAP" tabindex="-1" role="dialog" aria-labelledby="Modal-label-3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="Modal-label-3">Araling Panlipunan Teachers</h4>
			</div>
			<div class="modal-body">
			<?php
			$count = 0;
			foreach ($master_lists as $master_list) {
				if ($master_list->subject_id == 1) { 
				$count = $count + 1;
			?>
				<p><?= $count ?>. <?= $master_list->lastname.', '.$master_list->firstname.' '.$master_list->middle ?>.</p>
			<?php	
				}
			}
			?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- ENGLISH Teachers Modal -->
<div class="modal fade" id="modalENGLISH" tabindex="-1" role="dialog" aria-labelledby="Modal-label-3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="Modal-label-3">English Teachers</h4>
			</div>
			<div class="modal-body">
			<?php
			$count = 0;
			foreach ($master_lists as $master_list) {
				if ($master_list->subject_id == 6) { 
				$count = $count + 1;
			?>
				<p><?= $count ?>. <?= $master_list->lastname.', '.$master_list->firstname.' '.$master_list->middle ?>.</p>
			<?php	
				}
			}
			?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- MAPEH Teachers Modal -->
<div class="modal fade" id="modalMAPEH" tabindex="-1" role="dialog" aria-labelledby="Modal-label-3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="Modal-label-3">MAPEH Teachers</h4>
			</div>
			<div class="modal-body">
			<?php
			$count = 0;
			foreach ($master_lists as $master_list) {
				if ($master_list->subject_id == 3) { 
				$count = $count + 1;
			?>
				<p><?= $count ?>. <?= $master_list->lastname.', '.$master_list->firstname.' '.$master_list->middle ?>.</p>
			<?php	
				}
			}
			?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- MATH Teachers Modal -->
<div class="modal fade" id="modalMATH" tabindex="-1" role="dialog" aria-labelledby="Modal-label-3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="Modal-label-3">MATH Teachers</h4>
			</div>
			<div class="modal-body">
			<?php
			$count = 0;
			foreach ($master_lists as $master_list) {
				if ($master_list->subject_id == 5) { 
				$count = $count + 1;
			?>
				<p><?= $count ?>. <?= $master_list->lastname.', '.$master_list->firstname.' '.$master_list->middle ?>.</p>
			<?php	
				}
			}
			?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- PILIPINO Teachers Modal -->
<div class="modal fade" id="modalPILIPINO" tabindex="-1" role="dialog" aria-labelledby="Modal-label-3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="Modal-label-3">PILIPINO Teachers</h4>
			</div>
			<div class="modal-body">
			<?php
			$count = 0;
			foreach ($master_lists as $master_list) {
				if ($master_list->subject_id == 7) { 
				$count = $count + 1;
			?>
				<p><?= $count ?>. <?= $master_list->lastname.', '.$master_list->firstname.' '.$master_list->middle ?>.</p>
			<?php	
				}
			}
			?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- SCIENCE Teachers Modal -->
<div class="modal fade" id="modalSCIENCE" tabindex="-1" role="dialog" aria-labelledby="Modal-label-3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="Modal-label-3">SCIENCE Teachers</h4>
			</div>
			<div class="modal-body">
			<?php
			$count = 0;
			foreach ($master_lists as $master_list) {
				if ($master_list->subject_id == 4) { 
				$count = $count + 1;
			?>
				<p><?= $count ?>. <?= $master_list->lastname.', '.$master_list->firstname.' '.$master_list->middle ?>.</p>
			<?php	
				}
			}
			?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- SHC Teachers Modal -->
<div class="modal fade" id="modalSHC" tabindex="-1" role="dialog" aria-labelledby="Modal-label-3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="Modal-label-3">Senior High Teachers</h4>
			</div>
			<div class="modal-body">
			<?php
			$count = 0;
			foreach ($master_lists as $master_list) {
				if (!$master_list->subject_id == 1 && !$master_list->subject_id == 2 && !$master_list->subject_id == 3 && !$master_list->subject_id == 4 && !$master_list->subject_id == 5 && !$master_list->subject_id == 6 && !$master_list->subject_id == 7 && !$master_list->subject_id == 8) { 
				$count = $count + 1;
			?>
				<p><?= $count ?>. <?= $master_list->lastname.', '.$master_list->firstname.' '.$master_list->middle ?>.</p>
			<?php	
				}
			}
			?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- TLE Teachers Modal -->
<div class="modal fade" id="modalTLE" tabindex="-1" role="dialog" aria-labelledby="Modal-label-3">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="Modal-label-3">TLE Teachers</h4>
			</div>
			<div class="modal-body">
			<?php
			$count = 0;
			foreach ($master_lists as $master_list) {
				if ($master_list->subject_id == 2) { 
				$count = $count + 1;
			?>
				<p><?= $count ?>. <?= $master_list->lastname.', '.$master_list->firstname.' '.$master_list->middle ?>.</p>
			<?php	
				}
			}
			?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>