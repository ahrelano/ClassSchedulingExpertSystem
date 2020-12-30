<?php
namespace App\Controller;
require_once ROOT . DS . 'src/Lib/fpdf/fpdf.php';
require_once ROOT . DS . 'src/Lib/fpdf/html_table.php';

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\App;

class HomeController extends AppController
{
	public function beforeFilter(Event $event){
		$this->Auth->allow();
	}

	public function seniorpdf($id=null){
		$this->viewBuilder()->layout('');
		$this->loadModel('Teachers');
		$this->loadModel('Rooms');
		$rooms = $this->Rooms->find('all', ['contain'=>['Buildings']])->where(['Rooms.grade'>0]);
		$teachers = $this->Teachers->find('all', ['contain'=>['Positions','Subjects']])->where(['Teachers.generate'=>1])->order(['Teachers.lastname']);
			$subjects = ['AP', 'TLE', 'MAPEH', 'Science', 'Math', 'English', 'Filipino', 'ESP'];
	$days = ['M','T','W', 'Th','F'];
	$ap = 0;
	$tle = 0;
	$mapeh = 0;
	$science = 0;
	$math = 0;
	$english = 0;
	$filipino = 0;
	foreach ($teachers as $teacher) {
		if ($teacher->subject_id > 0 && $teacher->loads > 0) {
			$list_teachers[] = [
								'id' => $teacher->id,
								'teacher' => $teacher->lastname.', '.$teacher->firstname.' '.$teacher->middle.'.',
								'advise' => '',
								'major' => $teacher->subject->subject,
								'loads' => $teacher->loads,
								'time1' => '',
								'time2' => '',
								'time3' => '',
								'time4' => '',
								'time5' => '',
								'time6' => '',
								'time7' => '',
								'time8' => '',
								];
			if ($teacher->subject->subject == 'AP') {
				$ap = $ap + $teacher->loads;
			}else if ($teacher->subject->subject == 'TLE') {
				$tle = $tle + $teacher->loads;
			}else if ($teacher->subject->subject == 'MAPEH') {
				$mapeh = $mapeh + $teacher->loads;
			}else if ($teacher->subject->subject == 'Science') {
				$science = $science + $teacher->loads;
			}else if ($teacher->subject->subject == 'Math') {
				$math = $math + $teacher->loads;
			}else if ($teacher->subject->subject == 'English') {
				$english = $english + $teacher->loads;
			}else if ($teacher->subject->subject == 'Filipino') {
				$filipino = $filipino + $teacher->loads;
			}
		}
	}

	$room_limits = 0;
	foreach ($rooms as $room) {
		if ($room->grade == 7 || $room->grade == 8 || $room->grade == 9 || $room->grade == 10) {
			$room_limits = $room_limits + 1;
			$grade_junior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
				'time8' => '',
			]; 
		}
	}

