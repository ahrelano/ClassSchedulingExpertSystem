<?php
$pdf=new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$html="<table border=1>";
$title = 'Not Found';
if (isset($list_teachers)) {
	foreach ($list_teachers as $key => $list_teacher) {
	$title = str_replace(".", " ", $list_teacher['teacher']);
	$html.= 'Teacher '.$list_teacher['teacher'] .'<br>';
	$html.= 'Advise '.$list_teacher['advise'] .'<br>';
	$html.= 'Major: '.$list_teacher['major'] .'<br>';
	if ($list_teacher['loads'] < 0) {
		$html.= 'Loads: 0<br>';
	}else{
		$html.= 'Loads: '. $list_teacher['loads'] .'<br>';
	}
	$html.='<tr>
				<td width="200" bgcolor="red">Time</td>
				<td width="250" bgcolor="#FFA500">Room(Grade)</td>
				<td width="300" bgcolor="#FFA500">Subject</td>
			</tr>';
	if (isset($list_teacher['time1']['time'])){
		$html .= '<tr>';
		$html .= '<td width="200">'. $list_teacher['time1']['time'] .'</td>';
		$html .= '<td width="250">'. $list_teacher['time1']['room'] .'</td>';
		$html .= '<td width="300">'. $list_teacher['time1']['subject'] .'</td>';
		$html .= '</tr>';
	}
	if (isset($list_teacher['time2']['time'])){
		$html .= '<tr>';
		$html .= '<td width="200">'. $list_teacher['time2']['time'] .'</td>';
		$html .= '<td width="250">'. $list_teacher['time2']['room'] .'</td>';
		$html .= '<td width="300">'. $list_teacher['time2']['subject'] .'</td>';
		$html .= '</tr>';
	} 
	if (isset($list_teacher['time3']['time'])){ 
		$html .= '<tr>';
		$html .= '<td width="200">'. $list_teacher['time3']['time'] .'</td>';
		$html .= '<td width="250">'. $list_teacher['time3']['room'] .'</td>';
		$html .= '<td width="300">'. $list_teacher['time3']['subject'] .'</td>';
		$html .= '</tr>';
	} 
	if (isset($list_teacher['time4']['time'])){
		$html .= '<tr>';
		$html .= '<td width="200">'. $list_teacher['time4']['time'] .'</td>';
		$html .= '<td width="250">'. $list_teacher['time4']['room'] .'</td>';
		$html .= '<td width="300">'. $list_teacher['time4']['subject'] .'</td>';
		$html .= '</tr>';
	} 
	if (isset($list_teacher['time5']['time'])){
		$html .= '<tr>';
		$html .= '<td width="200">'. $list_teacher['time5']['time'] .'</td>';
		$html .= '<td width="250">'. $list_teacher['time5']['room'] .'</td>';
		$html .= '<td width="300">'. $list_teacher['time5']['subject'] .'</td>';
		$html .= '</tr>';
	}
	if (isset($list_teacher['time6']['time'])){
		$html .= '<tr>';
		$html .= '<td width="200">'. $list_teacher['time6']['time'] .'</td>';
		$html .= '<td width="250">'. $list_teacher['time6']['room'] .'</td>';
		$html .= '<td width="300">'. $list_teacher['time6']['subject'] .'</td>';
		$html .= '</tr>';
	}
	if (isset($list_teacher['time7']['time'])){
		$html .= '<tr>';
		$html .= '<td width="200">'. $list_teacher['time7']['time'] .'</td>';
		$html .= '<td width="250">'. $list_teacher['time7']['room'] .'</td>';
		$html .= '<td width="300">'. $list_teacher['time7']['subject'] .'</td>';
		$html .= '</tr>';
	}
	if (isset($list_teacher['time8']['time'])){
		$html .= '<tr>';
		$html .= '<td width="200">'. $list_teacher['time8']['time'] .'</td>';
		$html .= '<td width="250">'. $list_teacher['time8']['room'] .'</td>';
		$html .= '<td width="300">'. $list_teacher['time8']['subject'] .'</td>';
		$html .= '</tr>';
	} 
	}
}
$html.="</table>";
$pdf->WriteHTML($html);
$pdf->Output('',$title);