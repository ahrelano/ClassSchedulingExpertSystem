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
if (isset($sched) && ($sched['grade']==7 || $sched['grade']==8)) {
	$pdf->SetTitle($sched['room']);
	$html.='<tr>
				<td width="200" bgcolor="red">Time</td>
				<td width="550" bgcolor="#FFA500">Teacher & Subjects</td>
			</tr>';
	$html.='<tr>
				<td width="200">6:50 AM - 7:30 AM</td>
				<td width="550">'.$sched['time1'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">7:30 AM - 8:10 AM</td>
				<td width="550">'.$sched['time2'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">8:10 AM - 8:50 AM</td>
				<td width="550">'.$sched['time3'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">8:50 AM - 9:05 AM</td>
				<td width="550">Recess</td>
			</tr>';
	$html.='<tr>
				<td width="200">9:05 AM - 9:45 AM</td>
				<td width="550">'.$sched['time4'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">9:45 AM - 10:25 AM</td>
				<td width="550">'.$sched['time5'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">10:25 AM - 11:05 AM</td>
				<td width="550">'.$sched['time6'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">11:05 AM - 11:45 AM</td>
				<td width="550">'.$sched['time7'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">11:45 AM - 12:25 PM</td>
				<td width="550">'.$sched['time8'].'</td>
			</tr>';
	$html.= '<tr><td width="750">Location: '.$sched['building'].'</td></tr>';

}else if(isset($sched) && ($sched['grade']==9 || $sched['grade']==10)){
	$pdf->SetTitle($sched['room']);
	$html.='<tr>
				<td width="200" bgcolor="red">Time</td>
				<td width="550" bgcolor="#FFA500">Teacher & Subjects</td>
			</tr>';
	$html.='<tr>
				<td width="200">12:30 PM - 1:10 PM</td>
				<td width="550">'.$sched['time1'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">1:10 PM - 1:50 PM</td>
				<td width="550">'.$sched['time2'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">1:50 PM - 2:30 PM</td>
				<td width="550">'.$sched['time3'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">2:30 PM - 3:10 PM</td>
				<td width="550">'.$sched['time4'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">3:10 PM - 3:25 PM</td>
				<td width="550">Break</td>
			</tr>';
	$html.='<tr>
				<td width="200">3:25 PM - 4:05 PM</td>
				<td width="550">'.$sched['time5'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">4:05 PM - 4:45 PM</td>
				<td width="550">'.$sched['time6'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">4:45 PM - 5:25 PM</td>
				<td width="550">'.$sched['time7'].'</td>
			</tr>';
	$html.='<tr>
				<td width="200">5:25 PM - 6:05 PM</td>
				<td width="550">'.$sched['time8'].'</td>
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
