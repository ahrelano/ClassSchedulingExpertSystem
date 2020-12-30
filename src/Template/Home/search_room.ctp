<?php
if ($grade == 7 || $grade == 8 || $grade == 9 || $grade == 10) {
	foreach ($grade_junior as $key => $value) {
		if ($grade_junior[$key]['grade'] == $grade) {
			$rooms[$key] = $grade_junior[$key]['room'];
		}
	}
?>
<script>
function roomSchedule(val){
	if (val != "") {
	    $('#junior'+val).modal({
	        show: true
	    });
	}
}
</script>
<?php
}else if($grade == 11 || $grade == 12){
	foreach ($grade_senior as $key => $value) {
		if ($grade_senior[$key]['grade'] == $grade) {
			$rooms[$key] = $grade_senior[$key]['room'];
		}
	}
?>
<script>
function roomSchedule(val){
	if (val != "") {
	    $('#senior'+val).modal({
	        show: true
	    });
	}
}
</script>
<?php
}
echo $this->Form->control('room', ['options'=>$rooms, 'class'=>'form-control','onchange'=>'roomSchedule(this.value)']);
?>