	$room_limits2 = 0;
	foreach ($rooms as $room) {
		if(($this->cutRoom($room->room) == 'ABM' || $this->cutRoom($room->room) == 'GAS' ) && $room->grade == 11){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
				'time8' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'STEM' && ($room->grade == 11 || $room->grade == 12)){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'TVL' && $room->grade == 11){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if(($this->cutRoom($room->room) == 'GAS' || $this->cutRoom($room->room) == 'ABM' || $this->cutRoom($room->room) == 'STEM') && $room->grade == 12){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'TVL' && $room->grade == 12){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
			]; 
		}
	}
	$teacher_limits = [
					'AP' => $ap - $room_limits,
					'TLE' => $tle - $room_limits,
					'MAPEH' => $mapeh - $room_limits,
					'Science' => $science - $room_limits + $room_limits2,
					'Math' => $math - $room_limits,
					'English' => $english - $room_limits,
					'Filipino' => $filipino - $room_limits,
			];
	$junior_abm_subjects = ['Gen. Math', 'Komunikasyon', 'Research In Daily Life', 'English For Academic', 'Oral Comm', 'Earth & Life Science', 'PER DEV.', 'Understanding Culture'];
	$junior_stem_subjects = ['Earth & Life Science', 'Pre-Calculus', 'Gen. Math', 'Komunikasyon', 'Intro to Philosophy', 'PER DEV.', 'Oral Comm'];
	$junior_gas_subjects = ['Understanding Culture', 'English For Academic', 'Oral Comm', 'Earth & Life Science', 'PER DEV.','Komunikasyon', 'Gen. Math', 'Research In Daily Life'];
	$junior_tvl_subjects = ['Komunikasyon', 'PER DEV.', 'English For Academic', 'Oral Comm', 'Filipino sa Piling Larangan', 'Bread and Pastry', 'Gen. Math'];
	$junior_abm_subjects2 = ['Research In Daily Life 2', 'Intro to Philosophy', 'Empowerment Technologies', 'Organization and Management', 'Fundamentals of Accounting 2', 'Pagsulat sa Pilipino', 'Business Finance'];
	$junior_stem_subjects2 = ['Understanding Culture', 'PAGBASA', 'Research In Daily Life 1', 'General Chemistry 1', 'English For Academic', 'Disaster Readiness', 'Empowerment Technologies'];
	$junior_gas_subjects2 = ['Business Math', 'Empowerment Technologies', 'Organization and Management', 'Pagsulat sa Pilipino', 'Intro to Philosophy', 'Research In Daily Life 2', 'Physical Science'];
	$junior_tvl_subjects2 = ['Bread and Pastry', 'Intro to Philosophy', 'Empowerment Technologies', 'Earth & Life Science', 'Understanding Culture', 'Research In Daily Life 1'];

if (isset($list_teachers) && isset($rooms)) {
	foreach ($list_teachers as $key => $find_pe) {
		if ($list_teachers[$key]['major'] == 'P.E') {
			$pe_teacher[] = $key;
		}
	}

	if (isset($grade_senior)) {
	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'ABM' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_abm_subjects = $this->sorting_list($junior_abm_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_abm_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'STEM' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_stem_subjects = $this->sorting_list($junior_stem_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_stem_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Math' && $subject == 'Pre-Calculus') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'GAS' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_gas_subjects = $this->sorting_list($junior_gas_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_gas_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && $list_teachers[$key]['major'] == 'English' && $subject == 'PER DEV.' && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'TVL' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_tvl_subjects = $this->sorting_list($junior_tvl_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_tvl_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'MAE-Math' || $list_teacher['major'] == 'Math') && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Filipino sa Piling Larangan') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Bread and Pastry') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'ABM' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_abm_subjects2 = $this->sorting_list($junior_abm_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_abm_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'MAE-Math' || $list_teacher['major'] == 'Math') && $subject == 'Fundamentals of Accounting 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Pagsulat sa Pilipino') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Organization and Management') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'CPA Master in Business Management' || $list_teacher['major'] == 'TLE') && $subject == 'Research In Daily Life 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Business Finance') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'STEM' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_stem_subjects2 = $this->sorting_list($junior_stem_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_stem_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Disaster Readiness') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'General Chemistry 1') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life 1') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'PAGBASA') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if ($pe_teacher) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'GAS' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_gas_subjects2 = $this->sorting_list($junior_gas_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_gas_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Organization and Management') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'CPA Master in Business Management' || $list_teacher['major'] == 'TLE') && $subject == 'Research In Daily Life 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'Physical Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Pagsulat sa Pilipino') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Math' && $subject == 'Business Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'TVL' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_tvl_subjects2 = $this->sorting_list($junior_tvl_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_tvl_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Bread and Pastry')  && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}}


	foreach ($grade_junior as $key_7 => $value) {
		$subjects = $this->sorting_list($subjects);
		$days = $this->sorting_list($days);
		foreach ($subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
			foreach ($list_teachers as $key => $list_teacher) {
				if ($grade_junior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '' && $this->checkjuniorMajor($list_teachers[$key]['major']) && $this->checkifJunior($grade_junior[$key_7]['grade'])) {
					$grade_junior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'];
				}
				if ($list_teachers[$key]['loads'] > 0 && $list_teacher['major'] == $subject && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && $this->checkifJunior($grade_junior[$key_7]['grade'])) {
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $list_teacher['major'] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => $list_teacher['major'],
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'ESP') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Filipino' && $subject == 'AP') && $teacher_limits['Filipino'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Filipino'] = $teacher_limits['Filipino'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(AP)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'AP',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'AP') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(AP)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'AP',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Math' && $subject == 'ESP') && $teacher_limits['Math'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Math'] = $teacher_limits['Math'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'English' && $subject == 'ESP') && $teacher_limits['English'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['English'] = $teacher_limits['English'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
					];  
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Filipino' && $subject == 'ESP') && $teacher_limits['Filipino'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Filipino'] = $teacher_limits['Filipino'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'English' && $subject == 'Filipino') && $teacher_limits['English'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['English'] = $teacher_limits['English'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(Filipino)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'Filipino ('.$days[1].$days[2].$days[3].')',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'Math') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(Math)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'Math ('.$days[1].$days[2].$days[3].')',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}
			}}
		}	
	}	
		foreach ($grade_senior as $key => $value) {
			if ($key == $id && $grade_senior[$key]['grade'] == 11 && ($this->cutRoom($grade_senior[$key]['room']) == 'ABM' || $this->cutRoom($grade_senior[$key]['room']) == 'GAS')) {
				$sched = [
					'room'=>$grade_senior[$key]['room'],
					'cutroom'=>$this->cutRoom($grade_senior[$key]['room']),
					'grade'=>$grade_senior[$key]['grade'],
					'adviser'=> $grade_senior[$key]['adviser'],
					'time1' => $grade_senior[$key]['time1'],
					'time2' => $grade_senior[$key]['time2'],
					'time3' => $grade_senior[$key]['time3'],
					'time4' => $grade_senior[$key]['time4'],
					'time5' => $grade_senior[$key]['time5'],
					'time6' => $grade_senior[$key]['time6'],
					'time7' => $grade_senior[$key]['time7'],
					'time8' => $grade_senior[$key]['time8'],
					'building' => $grade_senior[$key]['building']
				];
			}else if ($key == $id && $grade_senior[$key]['grade'] == 11 && ($this->cutRoom($grade_senior[$key]['room']) == 'STEM' || $this->cutRoom($grade_senior[$key]['room']) == 'TVL')) {
				$sched = [
					'room'=>$grade_senior[$key]['room'],
					'cutroom'=>$this->cutRoom($grade_senior[$key]['room']),
					'grade'=>$grade_senior[$key]['grade'],
					'adviser'=> $grade_senior[$key]['adviser'],
					'time1' => $grade_senior[$key]['time1'],
					'time2' => $grade_senior[$key]['time2'],
					'time3' => $grade_senior[$key]['time3'],
					'time4' => $grade_senior[$key]['time4'],
					'time5' => $grade_senior[$key]['time5'],
					'time6' => $grade_senior[$key]['time6'],
					'time7' => $grade_senior[$key]['time7'],
					'building' => $grade_senior[$key]['building']
				];
			}else if ($key == $id && $grade_senior[$key]['grade'] == 12 && ($this->cutRoom($grade_senior[$key]['room']) == 'ABM' || $this->cutRoom($grade_senior[$key]['room']) == 'STEM' || $this->cutRoom($grade_senior[$key]['room']) == 'GAS')) {
				$sched = [
					'room'=>$grade_senior[$key]['room'],
					'cutroom'=>$this->cutRoom($grade_senior[$key]['room']),
					'grade'=>$grade_senior[$key]['grade'],
					'adviser'=> $grade_senior[$key]['adviser'],
					'time1' => $grade_senior[$key]['time1'],
					'time2' => $grade_senior[$key]['time2'],
					'time3' => $grade_senior[$key]['time3'],
					'time4' => $grade_senior[$key]['time4'],
					'time5' => $grade_senior[$key]['time5'],
					'time6' => $grade_senior[$key]['time6'],
					'time7' => $grade_senior[$key]['time7'],
					'building' => $grade_senior[$key]['building']
				];
			}else if ($key == $id && $grade_senior[$key]['grade'] == 12 && $this->cutRoom($grade_senior[$key]['room']) == 'TVL') {
				$sched = [
					'room'=>$grade_senior[$key]['room'],
					'cutroom'=>$this->cutRoom($grade_senior[$key]['room']),
					'grade'=>$grade_senior[$key]['grade'],
					'adviser'=> $grade_senior[$key]['adviser'],
					'time1' => $grade_senior[$key]['time1'],
					'time2' => $grade_senior[$key]['time2'],
					'time3' => $grade_senior[$key]['time3'],
					'time4' => $grade_senior[$key]['time4'],
					'time5' => $grade_senior[$key]['time5'],
					'time6' => $grade_senior[$key]['time6'],
					'building' => $grade_senior[$key]['building']
				];
			}
		}
		$this->set(compact('sched', 'grade_senior'));
		$this->render()->type('application/pdf'); //Rendering the pdf
	}

	public function juniorpdf($grade=null,$id=null){
		$this->viewBuilder()->layout('');
		$this->loadModel('Teachers');
		$this->loadModel('Rooms');
	$rooms = $this->Rooms->find('all', ['contain'=>['Buildings']])->where(['Rooms.grade'>0]);
		$teachers = $this->Teachers->find('all', ['contain'=>['Positions','Subjects']])->where(['Teachers.generate'=>1])->order(['Teachers.lastname']);
			$subjects = ['AP', 'TLE', 'MAPEH', 'Science', 'Math', 'English', 'Filipino', 'ESP'];
	$days = ['M','T','W', 'Th','F'];
	$ap = 0;
	$tle = 0;
	$mapeh = 0;
	$science = 0;
	$math = 0;
	$english = 0;
	$filipino = 0;
	foreach ($teachers as $teacher) {
		if ($teacher->subject_id > 0 && $teacher->loads > 0) {
			$list_teachers[] = [
								'id' => $teacher->id,
								'teacher' => $teacher->lastname.', '.$teacher->firstname.' '.$teacher->middle.'.',
								'advise' => '',
								'major' => $teacher->subject->subject,
								'loads' => $teacher->loads,
								'time1' => '',
								'time2' => '',
								'time3' => '',
								'time4' => '',
								'time5' => '',
								'time6' => '',
								'time7' => '',
								'time8' => '',
								];
			if ($teacher->subject->subject == 'AP') {
				$ap = $ap + $teacher->loads;
			}else if ($teacher->subject->subject == 'TLE') {
				$tle = $tle + $teacher->loads;
			}else if ($teacher->subject->subject == 'MAPEH') {
				$mapeh = $mapeh + $teacher->loads;
			}else if ($teacher->subject->subject == 'Science') {
				$science = $science + $teacher->loads;
			}else if ($teacher->subject->subject == 'Math') {
				$math = $math + $teacher->loads;
			}else if ($teacher->subject->subject == 'English') {
				$english = $english + $teacher->loads;
			}else if ($teacher->subject->subject == 'Filipino') {
				$filipino = $filipino + $teacher->loads;
			}
		}
	}

	$room_limits = 0;
	foreach ($rooms as $room) {
		if ($room->grade == 7 || $room->grade == 8 || $room->grade == 9 || $room->grade == 10) {
			$room_limits = $room_limits + 1;
			$grade_junior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
				'time8' => '',
			]; 
		}
	}

	$room_limits2 = 0;
	foreach ($rooms as $room) {
		if(($this->cutRoom($room->room) == 'ABM' || $this->cutRoom($room->room) == 'GAS' ) && $room->grade == 11){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
				'time8' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'STEM' && ($room->grade == 11 || $room->grade == 12)){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'TVL' && $room->grade == 11){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if(($this->cutRoom($room->room) == 'GAS' || $this->cutRoom($room->room) == 'ABM' || $this->cutRoom($room->room) == 'STEM') && $room->grade == 12){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'TVL' && $room->grade == 12){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
			]; 
		}
	}
	$teacher_limits = [
					'AP' => $ap - $room_limits,
					'TLE' => $tle - $room_limits,
					'MAPEH' => $mapeh - $room_limits,
					'Science' => $science - $room_limits + $room_limits2,
					'Math' => $math - $room_limits,
					'English' => $english - $room_limits,
					'Filipino' => $filipino - $room_limits,
			];
	$junior_abm_subjects = ['Gen. Math', 'Komunikasyon', 'Research In Daily Life', 'English For Academic', 'Oral Comm', 'Earth & Life Science', 'PER DEV.', 'Understanding Culture'];
	$junior_stem_subjects = ['Earth & Life Science', 'Pre-Calculus', 'Gen. Math', 'Komunikasyon', 'Intro to Philosophy', 'PER DEV.', 'Oral Comm'];
	$junior_gas_subjects = ['Understanding Culture', 'English For Academic', 'Oral Comm', 'Earth & Life Science', 'PER DEV.','Komunikasyon', 'Gen. Math', 'Research In Daily Life'];
	$junior_tvl_subjects = ['Komunikasyon', 'PER DEV.', 'English For Academic', 'Oral Comm', 'Filipino sa Piling Larangan', 'Bread and Pastry', 'Gen. Math'];
	$junior_abm_subjects2 = ['Research In Daily Life 2', 'Intro to Philosophy', 'Empowerment Technologies', 'Organization and Management', 'Fundamentals of Accounting 2', 'Pagsulat sa Pilipino', 'Business Finance'];
	$junior_stem_subjects2 = ['Understanding Culture', 'PAGBASA', 'Research In Daily Life 1', 'General Chemistry 1', 'English For Academic', 'Disaster Readiness', 'Empowerment Technologies'];
	$junior_gas_subjects2 = ['Business Math', 'Empowerment Technologies', 'Organization and Management', 'Pagsulat sa Pilipino', 'Intro to Philosophy', 'Research In Daily Life 2', 'Physical Science'];
	$junior_tvl_subjects2 = ['Bread and Pastry', 'Intro to Philosophy', 'Empowerment Technologies', 'Earth & Life Science', 'Understanding Culture', 'Research In Daily Life 1'];

