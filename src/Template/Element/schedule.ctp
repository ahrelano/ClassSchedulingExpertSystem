<script>
function teacherSchedule(val){
	if (val != "") {
	    $('#teacher'+val).modal({
	        show: true
	    });
	}
}
function searchroom(grade){
	$.ajax({
	    url: '<?= $this->Url->build(["controller"=>"Home", "action"=>"search-room"]) ?>/'+grade,
	    success: function(result){
	        $(".rooms").html(result);
	    }
	});
}
</script>
<?php 
function check_if_time_exists($time,$room,$subject){
	$cut_time = explode(" - ",$time);
	return [
			'time'=> $time,
			'room'=> $room,
			'subject'=> $subject,
			'cuttime'=> $cut_time[0]
		];
}

// debug($list_teachers);
if (isset($list_teachers)) {
	foreach ($list_teachers as $list_teacher) {
		unset($teacher_sched);
		$teacher_sched = array();
		$teachers[$list_teacher['id']] = $list_teacher['teacher'];
		if (isset($list_teacher['time1']['time'])){
			$teacher_sched[] = check_if_time_exists($list_teacher['time1']['time'], $list_teacher['time1']['room'], $list_teacher['time1']['subject']);
		}
		if (isset($list_teacher['time2']['time'])){ 
			$teacher_sched[] = check_if_time_exists($list_teacher['time2']['time'], $list_teacher['time2']['room'], $list_teacher['time2']['subject']);
		}
		if (isset($list_teacher['time3']['time'])){ 
			$teacher_sched[] = check_if_time_exists($list_teacher['time3']['time'], $list_teacher['time3']['room'], $list_teacher['time3']['subject']);
		}
		if (isset($list_teacher['time4']['time'])){ 
			$teacher_sched[] = check_if_time_exists($list_teacher['time4']['time'], $list_teacher['time4']['room'], $list_teacher['time4']['subject']);
		}
		if (isset($list_teacher['time5']['time'])){ 
			$teacher_sched[] = check_if_time_exists($list_teacher['time5']['time'], $list_teacher['time5']['room'], $list_teacher['time5']['subject']);
		}
		if (isset($list_teacher['time6']['time'])){ 
			$teacher_sched[] = check_if_time_exists($list_teacher['time6']['time'], $list_teacher['time6']['room'], $list_teacher['time6']['subject']);
		}
		if (isset($list_teacher['time7']['time'])){ 
			$teacher_sched[] = check_if_time_exists($list_teacher['time7']['time'], $list_teacher['time7']['room'], $list_teacher['time7']['subject']);
		}
		if (isset($list_teacher['time8']['time'])){ 
			$teacher_sched[] = check_if_time_exists($list_teacher['time8']['time'], $list_teacher['time8']['room'], $list_teacher['time8']['subject']);
		}
		// debug($list_teacher['teacher']);
		while (count($teacher_sched) > 0){
			foreach ($teacher_sched as $key => $sched) {
				if (!isset($previous_key)) {
					$previous_key = '';
				}
				// echo count($teacher_sched).'<br>';
				// echo $sched['cuttime'].'<br>';
				if (count($teacher_sched) == 1) {
					// echo $sched['cuttime'] . '<br><br><br>';
					// debug($sched['cuttime']);
					unset($teacher_sched[$key]);
				}
				unset($teacher_sched[$key]);
				// debug(count($teacher_sched));
				if (isset($previous_time)) {
					// debug((strtotime($sched['cuttime']) < strtotime($previous_time)) ? 'true' : 'false');
					// debug($sched['cuttime'] .' < '. $previous_time);
					if (strtotime($sched['cuttime']) < strtotime($previous_time)) {
						$previous_key = $key;
						$previous_time = $sched['cuttime'];
						// debug($previous_time);
					}
				}else{
					$previous_key = $key;
					$previous_time = $sched['cuttime'];
					// debug($previous_time);
				}
			}
			if ($previous_key != '') {
				// echo $previous_time . 'tt<br>';
				// unset($teacher_sched[$previous_key]);
				$previous_key = '';
				// debug(count($teacher_sched));
			}
		}
		?>
		<div class="modal fade" id="teacher<?= $list_teacher['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="teacher<?= $list_teacher['id'] ?>" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel"><?= $list_teacher['teacher'] ?></h4>
			        <p>Advise: <ins><?= $list_teacher['advise'] ?></ins></p>
			        <p>Major: <ins><?= $list_teacher['major'] ?></ins></p>
			        <p>Loads: <ins><?= ($list_teacher['loads'] < 0) ? '0' : $list_teacher['loads'] ?></ins></p>
			      </div>
			      <div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<th>Time</th>
							<th>Room(Grade)</th>
							<th>Subject</th>
						</tr>
						<?php if (isset($list_teacher['time1']['time'])): ?>
							<tr>
								<td><?= $list_teacher['time1']['time'] ?></td>
								<td><?= $list_teacher['time1']['room'] ?></td>
								<td><?= $list_teacher['time1']['subject'] ?></td>
							</tr>
						<?php endif ?>
						<?php if (isset($list_teacher['time2']['time'])): ?>
							<tr>
								<td><?= $list_teacher['time2']['time'] ?></td>
								<td><?= $list_teacher['time2']['room'] ?></td>
								<td><?= $list_teacher['time2']['subject'] ?></td>
							</tr>
						<?php endif ?>
						<?php if (isset($list_teacher['time3']['time'])): ?>
							<tr>
								<td><?= $list_teacher['time3']['time'] ?></td>
								<td><?= $list_teacher['time3']['room'] ?></td>
								<td><?= $list_teacher['time3']['subject'] ?></td>
							</tr>
						<?php endif ?>
						<?php if (isset($list_teacher['time4']['time'])): ?>
							<tr>
								<td><?= $list_teacher['time4']['time'] ?></td>
								<td><?= $list_teacher['time4']['room'] ?></td>
								<td><?= $list_teacher['time4']['subject'] ?></td>
							</tr>
						<?php endif ?>
						<?php if (isset($list_teacher['time5']['time'])): ?>
							<tr>
								<td><?= $list_teacher['time5']['time'] ?></td>
								<td><?= $list_teacher['time5']['room'] ?></td>
								<td><?= $list_teacher['time5']['subject'] ?></td>
							</tr>
						<?php endif ?>
						<?php if (isset($list_teacher['time6']['time'])): ?>
							<tr>
								<td><?= $list_teacher['time6']['time'] ?></td>
								<td><?= $list_teacher['time6']['room'] ?></td>
								<td><?= $list_teacher['time6']['subject'] ?></td>
							</tr>
						<?php endif ?>
						<?php if (isset($list_teacher['time7']['time'])): ?>
							<tr>
								<td><?= $list_teacher['time7']['time'] ?></td>
								<td><?= $list_teacher['time7']['room'] ?></td>
								<td><?= $list_teacher['time7']['subject'] ?></td>
							</tr>
						<?php endif ?>
						<?php if (isset($list_teacher['time8']['time'])): ?>
							<tr>
								<td><?= $list_teacher['time8']['time'] ?></td>
								<td><?= $list_teacher['time8']['room'] ?></td>
								<td><?= $list_teacher['time8']['subject'] ?></td>
							</tr>
						<?php endif ?>
					</table>
			      </div>
			      <div class="modal-footer">
			        <label><?= $this->Html->link('View PDF', ['action'=>'teacherpdf', $list_teacher['id']], ['target'=>'_blank']); ?></label>
			      </div>
			    </div>
			  </div>
			</div>
		<?php
	}
	echo '<h3>Teacher\'s schedule</h3>';
	if (isset($teachers)) {
		echo $this->Form->control('teachers', ['options'=>$teachers, 'class'=>'form-control','onchange'=>'teacherSchedule(this.value)', 'empty'=>'Choose a teacher']);
	}
	$grade = [
		'7' => 'Grade 7',
        '8' => 'Grade 8',
        '9' => 'Grade 9',
        '10' => 'Grade 10',
        '11' => 'Grade 11',
        '12' => 'Grade 12',
	];
	echo '<h3>Room\'s schedule</h3>';
	if (isset($grade_junior) || isset($grade_senior)) {
		echo $this->Form->control('grade', ['options'=>$grade, 'class'=>'form-control','onchange'=>'searchroom(this.value)', 'empty'=>'Grade']);
		echo '<div class="rooms"><label for="room">Room</label><select name="rooms" id="rooms" class="form-control"><option value="">Rooms</option></select></div>';
	}
}
if (isset($grade_junior)) {
	$count = 1;
	foreach ($grade_junior as $key => $value) {
		if ($grade_junior[$key]['grade'] == 7) {
			?>
			<div class="modal fade" id="junior<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="junior<?= $key ?>" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel"><?= $grade_junior[$key]['room'] ?></h4>
			        <p>Adviser: <ins><?= $grade_junior[$key]['adviser'] ?></ins></p>
			      </div>
			      <div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<th>Time</th>
							<th>Teacher & Subjects</th>
						</tr>
						<tr>
							<td>6:50 AM - 7:30 AM</td>
							<td><?= $grade_junior[$key]['time1'] ?></td>
						</tr>
						<tr>
							<td>7:30 AM - 8:10 AM</td>
							<td><?= $grade_junior[$key]['time2'] ?></td>
						</tr>
						<tr>
							<td>8:10 AM - 8:50 AM</td>
							<td><?= $grade_junior[$key]['time3'] ?></td>
						</tr>
						<tr>
							<td><b>8:50 AM - 9:05 AM</b></td>
							<td><b>Recess</b></td>
						</tr>
						<tr>
							<td>9:05 AM - 9:45 AM</td>
							<td><?= $grade_junior[$key]['time4'] ?></td>
						</tr>
						<tr>
							<td>9:45 AM - 10:25 AM</td>
							<td><?= $grade_junior[$key]['time5'] ?></td>
						</tr>
						<tr>
							<td>10:25 AM - 11:05 AM</td>
							<td><?= $grade_junior[$key]['time6'] ?></td>
						</tr>
						<tr>
							<td>11:05 AM - 11:45 AM</td>
							<td><?= $grade_junior[$key]['time7'] ?></td>
						</tr>
						<tr>
							<td>11:45 AM - 12:25 PM</td>
							<td><?= $grade_junior[$key]['time8'] ?></td>
						</tr>
					</table>
			      </div>
			      <div class="modal-footer">
			        <p>Location: <ins><?= $grade_junior[$key]['building'] ?></ins></p>
			        <label><?= $this->Html->link('View PDF', ['action'=>'juniorpdf', 'junior', $key], ['target'=>'_blank']); ?></label>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
		}
	}
?>


<?php
$count = 1;
	foreach ($grade_junior as $key => $value) {
		if ($grade_junior[$key]['grade'] == 8) {
			?>
			<div class="modal fade" id="junior<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="junior<?= $key ?>" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel"><?= $grade_junior[$key]['room'] ?></h4>
			        <p>Adviser: <ins><?= $grade_junior[$key]['adviser'] ?></ins></p>
			      </div>
			      <div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<th>Time</th>
							<th>Teacher & Subjects</th>
						</tr>
						<tr>
							<td>6:50 AM - 7:30 AM</td>
							<td><?= $grade_junior[$key]['time1'] ?></td>
						</tr>
						<tr>
							<td>7:30 AM - 8:10 AM</td>
							<td><?= $grade_junior[$key]['time2'] ?></td>
						</tr>
						<tr>
							<td>8:10 AM - 8:50 AM</td>
							<td><?= $grade_junior[$key]['time3'] ?></td>
						</tr>
						<tr>
							<td><b>8:50 AM - 9:05 AM</b></td>
							<td><b>Recess</b></td>
						</tr>
						<tr>
							<td>9:05 AM - 9:45 AM</td>
							<td><?= $grade_junior[$key]['time4'] ?></td>
						</tr>
						<tr>
							<td>9:45 AM - 10:25 AM</td>
							<td><?= $grade_junior[$key]['time5'] ?></td>
						</tr>
						<tr>
							<td>10:25 AM - 11:05 AM</td>
							<td><?= $grade_junior[$key]['time6'] ?></td>
						</tr>
						<tr>
							<td>11:05 AM - 11:45 AM</td>
							<td><?= $grade_junior[$key]['time7'] ?></td>
						</tr>
						<tr>
							<td>11:45 AM - 12:25 PM</td>
							<td><?= $grade_junior[$key]['time8'] ?></td>
						</tr>
					</table>
			      </div>
			      <div class="modal-footer">
			        <p>Location: <ins><?= $grade_junior[$key]['building'] ?></ins></p>
			        <label><?= $this->Html->link('View PDF', ['action'=>'juniorpdf', 'junior', $key], ['target'=>'_blank']); ?></label>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
		}
	}
?>

<?php
$count = 1;
	foreach ($grade_junior as $key => $value) {
		if ($grade_junior[$key]['grade'] == 9) {
			?>
			<div class="modal fade" id="junior<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="junior<?= $key ?>" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel"><?= $grade_junior[$key]['room'] ?></h4>
			        <p>Adviser: <ins><?= $grade_junior[$key]['adviser'] ?></ins></p>
			      </div>
			      <div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<th>Time</th>
							<th>Teacher & Subjects</th>
						</tr>
						<tr>
							<td>12:30 PM - 1:10 PM</td>
							<td><?= $grade_junior[$key]['time1'] ?></td>
						</tr>
						<tr>
							<td>1:10 PM - 1:50 PM</td>
							<td><?= $grade_junior[$key]['time2'] ?></td>
						</tr>
						<tr>
							<td>1:50 PM - 2:30 PM</td>
							<td><?= $grade_junior[$key]['time3'] ?></td>
						</tr>
						<tr>
							<td>2:30 PM - 3:10 PM</td>
							<td><?= $grade_junior[$key]['time4'] ?></td>
						</tr>
						<tr>
							<td><b>3:10 PM - 3:25 PM</b></td>
							<td><b>Break</b></td>
						</tr>
						<tr>
							<td>3:25 PM - 4:05 PM</td>
							<td><?= $grade_junior[$key]['time5'] ?></td>
						</tr>
						<tr>
							<td>4:05 PM - 4:45 PM</td>
							<td><?= $grade_junior[$key]['time6'] ?></td>
						</tr>
						<tr>
							<td>4:45 PM - 5:25 PM</td>
							<td><?= $grade_junior[$key]['time7'] ?></td>
						</tr>
						<tr>
							<td>5:25 PM - 6:05 PM</td>
							<td><?= $grade_junior[$key]['time8'] ?></td>
						</tr>
					</table>
			      </div>
			      <div class="modal-footer">
			        <p>Location: <ins><?= $grade_junior[$key]['building'] ?></ins></p>
			        <label><?= $this->Html->link('View PDF', ['action'=>'juniorpdf', 'junior', $key], ['target'=>'_blank']); ?></label>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
		}
	}
?>

<?php
$count = 1;
	foreach ($grade_junior as $key => $value) {
		if ($grade_junior[$key]['grade'] == 10) {
			?>
			<div class="modal fade" id="junior<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="junior<?= $key ?>" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel"><?= $grade_junior[$key]['room'] ?></h4>
			        <p>Adviser: <ins><?= $grade_junior[$key]['adviser'] ?></ins></p>
			      </div>
			      <div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<th>Time</th>
							<th>Teacher & Subjects</th>
						</tr>
						<tr>
							<td>12:30 PM - 1:10 PM</td>
							<td><?= $grade_junior[$key]['time1'] ?></td>
						</tr>
						<tr>
							<td>1:10 PM - 1:50 PM</td>
							<td><?= $grade_junior[$key]['time2'] ?></td>
						</tr>
						<tr>
							<td>1:50 PM - 2:30 PM</td>
							<td><?= $grade_junior[$key]['time3'] ?></td>
						</tr>
						<tr>
							<td>2:30 PM - 3:10 PM</td>
							<td><?= $grade_junior[$key]['time4'] ?></td>
						</tr>
						<tr>
							<td><b>3:10 PM - 3:25 PM</b></td>
							<td><b>Break</b></td>
						</tr>
						<tr>
							<td>3:25 PM - 4:05 PM</td>
							<td><?= $grade_junior[$key]['time5'] ?></td>
						</tr>
						<tr>
							<td>4:05 PM - 4:45 PM</td>
							<td><?= $grade_junior[$key]['time6'] ?></td>
						</tr>
						<tr>
							<td>4:45 PM - 5:25 PM</td>
							<td><?= $grade_junior[$key]['time7'] ?></td>
						</tr>
						<tr>
							<td>5:25 PM - 6:05 PM</td>
							<td><?= $grade_junior[$key]['time8'] ?></td>
						</tr>
					</table>
			      </div>
			      <div class="modal-footer">
			        <p>Location: <ins><?= $grade_junior[$key]['building'] ?></ins></p>
			        <label><?= $this->Html->link('View PDF', ['action'=>'juniorpdf', 'junior', $key], ['target'=>'_blank']); ?></label>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
		}
	}}
