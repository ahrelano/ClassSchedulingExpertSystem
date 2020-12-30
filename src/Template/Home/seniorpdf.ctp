<?php
$pdf=new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$html="<table border=1>";
if (isset($sched)) {
	$html.= 'Grade '.$sched['grade'] .'<br>';
	$html.= 'Room: '.$sched['room'] .'<br>';
	$html.= 'Adviser: '.$sched['adviser'] .'<br><br>';
}
if (isset($sched) && $sched['grade'] == 11 && ($sched['cutroom'] == 'ABM' || $sched['cutroom'] == 'GAS')) {
	$pdf->SetTitle($sched['room']);
	$html.='<tr>
				<td width="200" bgcolor="red">Time</td>
				<td width="550" bgcolor="#FFA500">Teacher & Subjects</td>
			</tr>';
	$html.='<tr>
				<td width="200">7:30 AM - 8:30 AM</td>
				<td width="550">'.$sched['time1'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">8:30 AM - 9:30 AM</td>
				<td width="550">'.$sched['time2'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">9:30 AM - 10:00 AM</td>
				<td width="550">Recess</td>
			</tr>';
	$html.='<tr>
				<td width="200">10:00 AM - 11:00 AM</td>
				<td width="550">'.$sched['time3'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">11:00 AM - 12:00 PM</td>
				<td width="550">'.$sched['time4'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">12:00 PM - 1:00 PM</td>
				<td width="550">Lunch Break</td>
			</tr>';
	$html.='<tr>
				<td width="200">1:00 PM - 2:00 PM</td>
				<td width="550">'.$sched['time5'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">2:00 PM - 3:00 PM</td>
				<td width="550">'.$sched['time6'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">3:00 PM - 4:00 PM</td>
				<td width="550">'.$sched['time7'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">4:00 PM - 5:00 PM</td>
				<td width="550">'.$sched['time8'].'</td>
			</tr>';
	$html.= '<tr><td width="750">Location: '.$sched['building'].'</td></tr>';

}else if (isset($sched) && $sched['grade'] == 11 && ($sched['cutroom'] == 'STEM' || $sched['cutroom'] == 'TVL')) {
	$pdf->SetTitle($sched['room']);
	$html.='<tr>
				<td width="200" bgcolor="red">Time</td>
				<td width="550" bgcolor="#FFA500">Teacher & Subjects</td>
			</tr>';
	$html.='<tr>
				<td width="200">7:30 AM - 8:30 AM</td>
				<td width="550">'.$sched['time1'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">8:30 AM - 9:30 AM</td>
				<td width="550">'.$sched['time2'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">9:30 AM - 10:00 AM</td>
				<td width="550">Recess</td>
			</tr>';
	$html.='<tr>
				<td width="200">10:00 AM - 11:00 AM</td>
				<td width="550">'.$sched['time3'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">11:00 AM - 12:00 PM</td>
				<td width="550">'.$sched['time4'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">12:00 PM - 1:00 PM</td>
				<td width="550">Lunch Break</td>
			</tr>';
	$html.='<tr>
				<td width="200">1:00 PM - 2:00 PM</td>
				<td width="550">'.$sched['time5'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">2:00 PM - 3:00 PM</td>
				<td width="550">'.$sched['time6'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">3:00 PM - 4:00 PM</td>
				<td width="550">'.$sched['time7'].'</td>
			</tr>';
	$html.= '<tr><td width="750">Location: '.$sched['building'].'</td></tr>';

}else if (isset($sched) && $sched['grade'] == 12 && ($sched['cutroom'] == 'ABM' || $sched['cutroom'] == 'STEM' || $sched['cutroom'] == 'GAS')) {
	$pdf->SetTitle($sched['room']);
	$html.='<tr>
				<td width="200" bgcolor="red">Time</td>
				<td width="550" bgcolor="#FFA500">Teacher & Subjects</td>
			</tr>';
	$html.='<tr>
				<td width="200">7:30 AM - 8:30 AM</td>
				<td width="550">'.$sched['time1'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">8:30 AM - 9:30 AM</td>
				<td width="550">'.$sched['time2'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">9:30 AM - 10:00 AM</td>
				<td width="550">Recess</td>
			</tr>';
	$html.='<tr>
				<td width="200">10:00 AM - 11:00 AM</td>
				<td width="550">'.$sched['time3'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">11:00 AM - 12:00 PM</td>
				<td width="550">'.$sched['time4'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">12:00 PM - 1:00 PM</td>
				<td width="550">Lunch Break</td>
			</tr>';
	$html.='<tr>
				<td width="200">1:00 PM - 2:00 PM</td>
				<td width="550">'.$sched['time5'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">2:00 PM - 3:00 PM</td>
				<td width="550">'.$sched['time6'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">3:00 PM - 4:00 PM</td>
				<td width="550">'.$sched['time7'].'</td>
			</tr>';
	$html.= '<tr><td width="750">Location: '.$sched['building'].'</td></tr>';

}else if (isset($sched) && $sched['grade'] == 12 && $sched['cutroom'] == 'TVL') {
	$pdf->SetTitle($sched['room']);
	$html.='<tr>
				<td width="200" bgcolor="red">Time</td>
				<td width="550" bgcolor="#FFA500">Teacher & Subjects</td>
			</tr>';
	$html.='<tr>
				<td width="200">7:30 AM - 8:30 AM</td>
				<td width="550">'.$sched['time1'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">8:30 AM - 9:30 AM</td>
				<td width="550">'.$sched['time2'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">9:30 AM - 10:00 AM</td>
				<td width="550">Recess</td>
			</tr>';
	$html.='<tr>
				<td width="200">10:00 AM - 11:00 AM</td>
				<td width="550">'.$sched['time3'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">11:00 AM - 12:00 PM</td>
				<td width="550">'.$sched['time4'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">12:00 PM - 1:00 PM</td>
				<td width="550">Lunch Break</td>
			</tr>';
	$html.='<tr>
				<td width="200">1:00 PM - 2:00 PM</td>
				<td width="550">'.$sched['time5'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">2:00 PM - 3:00 PM</td>
				<td width="550">'.$sched['time6'].'</td>
			</tr>';
	$html.= '<tr><td width="750">Location: '.$sched['building'].'</td></tr>';

}
$html.="</table>";
$pdf->WriteHTML($html);
if (isset($sched)) {
	$title = str_replace(".", "-", $sched['room'].'-Grade'.$sched['grade']);
	$pdf->Output('',$title);
}else{
	$pdf->Output('','Not Found');
}
