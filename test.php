<?php
/*
$ch = curl_init("http://api4.cekresi.co.id/allcnote.php");
curl_setopt_array($ch, 
	[
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_REFERER => "http://www.resi.co.id/",
		CURLOPT_HTTPHEADER => [
			"Origin: http://www.resi.co.id",
			"Content-type: application/x-www-form-urlencoded"
		],
		CURLOPT_POSTFIELDS => "id=011700785388617&kurir=jne",
		CURLOPT_USERAGENT => "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:56.0) Gecko/20100101 Firefox/56.0"
	]
);
$out = curl_exec($ch);
curl_close($ch);
file_put_contents("b.tmp", $out);
var_dump($out);die;*/

$a = file_get_contents("b.tmp");

$b = explode("<td width=\"130\">No Resi</td>", $a, 2);
if (isset($b[1])) {
	$b = explode("</tr>", $b[1], 2);
	if (isset($b[1])) {
		$noresi = strip_tags($b[0]);
		$b = explode("<td>Status</td>", $a, 2);
		if (isset($b[1])) {
			$b = explode("<td><b>", $b[1], 2);
			if (isset($b[1])) {
				$b = explode("<", $b[1], 2);
				$status = $b[0];
				$b = explode("<td>Dikirim tanggal</td>", $a, 2);
				if (isset($b[1])) {
					$b = explode("</tr>", $b[1], 2);
					$b = explode("<td>", $b[0]);
					if (isset($b[2])) {
						$b = explode("</td>", $b[2], 2);
						$dikirim = $b[0];
						$b = explode("<td valign=\"top\">Dikirim oleh</td>", $a, 2);
						if (isset($b[1])) {
							$b = explode("</tr>", $b[1], 2);
							$b = explode("<td valign=\"top\">", $b[0]);
							if (isset($b[2])) {
								$dikirimoleh = trim(strip_tags(html_entity_decode($b[2], ENT_QUOTES, 'UTF-8')));
								$b = explode("<td valign=\"top\">Dikirim ke</td>", $a, 2);
								if (isset($b[1])) {
									$b = explode("</tr>", $b[1], 2);
									$b = explode("<td valign=\"top\">", $b[0]);
									if (isset($b[2])) {
										$b = explode("</td>", $b[2], 2);
										$dikirimke = trim(strip_tags(html_entity_decode($b[0], ENT_QUOTES, 'UTF-8')));
										$b = explode("<th width=\"40%\">Keterangan</th>", $a, 2);
										$b = explode("</tbody>", $b[1]);
										$b = explode("<tr>", $b[0]);
										unset($b[0]);
										$data = [];
										foreach ($b as $val) {
											$val = explode("\n", trim($val));
											$tgl = strip_tags(trim($val[0]));
											unset($val[0]);
											$text = [];
											foreach ($val as $val) {
												$val = trim(strip_tags(html_entity_decode($val, ENT_QUOTES, 'UTF-8')));
												empty($val) or $text[] = $val;
											}
											$text and $data[$tgl] = implode("\n", $text);
										}
										$b = explode("POD Detail</b></div>", $a, 2);
										$b = explode("<th width=\"40%\">Keterangan</th>", $b[1], 2);
										$b = explode("</tbody>", $b[1], 2);
										$b = explode("<tr>", $b[0]);
										unset($b[0]);
										$text = []; $pod = [];
										foreach ($b as $val) {
											$val = explode("\n", trim($val));
											$tgl = strip_tags(trim($val[0]));
											unset($val[0]);
											$text = [];
											foreach ($val as $val) {
												$val = trim(strip_tags(html_entity_decode($val, ENT_QUOTES, 'UTF-8')));
												empty($val) or $text[] = $val;
											}
											$text and $pod[$tgl] = implode("\n", $text);
										}
									}
								}
							}
						}
						$data = [
							"informasi_pengiriman" => [
								"no_resi" => $noresi,
								"status" => $status,
								"tanggal_pengiriman" => $dikirim,
								"dikirim_oleh" => $dikirimoleh,
								"dikirim_ke" => $dikirimke
							],
							"status_pengiriman" => [
								"outbond" => $data,
								"pod_detail" => $pod
							]
						];
					}
				}
			}
		}
	}
}

var_dump($data);