if (isset($list_teachers) && isset($rooms)) {
	foreach ($list_teachers as $key => $find_pe) {
		if ($list_teachers[$key]['major'] == 'P.E') {
			$pe_teacher[] = $key;
		}
	}

	if (isset($grade_senior)) {
	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'ABM' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_abm_subjects = $this->sorting_list($junior_abm_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_abm_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'STEM' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_stem_subjects = $this->sorting_list($junior_stem_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_stem_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Math' && $subject == 'Pre-Calculus') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'GAS' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_gas_subjects = $this->sorting_list($junior_gas_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_gas_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && $list_teachers[$key]['major'] == 'English' && $subject == 'PER DEV.' && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'TVL' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_tvl_subjects = $this->sorting_list($junior_tvl_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_tvl_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'];
					}
					if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'MAE-Math' || $list_teacher['major'] == 'Math') && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Filipino sa Piling Larangan') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Bread and Pastry') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'ABM' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_abm_subjects2 = $this->sorting_list($junior_abm_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_abm_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'MAE-Math' || $list_teacher['major'] == 'Math') && $subject == 'Fundamentals of Accounting 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Pagsulat sa Pilipino') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Organization and Management') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'CPA Master in Business Management' || $list_teacher['major'] == 'TLE') && $subject == 'Research In Daily Life 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Business Finance') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'STEM' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_stem_subjects2 = $this->sorting_list($junior_stem_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_stem_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Disaster Readiness') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'General Chemistry 1') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life 1') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'PAGBASA') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if ($pe_teacher) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'GAS' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_gas_subjects2 = $this->sorting_list($junior_gas_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_gas_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Organization and Management') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'CPA Master in Business Management' || $list_teacher['major'] == 'TLE') && $subject == 'Research In Daily Life 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'Physical Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Pagsulat sa Pilipino') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Math' && $subject == 'Business Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'TVL' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_tvl_subjects2 = $this->sorting_list($junior_tvl_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_tvl_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Bread and Pastry')  && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $value['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}}



	foreach ($grade_junior as $key_7 => $value) {
		$subjects = $this->sorting_list($subjects);
		$days = $this->sorting_list($days);
		foreach ($subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
			foreach ($list_teachers as $key => $list_teacher) {
				if ($grade_junior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '' && $this->checkjuniorMajor($list_teachers[$key]['major']) && $this->checkifJunior($grade_junior[$key_7]['grade'])) {
					$grade_junior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'];
				}
				if ($list_teachers[$key]['loads'] > 0 && $list_teacher['major'] == $subject && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && $this->checkifJunior($grade_junior[$key_7]['grade'])) {
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $list_teacher['major'] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => $list_teacher['major'],
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'ESP') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Filipino' && $subject == 'AP') && $teacher_limits['Filipino'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Filipino'] = $teacher_limits['Filipino'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(AP)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'AP',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'AP') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(AP)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'AP',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Math' && $subject == 'ESP') && $teacher_limits['Math'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Math'] = $teacher_limits['Math'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'English' && $subject == 'ESP') && $teacher_limits['English'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['English'] = $teacher_limits['English'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
					];  
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Filipino' && $subject == 'ESP') && $teacher_limits['Filipino'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Filipino'] = $teacher_limits['Filipino'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'English' && $subject == 'Filipino') && $teacher_limits['English'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['English'] = $teacher_limits['English'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(Filipino)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'Filipino ('.$days[1].$days[2].$days[3].')',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'Math') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(Math)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'Math ('.$days[1].$days[2].$days[3].')',
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}
			}}
		}	
	}	
	if ($grade == 'junior') {
		foreach ($grade_junior as $key => $value) {
			if ($key == $id) {
				$sched = [
					'room'=>$grade_junior[$key]['room'],
					'grade'=>$grade_junior[$key]['grade'],
					'adviser'=> $grade_junior[$key]['adviser'],
					'time1' => $grade_junior[$key]['time1'],
					'time2' => $grade_junior[$key]['time2'],
					'time3' => $grade_junior[$key]['time3'],
					'time4' => $grade_junior[$key]['time4'],
					'time5' => $grade_junior[$key]['time5'],
					'time6' => $grade_junior[$key]['time6'],
					'time7' => $grade_junior[$key]['time7'],
					'time8' => $grade_junior[$key]['time8'],
					'building' => $grade_junior[$key]['building']
				];
			}
		}
	}
		$this->set(compact('sched'));
		$this->render()->type('application/pdf'); //Rendering the pdf
	}

	public function index(){
		$this->viewBuilder()->layout('home');
		$this->loadModel('Teachers');
		$this->loadModel('Rooms');
$rooms = $this->Rooms->find('all', ['contain'=>['Buildings']])->where(['Rooms.grade'>0]);
		$teachers = $this->Teachers->find('all', ['contain'=>['Positions','Subjects']])->where(['Teachers.generate'=>1])->order(['Teachers.lastname']);
			$subjects = ['AP', 'TLE', 'MAPEH', 'Science', 'Math', 'English', 'Filipino', 'ESP'];
	$days = ['M','T','W', 'Th','F'];
	$ap = 0;
	$tle = 0;
	$mapeh = 0;
	$science = 0;
	$math = 0;
	$english = 0;
	$filipino = 0;
	foreach ($teachers as $teacher) {
		if ($teacher->subject_id > 0 && $teacher->loads > 0) {
			$list_teachers[] = [
								'id' => $teacher->id,
								'teacher' => $teacher->lastname.', '.$teacher->firstname.' '.$teacher->middle.'.',
								'advise' => '',
								'major' => $teacher->subject->subject,
								'loads' => $teacher->loads,
								'time1' => '',
								'time2' => '',
								'time3' => '',
								'time4' => '',
								'time5' => '',
								'time6' => '',
								'time7' => '',
								'time8' => '',
								];
			if ($teacher->subject->subject == 'AP') {
				$ap = $ap + $teacher->loads;
			}else if ($teacher->subject->subject == 'TLE') {
				$tle = $tle + $teacher->loads;
			}else if ($teacher->subject->subject == 'MAPEH') {
				$mapeh = $mapeh + $teacher->loads;
			}else if ($teacher->subject->subject == 'Science') {
				$science = $science + $teacher->loads;
			}else if ($teacher->subject->subject == 'Math') {
				$math = $math + $teacher->loads;
			}else if ($teacher->subject->subject == 'English') {
				$english = $english + $teacher->loads;
			}else if ($teacher->subject->subject == 'Filipino') {
				$filipino = $filipino + $teacher->loads;
			}
		}
	}

	$room_limits = 0;
	foreach ($rooms as $room) {
		if ($room->grade == 7 || $room->grade == 8 || $room->grade == 9 || $room->grade == 10) {
			$room_limits = $room_limits + 1;
			$grade_junior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
				'time8' => '',
			]; 
		}
	}

	$room_limits2 = 0;
	foreach ($rooms as $room) {
		if(($this->cutRoom($room->room) == 'ABM' || $this->cutRoom($room->room) == 'GAS' ) && $room->grade == 11){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
				'time8' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'STEM' && ($room->grade == 11 || $room->grade == 12)){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'TVL' && $room->grade == 11){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if(($this->cutRoom($room->room) == 'GAS' || $this->cutRoom($room->room) == 'ABM' || $this->cutRoom($room->room) == 'STEM') && $room->grade == 12){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'TVL' && $room->grade == 12){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
			]; 
		}
	}
	$teacher_limits = [
					'AP' => $ap - $room_limits,
					'TLE' => $tle - $room_limits,
					'MAPEH' => $mapeh - $room_limits,
					'Science' => $science - $room_limits + $room_limits2,
					'Math' => $math - $room_limits,
					'English' => $english - $room_limits,
					'Filipino' => $filipino - $room_limits,
			];
	$junior_abm_subjects = ['Gen. Math', 'Komunikasyon', 'Research In Daily Life', 'English For Academic', 'Oral Comm', 'Earth & Life Science', 'PER DEV.', 'Understanding Culture'];
	$junior_stem_subjects = ['Earth & Life Science', 'Pre-Calculus', 'Gen. Math', 'Komunikasyon', 'Intro to Philosophy', 'PER DEV.', 'Oral Comm'];
	$junior_gas_subjects = ['Understanding Culture', 'English For Academic', 'Oral Comm', 'Earth & Life Science', 'PER DEV.','Komunikasyon', 'Gen. Math', 'Research In Daily Life'];
	$junior_tvl_subjects = ['Komunikasyon', 'PER DEV.', 'English For Academic', 'Oral Comm', 'Filipino sa Piling Larangan', 'Bread and Pastry', 'Gen. Math'];
	$junior_abm_subjects2 = ['Research In Daily Life 2', 'Intro to Philosophy', 'Empowerment Technologies', 'Organization and Management', 'Fundamentals of Accounting 2', 'Pagsulat sa Pilipino', 'Business Finance'];
	$junior_stem_subjects2 = ['Understanding Culture', 'PAGBASA', 'Research In Daily Life 1', 'General Chemistry 1', 'English For Academic', 'Disaster Readiness', 'Empowerment Technologies'];
	$junior_gas_subjects2 = ['Business Math', 'Empowerment Technologies', 'Organization and Management', 'Pagsulat sa Pilipino', 'Intro to Philosophy', 'Research In Daily Life 2', 'Physical Science'];
	$junior_tvl_subjects2 = ['Bread and Pastry', 'Intro to Philosophy', 'Empowerment Technologies', 'Earth & Life Science', 'Understanding Culture', 'Research In Daily Life 1'];