?>


<?php
function cutRoom($room){
	$room = explode(" ",$room);
	return $room[0];
}
if (isset($grade_senior)) {
	$count = 1;
	foreach ($grade_senior as $key => $value) {
		if ($grade_senior[$key]['grade'] == 11 && (cutRoom($grade_senior[$key]['room']) == 'ABM' || cutRoom($grade_senior[$key]['room']) == 'GAS')) {
			?>
			<div class="modal fade" id="senior<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="senior<?= $key ?>" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel"><?= $grade_senior[$key]['room'] ?></h4>
			        <p>Adviser: <ins><?= $grade_senior[$key]['adviser'] ?></ins></p>
			      </div>
			      <div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<th>Time</th>
							<th>Teacher & Subjects</th>
						</tr>
						<tr>
							<td>7:30 AM - 8:30 AM</td>
							<td><?= $grade_senior[$key]['time1'] ?></td>
						</tr>
						<tr>
							<td>8:30 AM - 9:30 AM</td>
							<td><?= $grade_senior[$key]['time2'] ?></td>
						</tr>
						<tr>
							<td><b>9:30 AM - 10:00 AM</b></td>
							<td><b>Recess</b></td>
						</tr>
						<tr>
							<td>10:00 AM - 11:00 AM</td>
							<td><?= $grade_senior[$key]['time3'] ?></td>
						</tr>
						<tr>
							<td>11:00 AM - 12:00 PM</td>
							<td><?= $grade_senior[$key]['time4'] ?></td>
						</tr>
						<tr>
							<td><b>12:00 PM - 1:00 PM</b></td>
							<td><b>Lunch Break</b></td>
						</tr>
						<tr>
							<td>1:00 PM - 2:00 PM</td>
							<td><?= $grade_senior[$key]['time5'] ?></td>
						</tr>
						<tr>
							<td>2:00 PM - 3:00 PM</td>
							<td><?= $grade_senior[$key]['time6'] ?></td>
						</tr>
						<tr>
							<td>3:00 PM - 4:00 PM</td>
							<td><?= $grade_senior[$key]['time7'] ?></td>
						</tr>
						<tr>
							<td>4:00 PM - 5:00 PM</td>
							<td><?= $grade_senior[$key]['time8'] ?></td>
						</tr>
					</table>
			      </div>
			      <div class="modal-footer">
			        <p>Location: <ins><?= $grade_junior[$key]['building'] ?></ins></p>
			        <label><?= $this->Html->link('View PDF', ['action'=>'seniorpdf', $key], ['target'=>'_blank']); ?></label>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
		}
	}

	foreach ($grade_senior as $key => $value) {
		if ($grade_senior[$key]['grade'] == 11 && (cutRoom($grade_senior[$key]['room']) == 'STEM' || cutRoom($grade_senior[$key]['room']) == 'TVL')) {
			?>
			<div class="modal fade" id="senior<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="senior<?= $key ?>" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel"><?= $grade_senior[$key]['room'] ?></h4>
			        <p>Adviser: <ins><?= $grade_senior[$key]['adviser'] ?></ins></p>
			      </div>
			      <div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<th>Time</th>
							<th>Teacher & Subjects</th>
						</tr>
						<tr>
							<td>7:30 AM - 8:30 AM</td>
							<td><?= $grade_senior[$key]['time1'] ?></td>
						</tr>
						<tr>
							<td>8:30 AM - 9:30 AM</td>
							<td><?= $grade_senior[$key]['time2'] ?></td>
						</tr>
						<tr>
							<td><b>9:30 AM - 10:00 AM</b></td>
							<td><b>Recess</b></td>
						</tr>
						<tr>
							<td>10:00 AM - 11:00 AM</td>
							<td><?= $grade_senior[$key]['time3'] ?></td>
						</tr>
						<tr>
							<td>11:00 AM - 12:00 PM</td>
							<td><?= $grade_senior[$key]['time4'] ?></td>
						</tr>
						<tr>
							<td><b>12:00 PM - 1:00 PM</b></td>
							<td><b>Lunch Break</b></td>
						</tr>
						<tr>
							<td>1:00 PM - 2:00 PM</td>
							<td><?= $grade_senior[$key]['time5'] ?></td>
						</tr>
						<tr>
							<td>2:00 PM - 3:00 PM</td>
							<td><?= $grade_senior[$key]['time6'] ?></td>
						</tr>
						<tr>
							<td>3:00 PM - 4:00 PM</td>
							<td><?= $grade_senior[$key]['time7'] ?></td>
						</tr>
					</table>
			      </div>
			      <div class="modal-footer">
			        <p>Location: <ins><?= $grade_junior[$key]['building'] ?></ins></p>
			        <label><?= $this->Html->link('View PDF', ['action'=>'seniorpdf', $key], ['target'=>'_blank']); ?></label>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
		}
	}
?>

<?php
$count = 1;
	foreach ($grade_senior as $key => $value) {
		if ($grade_senior[$key]['grade'] == 12 && (cutRoom($grade_senior[$key]['room']) == 'ABM' || cutRoom($grade_senior[$key]['room']) == 'STEM' || cutRoom($grade_senior[$key]['room']) == 'GAS')) {
			?>
			<div class="modal fade" id="senior<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="senior<?= $key ?>" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel"><?= $grade_senior[$key]['room'] ?></h4>
			        <p>Adviser: <ins><?= $grade_senior[$key]['adviser'] ?></ins></p>
			      </div>
			      <div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<th>Time</th>
							<th>Teacher & Subjects</th>
						</tr>
						<tr>
							<td>7:30 AM - 8:30 AM</td>
							<td><?= $grade_senior[$key]['time1'] ?></td>
						</tr>
						<tr>
							<td>8:30 AM - 9:30 AM</td>
							<td><?= $grade_senior[$key]['time2'] ?></td>
						</tr>
						<tr>
							<td><b>9:30 AM - 10:00 AM</b></td>
							<td><b>Recess</b></td>
						</tr>
						<tr>
							<td>10:00 AM - 11:00 AM</td>
							<td><?= $grade_senior[$key]['time3'] ?></td>
						</tr>
						<tr>
							<td>11:00 AM - 12:00 PM</td>
							<td><?= $grade_senior[$key]['time4'] ?></td>
						</tr>
						<tr>
							<td><b>12:00 PM - 1:00 PM</b></td>
							<td><b>Lunch Break</b></td>
						</tr>
						<tr>
							<td>1:00 PM - 2:00 PM</td>
							<td><?= $grade_senior[$key]['time5'] ?></td>
						</tr>
						<tr>
							<td>2:00 PM - 3:00 PM</td>
							<td><?= $grade_senior[$key]['time6'] ?></td>
						</tr>
						<tr>
							<td>3:00 PM - 4:00 PM</td>
							<td><?= $grade_senior[$key]['time7'] ?></td>
						</tr>
					</table>
			      </div>
			      <div class="modal-footer">
			        <p>Location: <ins><?= $grade_junior[$key]['building'] ?></ins></p>
			        <label><?= $this->Html->link('View PDF', ['action'=>'seniorpdf', $key], ['target'=>'_blank']); ?></label>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
		}
	}
	foreach ($grade_senior as $key => $value) {
		if ($grade_senior[$key]['grade'] == 12 && (cutRoom($grade_senior[$key]['room']) == 'TVL')) {
			?>
			<div class="modal fade" id="senior<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="senior<?= $key ?>" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel"><?= $grade_senior[$key]['room'] ?></h4>
			        <p>Adviser: <ins><?= $grade_senior[$key]['adviser'] ?></ins></p>
			      </div>
			      <div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<th>Time</th>
							<th>Teacher & Subjects</th>
						</tr>
						<tr>
							<td>7:30 AM - 8:30 AM</td>
							<td><?= $grade_senior[$key]['time1'] ?></td>
						</tr>
						<tr>
							<td>8:30 AM - 9:30 AM</td>
							<td><?= $grade_senior[$key]['time2'] ?></td>
						</tr>
						<tr>
							<td><b>9:30 AM - 10:00 AM</b></td>
							<td><b>Recess</b></td>
						</tr>
						<tr>
							<td>10:00 AM - 11:00 AM</td>
							<td><?= $grade_senior[$key]['time3'] ?></td>
						</tr>
						<tr>
							<td>11:00 AM - 12:00 PM</td>
							<td><?= $grade_senior[$key]['time4'] ?></td>
						</tr>
						<tr>
							<td><b>12:00 PM - 1:00 PM</b></td>
							<td><b>Lunch Break</b></td>
						</tr>
						<tr>
							<td>1:00 PM - 2:00 PM</td>
							<td><?= $grade_senior[$key]['time5'] ?></td>
						</tr>
						<tr>
							<td>2:00 PM - 3:00 PM</td>
							<td><?= $grade_senior[$key]['time6'] ?></td>
						</tr>
					</table>
			      </div>
			      <div class="modal-footer">
			        <p>Location: <ins><?= $grade_junior[$key]['building'] ?></ins></p>
			        <label><?= $this->Html->link('View PDF', ['action'=>'seniorpdf', $key], ['target'=>'_blank']); ?></label>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
		}
	}}
?>