if (isset($list_teachers) && isset($rooms)) {
	foreach ($list_teachers as $key => $find_pe) {
		if ($list_teachers[$key]['major'] == 'P.E') {
			$pe_teacher[] = $key;
		}
	}
if (isset($grade_senior)) {
	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'ABM' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_abm_subjects = $this->sorting_list($junior_abm_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_abm_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'STEM' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_stem_subjects = $this->sorting_list($junior_stem_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_stem_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Math' && $subject == 'Pre-Calculus') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'GAS' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_gas_subjects = $this->sorting_list($junior_gas_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_gas_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'];
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && $list_teachers[$key]['major'] == 'English' && $subject == 'PER DEV.' && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'TVL' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_tvl_subjects = $this->sorting_list($junior_tvl_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_tvl_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'MAE-Math' || $list_teacher['major'] == 'Math') && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Filipino sa Piling Larangan') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Bread and Pastry') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'ABM' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_abm_subjects2 = $this->sorting_list($junior_abm_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_abm_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'MAE-Math' || $list_teacher['major'] == 'Math') && $subject == 'Fundamentals of Accounting 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Pagsulat sa Pilipino') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Organization and Management') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'CPA Master in Business Management' || $list_teacher['major'] == 'TLE') && $subject == 'Research In Daily Life 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Business Finance') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'STEM' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_stem_subjects2 = $this->sorting_list($junior_stem_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_stem_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Disaster Readiness') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'General Chemistry 1') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life 1') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'PAGBASA') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if ($pe_teacher) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'GAS' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_gas_subjects2 = $this->sorting_list($junior_gas_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_gas_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Organization and Management') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'CPA Master in Business Management' || $list_teacher['major'] == 'TLE') && $subject == 'Research In Daily Life 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'Physical Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Pagsulat sa Pilipino') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Math' && $subject == 'Business Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'TVL' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_tvl_subjects2 = $this->sorting_list($junior_tvl_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_tvl_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Bread and Pastry')  && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}}


	foreach ($grade_junior as $key_7 => $value) {
		$subjects = $this->sorting_list($subjects);
		$days = $this->sorting_list($days);
		foreach ($subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
			foreach ($list_teachers as $key => $list_teacher) {
				if ($grade_junior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '' && $this->checkjuniorMajor($list_teachers[$key]['major']) && $this->checkifJunior($grade_junior[$key_7]['grade'])) {
					$grade_junior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(Grade '.$grade_junior[$key_7]['grade'].')';
				}
				$time = $this->timeJunior($subject_number,$grade_junior[$key_7]['grade']);
				if ($list_teachers[$key]['loads'] > 0 && $list_teacher['major'] == $subject && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && $this->checkifJunior($grade_junior[$key_7]['grade'])) {
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $list_teacher['major'] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => $list_teacher['major'],
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'ESP') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Filipino' && $subject == 'AP') && $teacher_limits['Filipino'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Filipino'] = $teacher_limits['Filipino'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(AP)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'AP',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'AP') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(AP)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'AP',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Math' && $subject == 'ESP') && $teacher_limits['Math'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Math'] = $teacher_limits['Math'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'English' && $subject == 'ESP') && $teacher_limits['English'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['English'] = $teacher_limits['English'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					];  
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Filipino' && $subject == 'ESP') && $teacher_limits['Filipino'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Filipino'] = $teacher_limits['Filipino'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'English' && $subject == 'Filipino') && $teacher_limits['English'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['English'] = $teacher_limits['English'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(Filipino)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'Filipino ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'Math') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(Math)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'Math ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}
			}}
		}	
	}	
		$this->set(compact('list_teachers','grade_senior', 'grade_junior'));
		$master_lists = $this->Teachers->find('all', ['contain'=>['Positions','Subjects']])->order(['Teachers.lastname']);
		$this->set(compact('master_lists'));
	}

	public function teacherpdf($id){
		$this->viewBuilder()->layout('');
		$this->loadModel('Teachers');
		$this->loadModel('Rooms');
$rooms = $this->Rooms->find('all', ['contain'=>['Buildings']])->where(['Rooms.grade'>0]);
		$teachers = $this->Teachers->find('all', ['contain'=>['Positions','Subjects']])->where(['Teachers.generate'=>1, 'Teachers.id'=>$id])->order(['Teachers.lastname']);
			$subjects = ['AP', 'TLE', 'MAPEH', 'Science', 'Math', 'English', 'Filipino', 'ESP'];
	$days = ['M','T','W', 'Th','F'];
	$ap = 0;
	$tle = 0;
	$mapeh = 0;
	$science = 0;
	$math = 0;
	$english = 0;
	$filipino = 0;
	foreach ($teachers as $teacher) {
		if ($teacher->subject_id > 0 && $teacher->loads > 0) {
			$list_teachers[] = [
								'id' => $teacher->id,
								'teacher' => $teacher->lastname.', '.$teacher->firstname.' '.$teacher->middle.'.',
								'advise' => '',
								'major' => $teacher->subject->subject,
								'loads' => $teacher->loads,
								'time1' => '',
								'time2' => '',
								'time3' => '',
								'time4' => '',
								'time5' => '',
								'time6' => '',
								'time7' => '',
								'time8' => '',
								];
			if ($teacher->subject->subject == 'AP') {
				$ap = $ap + $teacher->loads;
			}else if ($teacher->subject->subject == 'TLE') {
				$tle = $tle + $teacher->loads;
			}else if ($teacher->subject->subject == 'MAPEH') {
				$mapeh = $mapeh + $teacher->loads;
			}else if ($teacher->subject->subject == 'Science') {
				$science = $science + $teacher->loads;
			}else if ($teacher->subject->subject == 'Math') {
				$math = $math + $teacher->loads;
			}else if ($teacher->subject->subject == 'English') {
				$english = $english + $teacher->loads;
			}else if ($teacher->subject->subject == 'Filipino') {
				$filipino = $filipino + $teacher->loads;
			}
		}
	}

	$room_limits = 0;
	foreach ($rooms as $room) {
		if ($room->grade == 7 || $room->grade == 8 || $room->grade == 9 || $room->grade == 10) {
			$room_limits = $room_limits + 1;
			$grade_junior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
				'time8' => '',
			]; 
		}
	}

	$room_limits2 = 0;
	foreach ($rooms as $room) {
		if(($this->cutRoom($room->room) == 'ABM' || $this->cutRoom($room->room) == 'GAS' ) && $room->grade == 11){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
				'time8' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'STEM' && ($room->grade == 11 || $room->grade == 12)){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'TVL' && $room->grade == 11){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if(($this->cutRoom($room->room) == 'GAS' || $this->cutRoom($room->room) == 'ABM' || $this->cutRoom($room->room) == 'STEM') && $room->grade == 12){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'TVL' && $room->grade == 12){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
			]; 
		}
	}
	$teacher_limits = [
					'AP' => $ap - $room_limits,
					'TLE' => $tle - $room_limits,
					'MAPEH' => $mapeh - $room_limits,
					'Science' => $science - $room_limits + $room_limits2,
					'Math' => $math - $room_limits,
					'English' => $english - $room_limits,
					'Filipino' => $filipino - $room_limits,
			];
	$junior_abm_subjects = ['Gen. Math', 'Komunikasyon', 'Research In Daily Life', 'English For Academic', 'Oral Comm', 'Earth & Life Science', 'PER DEV.', 'Understanding Culture'];
	$junior_stem_subjects = ['Earth & Life Science', 'Pre-Calculus', 'Gen. Math', 'Komunikasyon', 'Intro to Philosophy', 'PER DEV.', 'Oral Comm'];
	$junior_gas_subjects = ['Understanding Culture', 'English For Academic', 'Oral Comm', 'Earth & Life Science', 'PER DEV.','Komunikasyon', 'Gen. Math', 'Research In Daily Life'];
	$junior_tvl_subjects = ['Komunikasyon', 'PER DEV.', 'English For Academic', 'Oral Comm', 'Filipino sa Piling Larangan', 'Bread and Pastry', 'Gen. Math'];
	$junior_abm_subjects2 = ['Research In Daily Life 2', 'Intro to Philosophy', 'Empowerment Technologies', 'Organization and Management', 'Fundamentals of Accounting 2', 'Pagsulat sa Pilipino', 'Business Finance'];
	$junior_stem_subjects2 = ['Understanding Culture', 'PAGBASA', 'Research In Daily Life 1', 'General Chemistry 1', 'English For Academic', 'Disaster Readiness', 'Empowerment Technologies'];
	$junior_gas_subjects2 = ['Business Math', 'Empowerment Technologies', 'Organization and Management', 'Pagsulat sa Pilipino', 'Intro to Philosophy', 'Research In Daily Life 2', 'Physical Science'];
	$junior_tvl_subjects2 = ['Bread and Pastry', 'Intro to Philosophy', 'Empowerment Technologies', 'Earth & Life Science', 'Understanding Culture', 'Research In Daily Life 1'];

if (isset($list_teachers) && isset($rooms)) {
	foreach ($list_teachers as $key => $find_pe) {
		if ($list_teachers[$key]['major'] == 'P.E') {
			$pe_teacher[] = $key;
		}
	}
if (isset($grade_senior)) {
	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'ABM' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_abm_subjects = $this->sorting_list($junior_abm_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_abm_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'STEM' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_stem_subjects = $this->sorting_list($junior_stem_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_stem_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Math' && $subject == 'Pre-Calculus') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'GAS' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_gas_subjects = $this->sorting_list($junior_gas_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_gas_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'];
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && $list_teachers[$key]['major'] == 'English' && $subject == 'PER DEV.' && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'TVL' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_tvl_subjects = $this->sorting_list($junior_tvl_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_tvl_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'MAE-Math' || $list_teacher['major'] == 'Math') && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Filipino sa Piling Larangan') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Bread and Pastry') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'ABM' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_abm_subjects2 = $this->sorting_list($junior_abm_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_abm_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'MAE-Math' || $list_teacher['major'] == 'Math') && $subject == 'Fundamentals of Accounting 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Pagsulat sa Pilipino') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Organization and Management') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'CPA Master in Business Management' || $list_teacher['major'] == 'TLE') && $subject == 'Research In Daily Life 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Business Finance') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'STEM' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_stem_subjects2 = $this->sorting_list($junior_stem_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_stem_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Disaster Readiness') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'General Chemistry 1') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life 1') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'PAGBASA') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if ($pe_teacher) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'GAS' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_gas_subjects2 = $this->sorting_list($junior_gas_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_gas_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Organization and Management') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'CPA Master in Business Management' || $list_teacher['major'] == 'TLE') && $subject == 'Research In Daily Life 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'Physical Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Pagsulat sa Pilipino') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Math' && $subject == 'Business Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'TVL' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_tvl_subjects2 = $this->sorting_list($junior_tvl_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_tvl_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Bread and Pastry')  && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}}


	foreach ($grade_junior as $key_7 => $value) {
		$subjects = $this->sorting_list($subjects);
		$days = $this->sorting_list($days);
		foreach ($subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
			foreach ($list_teachers as $key => $list_teacher) {
				if ($grade_junior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '' && $this->checkjuniorMajor($list_teachers[$key]['major']) && $this->checkifJunior($grade_junior[$key_7]['grade'])) {
					$grade_junior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(Grade '.$grade_junior[$key_7]['grade'].')';
				}
				$time = $this->timeJunior($subject_number,$grade_junior[$key_7]['grade']);
				if ($list_teachers[$key]['loads'] > 0 && $list_teacher['major'] == $subject && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && $this->checkifJunior($grade_junior[$key_7]['grade'])) {
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $list_teacher['major'] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => $list_teacher['major'],
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'ESP') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Filipino' && $subject == 'AP') && $teacher_limits['Filipino'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Filipino'] = $teacher_limits['Filipino'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(AP)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'AP',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'AP') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(AP)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'AP',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Math' && $subject == 'ESP') && $teacher_limits['Math'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Math'] = $teacher_limits['Math'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'English' && $subject == 'ESP') && $teacher_limits['English'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['English'] = $teacher_limits['English'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					];  
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Filipino' && $subject == 'ESP') && $teacher_limits['Filipino'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Filipino'] = $teacher_limits['Filipino'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'English' && $subject == 'Filipino') && $teacher_limits['English'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['English'] = $teacher_limits['English'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(Filipino)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'Filipino ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'Math') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(Math)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'].'('.$grade_junior[$key_7]['grade'].')',
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'Math ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}
			}}
		}	
	}	
		$this->set(compact('list_teachers','grade_senior', 'grade_junior'));
		$this->render()->type('application/pdf'); //Rendering the pdf
	}

	public function searchRoom($grade){
	if ($this->request->is('ajax') || $this->request->is('post')) {
		$this->loadModel('Teachers');
		$this->loadModel('Rooms');
$rooms = $this->Rooms->find('all', ['contain'=>['Buildings']])->where(['Rooms.grade'>0]);
		$teachers = $this->Teachers->find('all', ['contain'=>['Positions','Subjects']])->where(['Teachers.generate'=>1])->order(['Teachers.lastname']);
			$subjects = ['AP', 'TLE', 'MAPEH', 'Science', 'Math', 'English', 'Filipino', 'ESP'];
	$days = ['M','T','W', 'Th','F'];
	$ap = 0;
	$tle = 0;
	$mapeh = 0;
	$science = 0;
	$math = 0;
	$english = 0;
	$filipino = 0;
	foreach ($teachers as $teacher) {
		if ($teacher->subject_id > 0 && $teacher->loads > 0) {
			$list_teachers[] = [
								'id' => $teacher->id,
								'teacher' => $teacher->lastname.', '.$teacher->firstname.' '.$teacher->middle.'.',
								'advise' => '',
								'major' => $teacher->subject->subject,
								'loads' => $teacher->loads,
								'time1' => '',
								'time2' => '',
								'time3' => '',
								'time4' => '',
								'time5' => '',
								'time6' => '',
								'time7' => '',
								'time8' => '',
								];
			if ($teacher->subject->subject == 'AP') {
				$ap = $ap + $teacher->loads;
			}else if ($teacher->subject->subject == 'TLE') {
				$tle = $tle + $teacher->loads;
			}else if ($teacher->subject->subject == 'MAPEH') {
				$mapeh = $mapeh + $teacher->loads;
			}else if ($teacher->subject->subject == 'Science') {
				$science = $science + $teacher->loads;
			}else if ($teacher->subject->subject == 'Math') {
				$math = $math + $teacher->loads;
			}else if ($teacher->subject->subject == 'English') {
				$english = $english + $teacher->loads;
			}else if ($teacher->subject->subject == 'Filipino') {
				$filipino = $filipino + $teacher->loads;
			}
		}
	}

	$room_limits = 0;
	foreach ($rooms as $room) {
		if ($room->grade == 7 || $room->grade == 8 || $room->grade == 9 || $room->grade == 10) {
			$room_limits = $room_limits + 1;
			$grade_junior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
				'time8' => '',
			]; 
		}
	}

	$room_limits2 = 0;
	foreach ($rooms as $room) {
		if(($this->cutRoom($room->room) == 'ABM' || $this->cutRoom($room->room) == 'GAS' ) && $room->grade == 11){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
				'time8' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'STEM' && ($room->grade == 11 || $room->grade == 12)){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'TVL' && $room->grade == 11){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if(($this->cutRoom($room->room) == 'GAS' || $this->cutRoom($room->room) == 'ABM' || $this->cutRoom($room->room) == 'STEM') && $room->grade == 12){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
				'time7' => '',
			]; 
		}else if($this->cutRoom($room->room) == 'TVL' && $room->grade == 12){
			$room_limits2 = $room_limits2 + 1;
			$grade_senior[] = [
				'room' => $room->room,
				'building' => '('.$room->building->number.') '.$room->building->building,
				'grade' => $room->grade,
				'adviser' => '',
				'time1' => '',
				'time2' => '',
				'time3' => '',
				'time4' => '',
				'time5' => '',
				'time6' => '',
			]; 
		}
	}
	$teacher_limits = [
					'AP' => $ap - $room_limits,
					'TLE' => $tle - $room_limits,
					'MAPEH' => $mapeh - $room_limits,
					'Science' => $science - $room_limits + $room_limits2,
					'Math' => $math - $room_limits,
					'English' => $english - $room_limits,
					'Filipino' => $filipino - $room_limits,
			];
	$junior_abm_subjects = ['Gen. Math', 'Komunikasyon', 'Research In Daily Life', 'English For Academic', 'Oral Comm', 'Earth & Life Science', 'PER DEV.', 'Understanding Culture'];
	$junior_stem_subjects = ['Earth & Life Science', 'Pre-Calculus', 'Gen. Math', 'Komunikasyon', 'Intro to Philosophy', 'PER DEV.', 'Oral Comm'];
	$junior_gas_subjects = ['Understanding Culture', 'English For Academic', 'Oral Comm', 'Earth & Life Science', 'PER DEV.','Komunikasyon', 'Gen. Math', 'Research In Daily Life'];
	$junior_tvl_subjects = ['Komunikasyon', 'PER DEV.', 'English For Academic', 'Oral Comm', 'Filipino sa Piling Larangan', 'Bread and Pastry', 'Gen. Math'];
	$junior_abm_subjects2 = ['Research In Daily Life 2', 'Intro to Philosophy', 'Empowerment Technologies', 'Organization and Management', 'Fundamentals of Accounting 2', 'Pagsulat sa Pilipino', 'Business Finance'];
	$junior_stem_subjects2 = ['Understanding Culture', 'PAGBASA', 'Research In Daily Life 1', 'General Chemistry 1', 'English For Academic', 'Disaster Readiness', 'Empowerment Technologies'];
	$junior_gas_subjects2 = ['Business Math', 'Empowerment Technologies', 'Organization and Management', 'Pagsulat sa Pilipino', 'Intro to Philosophy', 'Research In Daily Life 2', 'Physical Science'];
	$junior_tvl_subjects2 = ['Bread and Pastry', 'Intro to Philosophy', 'Empowerment Technologies', 'Earth & Life Science', 'Understanding Culture', 'Research In Daily Life 1'];

if (isset($list_teachers) && isset($rooms)) {
	foreach ($list_teachers as $key => $find_pe) {
		if ($list_teachers[$key]['major'] == 'P.E') {
			$pe_teacher[] = $key;
		}
	}
if (isset($grade_senior)) {
	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'ABM' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_abm_subjects = $this->sorting_list($junior_abm_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_abm_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'STEM' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_stem_subjects = $this->sorting_list($junior_stem_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_stem_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Math' && $subject == 'Pre-Calculus') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'GAS' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_gas_subjects = $this->sorting_list($junior_gas_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_gas_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'];
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'MAE-Math' && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Bio. Chem.' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && $list_teachers[$key]['major'] == 'English' && $subject == 'PER DEV.' && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'TVL' && $grade_senior[$key_7]['grade'] == 11) {
			$junior_tvl_subjects = $this->sorting_list($junior_tvl_subjects);
			$days = $this->sorting_list($days);
			foreach ($junior_tvl_subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(11)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'MAE-Math' || $list_teacher['major'] == 'Math') && $subject == 'Gen. Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Filipino sa Piling Larangan') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Bread and Pastry') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Komunikasyon') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Oral Comm') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'PER DEV.') && $list_teachers[$key]['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(11)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(11)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'ABM' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_abm_subjects2 = $this->sorting_list($junior_abm_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_abm_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'MAE-Math' || $list_teacher['major'] == 'Math') && $subject == 'Fundamentals of Accounting 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Pagsulat sa Pilipino') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Organization and Management') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'CPA Master in Business Management' || $list_teacher['major'] == 'TLE') && $subject == 'Research In Daily Life 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Business Finance') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'STEM' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_stem_subjects2 = $this->sorting_list($junior_stem_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_stem_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'Disaster Readiness') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'General Chemistry 1') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life 1') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'PAGBASA') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'English' && $subject == 'English For Academic') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if ($pe_teacher) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'GAS' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_gas_subjects2 = $this->sorting_list($junior_gas_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_gas_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Organization and Management') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && (($list_teacher['major'] == 'CPA Master in Business Management' || $list_teacher['major'] == 'TLE') && $subject == 'Research In Daily Life 2') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'Physical Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Filipino' && $subject == 'Pagsulat sa Pilipino') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Math' && $subject == 'Business Math') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}

	foreach ($grade_senior as $key_7 => $value) {
	$subject_number = 0;
		if ($this->cutRoom($grade_senior[$key_7]['room']) == 'TVL' && $grade_senior[$key_7]['grade'] == 12) {
			$junior_tvl_subjects2 = $this->sorting_list($junior_tvl_subjects2);
			$days = $this->sorting_list($days);
			foreach ($junior_tvl_subjects2 as $key_subject => $subject) {
			$subject_number = $key_subject+1;
				foreach ($list_teachers as $key => $list_teacher) {
					if ($grade_senior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '') {
					$grade_senior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'].'(12)';
					}
					$time = $this->timeSenior($subject_number);
					if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Commerce - Accounting' && $subject == 'Intro to Philosophy') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == '18 Units Educ' && $subject == 'Understanding Culture') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'CPA Master in Business Management' && $subject == 'Research In Daily Life') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'TLE' && $subject == 'Empowerment Technologies') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Science' && $subject == 'Earth & Life Science') && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ')';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}else if ($list_teachers[$key]['loads'] > 0 && ($list_teacher['major'] == 'Food Tech.' && $subject == 'Bread and Pastry')  && $list_teacher['time'.$subject_number] == '' && $grade_senior[$key_7]['time'.$subject_number] == '') {
						$count_pe = 0;
						$pe_name = '';
						if (isset($pe_teacher)) {
						foreach ($pe_teacher as $key => $value) {
							$pe_name = $list_teachers[$value]['teacher'];
							if ($list_teachers[$value]['loads'] > 0 && $list_teachers[$value]['time'.$subject_number] == '' && $count_pe == 0) {
								$pe_name = $list_teachers[$value]['teacher'];
								$list_teachers[$value]['time'.$subject_number] = [
									'room' => $grade_senior[$key_7]['room'].'(12)',
									'grade' => $grade_senior[$key_7]['grade'],
									'subject' => 'P.E (' .$days[0].')',
									'time' => $time
								];
								$list_teachers[$value]['loads'] = $list_teachers[$value]['loads'] - 1;
								$count_pe = $count_pe + 1; 
							}
						}}
						$grade_senior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $subject . ') *'.$days[0].' / ' . $pe_name . ' (P.E)';
						$list_teachers[$key]['time'.$subject_number] = [
								'room' => $grade_senior[$key_7]['room'].'(12)',
								'grade' => $grade_senior[$key_7]['grade'],
								'subject' => $subject,
								'time' => $time
						]; 
						$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
					}
				}
			}
		}
	}}


	foreach ($grade_junior as $key_7 => $value) {
		$subjects = $this->sorting_list($subjects);
		$days = $this->sorting_list($days);
		foreach ($subjects as $key_subject => $subject) {
			$subject_number = $key_subject+1;
			foreach ($list_teachers as $key => $list_teacher) {
				if ($grade_junior[$key_7]['adviser'] == '' && $list_teachers[$key]['advise'] == '' && $this->checkjuniorMajor($list_teachers[$key]['major']) && $this->checkifJunior($grade_junior[$key_7]['grade'])) {
					$grade_junior[$key_7]['adviser'] = $list_teacher['teacher'];
					$list_teachers[$key]['advise'] = $value['room'];
				}
				$time = $this->timeJunior($subject_number,$grade_junior[$key_7]['grade']);
				if ($list_teachers[$key]['loads'] > 0 && $list_teacher['major'] == $subject && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && $this->checkifJunior($grade_junior[$key_7]['grade'])) {
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(' . $list_teacher['major'] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => $list_teacher['major'],
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'ESP') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Filipino' && $subject == 'AP') && $teacher_limits['Filipino'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Filipino'] = $teacher_limits['Filipino'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(AP)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'AP',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'AP') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(AP)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'AP',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Math' && $subject == 'ESP') && $teacher_limits['Math'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Math'] = $teacher_limits['Math'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'English' && $subject == 'ESP') && $teacher_limits['English'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['English'] = $teacher_limits['English'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					];  
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'Filipino' && $subject == 'ESP') && $teacher_limits['Filipino'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['Filipino'] = $teacher_limits['Filipino'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(ESP) ('.$days[1].$days[2].$days[3].') / Computer Class (' . $days[0] . ')';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'ESP ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'English' && $subject == 'Filipino') && $teacher_limits['English'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['English'] = $teacher_limits['English'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(Filipino)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'Filipino ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}else if($list_teachers[$key]['loads'] > 0 && $list_teacher['time'.$subject_number] == '' && $grade_junior[$key_7]['time'.$subject_number] == '' && ($list_teacher['major'] == 'TLE' && $subject == 'Math') && $teacher_limits['TLE'] > 0 && $this->checkifJunior($grade_junior[$key_7]['grade'])){
					$teacher_limits['TLE'] = $teacher_limits['TLE'] - 1;
					$grade_junior[$key_7]['time'.$subject_number] = $list_teacher['teacher'] . '(Math)';
					$list_teachers[$key]['time'.$subject_number] = [
							'room' => $value['room'],
							'grade' => $grade_junior[$key_7]['grade'],
							'subject' => 'Math ('.$days[1].$days[2].$days[3].')',
							'time' => $time
					]; 
					$list_teachers[$key]['loads'] = $list_teachers[$key]['loads'] - 1;
				}
			}}
		}	
	}	
		$this->set(compact('list_teachers','grade_senior', 'grade_junior', 'grade'));
	}else{
		return $this->redirect(['controller'=>'Home', 'action'=>'schedule']);
	}
	}
	
	public function login(){
		$this->checkLogin();
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
			return $this->redirect($this->Auth->redirectUrl());
			}else{
				$this->Flash->error('Your username or password is incorrect');
			}
		}
	}
	public function logout(){
        $this->Flash->success('Successfully logged out');
        return $this->redirect($this->Auth->logout());
    }

    public function swap(&$subjects, $a, $b) {
	    $tmp = $subjects[$a];
	    $subjects[$a] = $subjects[$b];
	    $subjects[$b] = $tmp;
	}

	public function cutRoom($room){
		$room = explode(" ",$room);
		return $room[0];
	}

	public function timeSenior($time){
		if ($time == 1) {
			return '7:30 AM - 8:30 AM';
		}else if ($time == 2) {
			return '8:30 AM - 9:30 AM';
		}else if ($time == 3) {
			return '10:00 AM - 11:00 AM';
		}else if ($time == 4) {
			return '11:00 AM - 12:00 PM';
		}else if ($time == 5) {
			return '1:00 PM - 2:00 PM';
		}else if ($time == 6) {
			return '2:00 PM - 3:00 PM';
		}else if ($time == 7) {
			return '3:00 PM - 4:00 PM';
		}else if ($time == 8) {
			return '4:00 PM - 5:00 PM';
		}

	}
	public function timeJunior($time,$grade){
		if ($grade == 7 || $grade == 8) {
			if ($time == 1) {
				return '6:50 AM - 7:30 AM';
			}else if ($time == 2) {
				return '7:30 AM - 8:10 AM';
			}else if ($time == 3) {
				return '8:10 AM - 8:50 AM';
			}else if ($time == 4) {
				return '9:05 AM - 9:45 AM';
			}else if ($time == 5) {
				return '9:45 AM - 10:25 AM';
			}else if ($time == 6) {
				return '10:25 AM - 11:05 AM';
			}else if ($time == 7) {
				return '11:05 AM - 11:45 AM';
			}else if ($time == 8) {
				return '11:45 AM - 12:25 PM';
			}
		}else if($grade == 9 || $grade == 10){
			if ($time == 1) {
				return '12:30 PM - 1:10 PM';
			}else if ($time == 2) {
				return '1:10 PM - 1:50 PM';
			}else if ($time == 3) {
				return '1:50 PM - 2:30 PM';
			}else if ($time == 4) {
				return '2:30 PM - 3:10 PM';
			}else if ($time == 5) {
				return '3:25 PM - 4:05 PM';
			}else if ($time == 6) {
				return '4:05 PM - 4:45 PM';
			}else if ($time == 7) {
				return '4:45 PM - 5:25 PM';
			}else if ($time == 8) {
				return '5:25 PM - 6:05 PM';
			}
		}
	}

	public function checkjuniorMajor($major){
		if ($major == 'AP' || $major == 'TLE' || $major == 'MAPEH' || $major == 'Science' || $major == 'Math' || $major == 'English' || $major == 'Filipino') {
			return true;
		}else{
			return false;
		}
	}

	public function checkifJunior($grade){
		if ($grade == 7 || $grade == 8 || $grade == 9 || $grade == 10) {
			return true;
		}else{
			return false;
		}
	}

	public function sorting_list($subjects) {
	    $size = count($subjects);
	    for ($i=0; $i<$size-1; $i++) {
	        for ($j=0; $j<$size-1; $j++) {
	                $this->swap($subjects, $j, $j+1);
	        }
	    }
	    return $subjects;
	}

}