<?php
class profilef {
	public function profilealiasify($str) {
		$str=trim($str);
		return preg_replace('/\-+/','-',preg_replace('/[^0-9a-z\-]/','',strtolower(preg_replace('/\s+/','-',str_replace('/',' or ',str_replace('&',' and ',$str))))));
	}
	public function profilecleanalias($str) {
		return preg_replace('/[^0-9a-z\-]/','',strtolower($str));
	}
	public function profilecleannid($str) {
		return preg_replace('/[^0-9a-z\_]/','',strtolower($str));
	}
	public function send_email_plain($to,$subject,$message) {
		$to=preg_replace("/[^a-zA-Z0-9_\-\.\+@]/", "", $to);
		$sn=preg_replace("/[^a-zA-Z0-9_\-\.]/", "", $_SERVER['SERVER_NAME']);
		$fromemail=$sn_email="info@".(strtolower(substr($sn,0,4))=='www.'?substr($sn,4):$sn);
		
		$nl="\r\n";
		$headers="From: PropertEASE <".$sn_email.">".$nl;
		$headers.="Sender: ".$fromemail."".$nl;
		$headers.="Reply-To: PropertEASE <".$fromemail.">".$nl;
		$headers.="Return-Path: PropertEASE <".$fromemail.">".$nl;
		$headers.="Date: ".date("r").$nl;
		$headers.="Message-ID: <".time()."@".$sn.">".$nl;
		$headers.="X-Mailer: PHP v".phpversion().$nl;
		$headers.="MIME-Version: 1.0".$nl;
		$headers.="Content-Disposition: inline".$nl;
		$headers.="Content-Type: text/plain; charset=iso-8859-1".$nl;
		$headers.="Content-Transfer-Encoding: 7bit".$nl;
		
		$subject = preg_replace("/[^a-zA-Z0-9\s\-'\.\,\_]/", "", $subject);
		$message = preg_replace('/[^(\x20-\x7F)]*/','',preg_replace('/\s+/'," ",$message));
		ini_set('sendmail_from', $sn_email);
		$result=mail($to, $subject, $message, $headers,"-oi -f ".$sn_email);
		return $result;
	}
	public function profilegetcatdata() {
		$db =& JFactory::getDBO();
		$db->setQuery('SELECT id, parent, name FROM #__sobipro_object WHERE oType=\'category\' AND state>0 AND approved>0');
		$catpool=$db->loadAssocList();
		$catparenta=array();
		$catlva=array();
		$cata=array();
		$catnamea=array();
		foreach($catpool as $catkey => $catdata) {
			$catpool[$catkey]=0;
			$parent=intval($catdata['parent']);
			$name=$catdata['name'];
			if($parent==1) {
				$id=intval($catdata['id']);
				if($id>1) {
					if(!isset($catlva[0])) {
						$catlva[0]=array($id);
					} else if(!in_array($id,$catlva[0])) {
						$catlva[0][]=$id;
					}
				}
			}
			$catparenta[intval($catdata['id']).'c']=$parent;
			$catnamea[intval($catdata['id']).'c']=$name;
		}
		$i=0;
		while($i<2) {
			foreach($catparenta as $idk => $parent) {
				if(!isset($catlva[$i])) {
					$catlva[$i]=array();
				}
				if(in_array($parent,$catlva[$i])) {
					if(!isset($catlva[$i+1])) {
						$catlva[$i+1]=array();
					}
					$catlva[$i+1][]=intval($idk);
				}
			}
			$i++;
		}
		$db->setQuery('SELECT DISTINCT #__sobipro_relations.pid FROM #__sobipro_relations INNER JOIN #__sobipro_object USING(id) WHERE #__sobipro_object.oType=\'entry\' AND #__sobipro_object.state>0 AND #__sobipro_object.approved>0 AND #__sobipro_relations.pid>0');
		$catparentpool=$db->loadAssocList();
		
		$catlvanew=array(array(),array(),array());
		$catparentanew=array();
		foreach($catparentpool as $val) {
			$parent=intval($val['pid']);
			if($parent>1) {
				$startlevel=0;
				if(in_array($parent,$catlva[2])) {
					$startlevel=2;
					$id=$parent;
					while($startlevel>=0) {
						if(!in_array($id,$catlvanew[$startlevel])) {
							$catlvanew[$startlevel][]=$id;
						}
						$catparent=intval($catparenta[$id.'c']);
						$catparentanew[$id.'c']=$catparent;
						$id=$catparent;
						$startlevel--;
					}
				}
			}
		}
		$catlva=0;
		$catparenta=array();
		$i=0;
		$catnameanew=array();
		while($i<3) {
			$catparenta[$i]=array();
			$catnameanew[$i]=array();
			foreach($catlvanew[$i] as $index => $id) {
				$catparenta[$i][$index]=$catparentanew[$id.'c'];
				$catnameanew[$i][$index]='\''.addslashes($catnamea[$id.'c']).'\'';
			}
			$i++;
		}
		$catparentanew=0;
		$catnamea=0;
		
		$zones=array();
		$overlays=array();
		$precincts=array();
		
		$zonenames=array();
		$overlaynames=array();
		$precinctnames=array();
		
		$zoneparenttemp=array();
		$overlayparenttemp=array();
		$precinctparenttemp=array();
		
		$db->setQuery('SELECT #__sobipro_field_data.baseData, #__sobipro_object.id FROM #__sobipro_field INNER JOIN #__sobipro_field_data USING(fid) INNER JOIN #__sobipro_object ON #__sobipro_field_data.sid=#__sobipro_object.id WHERE #__sobipro_object.approved>0 AND #__sobipro_object.state>0 AND #__sobipro_field.nid=\'field_name\' AND #__sobipro_object.oType=\'entry\' AND #__sobipro_object.name!=#__sobipro_field_data.baseData ORDER BY #__sobipro_object.name');
		$fixnamepool=$db->loadAssocList();
		$checknames=array();
		foreach($fixnamepool as $fnr) {
			$checknames[intval($fnr['id']).'n']=$fnr['baseData'];
		}
		unset($fixnamepool);
		
		$db->setQuery('SELECT #__sobipro_field_option_selected.optValue+0 AS typekey, #__sobipro_object.name, #__sobipro_object.id, #__sobipro_relations.pid FROM #__sobipro_field INNER JOIN #__sobipro_field_option_selected USING(fid) INNER JOIN #__sobipro_object ON #__sobipro_field_option_selected.sid=#__sobipro_object.id INNER JOIN #__sobipro_relations ON #__sobipro_object.id=#__sobipro_relations.id WHERE #__sobipro_object.approved>0 AND #__sobipro_object.state>0 AND #__sobipro_field.nid=\'field_type\' AND #__sobipro_object.oType=\'entry\' ORDER BY #__sobipro_object.name');
		$entrypool=$db->loadAssocList();
		foreach($entrypool as $epkey => $entryassoc) {
			$entryassoc[$epkey]=0;
			$pid=intval($entryassoc['pid']);
			if(in_array($pid,$catlvanew[2])) {
				$type=intval($entryassoc['typekey']);
				if($type<2) {
					$id=intval($entryassoc['id']);
					if(!in_array($id,$zones)) {
						$zones[]=$id;
						if(isset($checknames[$id.'n'])) {
							$name=$checknames[$id.'n'];
						} else {
							$name=$entryassoc['name'];
						}
						$zonenames[]="'".addslashes($name)."'";
						if(is_array($zoneparenttemp[$pid.'p'])) {
							if(!in_array($id,$zoneparenttemp[$pid.'p'])) {
								$zoneparenttemp[$pid.'p'][]=$id;
							}
						} else {
							$zoneparenttemp[$pid.'p']=array($id);
						}
					}
				} else if($type>2) {
					$id=intval($entryassoc['id']);
					if(!in_array($id,$overlays)) {
						$overlays[]=$id;
						if(isset($checknames[$id.'n'])) {
							$name=$checknames[$id.'n'];
						} else {
							$name=$entryassoc['name'];
						}
						$overlaynames[]="'".addslashes($name)."'";
						if(is_array($overlayparenttemp[$pid.'p'])) {
							if(!in_array($id,$overlayparenttemp[$pid.'p'])) {
								$overlayparenttemp[$pid.'p'][]=$id;
							}
						} else {
							$overlayparenttemp[$pid.'p']=array($id);
						}
					}
				} else {
					$id=intval($entryassoc['id']);
					if(!in_array($id,$precincts)) {
						$precincts[]=$id;
						if(isset($checknames[$id.'n'])) {
							$name=$checknames[$id.'n'];
						} else {
							$name=$entryassoc['name'];
						}
						$precinctnames[]="'".addslashes($name)."'";
						if(is_array($precinctparenttemp[$pid.'p'])) {
							if(!in_array($id,$precinctparenttemp[$pid.'p'])) {
								$precinctparenttemp[$pid.'p'][]=$id;
							}
						} else {
							$precinctparenttemp[$pid.'p']=array($id);
						}
					}
				}
			}
		}
		unset($checknames);
		
		$zoneassociations=array();
		$overlayassociations=array();
		$precinctassociations=array();
		$ytvids=array();
		$councilhps=array();
		$councillinks=array();
		foreach($catlvanew[1] as $councilindex => $councilid) {
			list($youtube,$link,$helppoints)=profilef::parsehelp($councilid);
			$councillinks[$councilindex]="'".addslashes($link)."'";
			$councilhps[$councilindex]="'".addslashes(profilef::hte($helppoints))."'";
			$ytvids[$councilindex]="'".addslashes(profilef::hte($youtube))."'";
		}
		foreach($catlvanew[2] as $schemeindex => $schemeid) {
			if(isset($zoneparenttemp[$schemeid.'p'])) {
				$zoneassociations[$schemeindex]='['.implode(',',$zoneparenttemp[$schemeid.'p']).']';
			} else {
				$zoneassociations[$schemeindex]='[]';
			}
			$zoneparenttemp[$schemeid.'p']=0;
			if(isset($overlayparenttemp[$schemeid.'p'])) {
				$overlayassociations[$schemeindex]='['.implode(',',$overlayparenttemp[$schemeid.'p']).']';
			} else {
				$overlayassociations[$schemeindex]='[]';
			}
			$overlayparenttemp[$schemeid.'p']=0;
			if(isset($precinctparenttemp[$schemeid.'p'])) {
				$precinctassociations[$schemeindex]='['.implode(',',$precinctparenttemp[$schemeid.'p']).']';
			} else {
				$precinctassociations[$schemeindex]='[]';
			}
			$precinctparenttemp[$schemeid.'p']=0;
		}
		unset($zoneparenttemp);
		unset($overlayparenttemp);
		unset($precinctparenttemp);
		//parseyt($id)
		return 'var catlevels=[['.implode(',',$catlvanew[0]).'],['.implode(',',$catlvanew[1]).'],['.implode(',',$catlvanew[2]).']];
var catassociations=[[],['.implode(',',$catparenta[1]).'],['.implode(',',$catparenta[2]).']];
var catnames=[['.implode(',',$catnameanew[0]).'],['.implode(',',$catnameanew[1]).'],['.implode(',',$catnameanew[2]).']];
var zones=['.implode(',',$zones).'];
var zoneassociations=['.implode(',',$zoneassociations).'];
var zonenames=['.implode(',',$zonenames).'];
var overlays=['.implode(',',$overlays).'];
var overlayassociations=['.implode(',',$overlayassociations).'];
var overlaynames=['.implode(',',$overlaynames).'];
var councilvideos=['.implode(',',$ytvids).'];
var councillinks=['.implode(',',$councillinks).'];
var councilhelp=['.implode(',',$councilhps).'];
var precincts=['.implode(',',$precincts).'];
var precinctassociations=['.implode(',',$precinctassociations).'];
var precinctnames=['.implode(',',$precinctnames).'];';
	}
	
	public function profilegetdetailfields($id) {
		$db =& JFactory::getDBO();
		$db->setQuery('SELECT #__sobipro_field.nid, #__sobipro_field_data.baseData, #__sobipro_field_option_selected.optValue FROM #__sobipro_field INNER JOIN #__sobipro_field_data USING(fid) LEFT OUTER JOIN #__sobipro_field_option_selected USING(fid,sid) WHERE sid='.intval($id));
		$fieldpool=$db->loadAssocList();
		$fielda=array();
		foreach($fieldpool as $fielddata) {
			$fielda[profilef::profilecleannid($fielddata['nid'])]=trim(stripslashes($fielddata[(is_null($fielddata['optValue'])?'baseData':'optValue')]));
		}
		return $fielda;
	}
	public function profilegetname($id) {
		$db =& JFactory::getDBO();
		$db->setQuery('SELECT name FROM #__sobipro_object WHERE id='.intval($id));
		return $db->loadResult();
	}
	public function hte($str) {
		return htmlentities($str,ENT_COMPAT,'UTF-8');
	}
	public function unhte($str) {
		return html_entity_decode($str,ENT_COMPAT,'UTF-8');
	}
	public function parsehelplink($link) {
		$link=trim($link); // Remove whitespace
		if(empty($link)) {
			return '';
		}
		if(substr($link,0,7)=='http://') {
			$link=substr($link,7);
			$https=false;
		} else if(substr($link,0,8)=='https://') {
			$link=substr($link,8);
			$https=true;
		} else {
			$link=explode('://',$link); // Unexpected protocol? Lets strip it anyway.
			if(count($link)>1) {
				$link=$link[1];
			} else {
				$link=$link[0];
			}
			$https=false;
		}
		$link=explode('?',$link); // Remove query strings
		$link=explode('/',$link[0]); // Remove paths
		$link=explode('@',$link[0]); // Remove username and password info from the url
		if(count($link)>1) {
			$link=$link[1];
		} else {
			$link=$link[0];
		}
		$link=explode(':',$link); // Remove the port number
		$link=preg_replace('/[^a-z0-9\-\.]/','',strtolower($link[0])); // Clean things up
		if(empty($link)) {
			return '';
		}
		if($https) {
			$link='|'.$link; // Add protocol marker
		}
		return $link;
	}
	public function parsehelp($id) {
		$db =& JFactory::getDBO();
		$db->setQuery('SELECT metaAuthor, metaKeys, metaDesc FROM #__sobipro_object WHERE id='.intval($id));
		if($line=$db->loadAssoc()) {
			$link=profilef::parsehelplink(stripslashes($line['metaAuthor']));
			$youtube=profilef::parseyt($id,stripslashes($line['metaDesc']));
			$helppoints=trim($line['metaKeys']);
			if(!empty($helppoints)) {
				$helppoints=preg_replace('/\s*[\n\r]\s*/','|',str_replace('|','',stripslashes($helppoints)));
			} else {
				$helppoints='';
			}
			return array($youtube,$link,$helppoints);
		}
		return array('','','');
	}
	public function parseyt($id,$ytcontent='') {
		if(empty($ytcontent)) {
			$db =& JFactory::getDBO();
			$db->setQuery('SELECT metaDesc FROM #__sobipro_object WHERE id='.intval($id));
			$ytcontent=$db->loadResult();
		}
		$ytcontent=trim($ytcontent);
		if(empty($ytcontent)) {
			return '';
		}
		$yttest=strtolower(substr($ytcontent,0,7));
		if($yttest=='http://') {
			$ytcontent=substr($ytcontent,7);
		} else if($yttest=='https:/') {
			$ytcontent=substr($ytcontent,8);
		}
		$yttest=strtolower(substr($ytcontent,0,4));
		if($yttest=='www.') {
			$ytcontent=substr($ytcontent,4);
		}
		$yttest=strtolower(substr($ytcontent,0,9));
		if($yttest=='youtu.be/') {
			$ytcontent=substr($ytcontent,9);
		} else if($yttest=='youtube.c') {
			$ytapos=strpos($ytcontent,'/');
			if(!is_bool($ytapos)) {
				$ytcontent=substr($ytcontent,$ytapos+1);
				$yttest=strtolower(substr($ytcontent,0,2));
				if($yttest=='v/') {
					$ytcontent=substr($ytcontent,2);
					$ytbpos=strpos($ytcontent,'?');
					if(!is_bool($ytbpos)) {
						$ytcontent=substr($ytcontent,0,$ytbpos);
					}
				} else {
					$yttest=strtolower(substr($ytcontent,0,6));
					if($yttest=='embed/') {
						$ytcontent=substr($ytcontent,6);
						$ytbpos=strpos($ytcontent,'?');
						if(!is_bool($ytbpos)) {
							$ytcontent=substr($ytcontent,0,$ytbpos);
						}
					} else {
						$fragment='';
						$query='';
						$querypos=strpos($ytcontent,'?');
						if(!is_bool($querypos)) {
							$fragmentpos=strpos($ytcontent,'#',$querypos);
							if(!is_bool($fragmentpos)) {
								$query=substr($ytcontent,$querypos+1,$fragmentpos-($querypos+1));
								$fragment=substr($ytcontent,$fragmentpos+1);
							} else {
								$query=substr($ytcontent,$querypos+1);
							}
						} else {
							$fragment=substr($ytcontent,$fragmentpos+1);
							$fragmentpos=strpos($ytcontent,'#');
						}
						if(!empty($query)) {
							$querypos=stripos($query,'v=');
						} else {
							$querypos=false;
						}
						if(!is_bool($querypos)) {
							$ytcontent=substr($query,$querypos+2);
							$querypos=strpos($ytcontent,'&');
							if(!is_bool($querypos)) {
								$ytcontent=substr($ytcontent,0,$querypos);
							}
						} else {
							if(!empty($fragment)) {
								$fragmentpos=strrpos($fragment,'/');
								if(!is_bool($fragmentpos)) {
									$ytcontent=substr($fragment,$fragmentpos+1);
								}
							}
						}
					}
				}
			}
		}
		return $ytcontent;
	}
	public function profilegetmuddefinition($id) {
		$db =& JFactory::getDBO();
		$db->setQuery('SELECT introtext, description FROM #__sobipro_category WHERE id='.intval($id));
		$mdt=$db->loadAssoc();
		if(!empty($mdt)) {
			$name=trim(preg_replace('/\s+/',' ',$mdt['introtext']));
			$description=trim(preg_replace('/\s+/',' ',profilef::unhte(strip_tags(str_replace('>','> ',$mdt['description'])))));
		} else {
			$name='';
			$description='';
		}
		return array($name,$description);
	}
	//public function profilegetaccountoutput
	public function profilegetpastsearchesoutput() {
		return '<h2>Search History</h2>'.profilef::profilegetpastsearches();
	}
	public function profileprintpastsearchesoutput() {
		echo profilef::profilegetpastsearchesoutput();
	}
	public function profilegetpastsearches() {
		$ret='<table class="ptpastsearch"><tr><th scope="col">Your Ref</th><th scope="col">Date</th><th scope="col">Propertease Ref</th></tr>';
		$searches=profilef::profilegetsearchlist();
		foreach($searches as $search) {
			$link=JURI::base().'?sr='.intval($search['id']);
			$ret.='<tr><td><a href="'.$link.'">'.profilef::hte($search['search_reference']).'</a></td><td><a href="'.$link.'">'.profilef::hte($search['search_date']).'</a></td><td><a href="'.$link.'">'.profilef::hte($search['search_id']).'</a></td></tr>';
		}
		$ret.='</table>';
		return $ret;
	}
	public function profilegetoutput() {
		if(intval($_REQUEST['concr'])) {
			return '<p><strong>Your request has been sent.</strong></p>';
		} else if(intval($_REQUEST['sr'])>0) {
			return profilef::profilegetresults_new($_REQUEST['sr']).profilef::profilegetpdfbutton($_REQUEST['sr']);
		} else if(profilef::profilecansearch()) {
			if(profilef::profileconcierge()) {
				$ret='';
				$ret.='<form action="" method="post" class="ptsearch">';
				$ret.='<h2>Concierge Search</h2>';
				$conccont=true;
				if(isset($_REQUEST['concr'])) {
					if(intval($_REQUEST['concr'])) {
						$ret.='<p><strong>Your request has been sent.</strong></p>';
						$conccont=false;
					} else {
						$ret.='<p><strong>You need to enter an address.</strong></p>';
					}
				} else {
					$ret.='<p>What is the address of the property that you would like a report for?</p>';
				}
				if($conccont) {
					$ret.='<p id="refw"><label><strong>Address:</strong></label><input name="conciergeaddy" type="text"></p>';
					$ret.='<p id="submitw"><input type="submit" value="Submit"></p>';
				}
				$ret.='</form>';
				return $ret;
			}
			return profilef::profilegetformhtml().'<script type="text/javascript">'.profilef::profilegetformjs().'</script><noscript><p>You need javascript enabled to use this form.</p></noscript>';
		} else {
			return '<p>Your subscription is either pending or expired. <a href="index.php/subscribe">Click here to renew or upgrade your subscription.</a></p>';
		}
	}
	public function profileprintoutput() {
		echo profilef::profilegetoutput();
	}
	public function profilegetformjs() {
		return profilef::profilegetcatdata()."
var $ = jQuery.noConflict();
var refw=document.getElementById('refw');
var statew=document.getElementById('statew');
var statedd=document.getElementById('state');
var councilw=document.getElementById('councilw');
var councildd=document.getElementById('council');
var schemew=document.getElementById('schemew');
var schemedd=document.getElementById('scheme');
var zonew=document.getElementById('zonew');
var zonedd=document.getElementById('zone');
var overlaysw=document.getElementById('overlaysw');
var overlaysdd=document.getElementById('overlays');
var planw=document.getElementById('planw');
var plandd=document.getElementById('plan');
var submitw=document.getElementById('submitw');
var videoins=document.getElementById('videoins');
var helpins=document.getElementById('helpins');

var refwj=$('#refw');
var statewj=$('#statew');
var stateddj=$('#state');
var councilwj=$('#councilw');
var councilddj=$('#council');
var schemewj=$('#schemew');
var schemeddj=$('#scheme');
var zonewj=$('#zonew');
var zoneddj=$('#zone');
var overlayswj=$('#overlaysw');
var overlaysddj=$('#overlays');
var planwj=$('#planw');
var planddj=$('#plan');
var submitwj=$('#submitw');
var videoinsj=$('#videoins');
var helpinsj=$('#helpins');

var cansubmit=false;

function show_ref() {
	$('#refw').css('display','block');
}
function hide_state() {
	statewj.css('display','none');
	hide_council();
}
function show_state() {
	statewj.css('display','block');
}
function hide_council() {
	councilwj.css('display','none');
	hide_scheme();
}
function show_council() {
	councilwj.css('display','block');
}
function hide_scheme() {
	schemewj.css('display','none');
	hide_restriction();
}
function show_scheme() {
	schemewj.css('display','block');
}
function hide_restriction() {
	zonewj.css('display','none');
	overlayswj.css('display','none');
	planwj.css('display','none');
	hide_submit();
}
function show_restriction() {
	zonewj.css('display','block');
	overlayswj.css('display','block');
	planw.stylej.css('display','block');
}
function hide_submit() {
	submitwj.css('display','none');
	submitwj.html(' ');
	cansubmit=false;
}
function show_submit() {
	submitwj.html('<input type=\"submit\" value=\"Submit\">');
	submitwj.css('display','block');
	cansubmit=true;
}
function execute_submit() {
	return cansubmit;
}
function destroy_general(obj) {
	while(obj.options.length) {
		obj.remove(0);
	}
}
function destroy_state() {
	destroy_general(statedd);
	destroy_council();
}
function destroy_council() {
	destroy_general(councildd);
	destroy_scheme();
}
function destroy_scheme() {
	if(videoins) {
		videoins.innerHTML='';
	}
	destroy_general(schemedd);
	destroy_restriction();
}
function destroy_restriction() {
	destroy_general(zonedd);
	destroy_general(overlaysdd);
	destroy_general(plandd);
}
function get_state() {
	var i=0;
	var c=catlevels[0].length;
	destroy_state();
	hide_state();
	var $ = jQuery.noConflict();
	$('.state-n').html('')
		 .append($('<option></option>')
         .attr('value',' - ')
         .text('Select State')); 
	while(i<c) {
		statedd.options[0] = new Option('(Select One)',0);
		statedd.options[statedd.options.length] = new Option(catnames[0][i],catlevels[0][i]);

		$('.state-n')
         .append($('<option></option>')
         .attr('value',catlevels[0][i])
         .text(catnames[0][i])); 
	
		i++;
	}
	statedd.selectedIndex=0;
	show_state();
}
function get_council() {
	var ddval=jQuery('#state').val();
	
	var i=0;
	var c=0;
	c=catlevels[1].length;
	destroy_council();
	hide_council();
	var $ = jQuery.noConflict();
	$('.council-n').html('')
		 .append($('<option></option>')
         .attr('value',' - ')
         .text('Select Regional Council')); 
	if(ddval>0) {
		councildd.options[0] = new Option('(Select One)',0);
		while(i<c) {
			if(catassociations[1][i]==ddval) {
				councildd.options[councildd.options.length] = new Option(catnames[1][i],catlevels[1][i]);

				$('.council-n')
			         .append($('<option></option>')
			         .attr('value',catlevels[1][i])
			         .text(catnames[1][i])); 

			}
			i++;
		}
		councildd.selectedIndex=0;
		show_council();
	}
}
function get_scheme() {
	var ddval=jQuery('#council').val();
	var i=0;
	var c=0;
	var tlink='';
	var tlinklab='';
	destroy_scheme();
	hide_scheme();
	var $ = jQuery.noConflict();
	$('.scheme-n').html('')
		 .append($('<option></option>')
         .attr('value',' - ')
         .text('Select Regional Council')); 

	$('#zonebody').empty().append(
		$('<div class=\"videoins\">').append($('#videoins').html()),
		$('<div class=\"helpins\">').append($('#helpins').html()),
		$('<div class=\"clearfix\">')
	);
	if(ddval>0) {
		c=catlevels[1].length;
		if(videoins) {
			while(i<c) {
				if(catlevels[1][i]==ddval) {
					if(councilvideos[i]) {
						/*videoins.innerHTML='<h3>Need Help Finding Your Zone?</h3><div class=\"videowrapper\"><div class=\"videoheight\"></div><iframe width=\"100%\" height=\"315\" src=\"https://www.youtube.com/embed/'+councilvideos[i]+'\" frameborder=\"0\" allowfullscreen></iframe></div>';*/
						jQuery('.videoins').html('<h3>Need Help Finding Your Zone?</h3><div class=\"videowrapper\"><div class=\"videoheight\"></div><iframe width=\"100%\" height=\"315\" src=\"https://www.youtube.com/embed/'+councilvideos[i]+'\" frameborder=\"0\" allowfullscreen></iframe></div>');
						i=c;
					}
				}
				i++;
			}
		}
		i=0;
		if(helpins) {
			while(i<c) {
				if(catlevels[1][i]==ddval) {
					if(councillinks[i]&&councilhelp[i]) {
						tlinklab=councillinks[i];
						if(tlinklab.charAt(0)==124) {
							tlinklab=tlinklab.substring(1);
							tlink='https://'+tlinklab;
							tlinklab=tlink;
						} else {
							tlink='http://'+tlinklab;
						}
						tlink+='/';
						//helpins.innerHTML='<h3>How to find your property information for '+catnames[1][i]+':</h3><ul><li>Go to <a href=\"'+tlink+'\" target=\"_blank\">'+tlinklab+'</a>;</li><li>'+councilhelp[i].split('|').join('</li><li>')+'</li><li>Enter this information into the PropertEASE dropdown box selections;</li><li>Click \\'Submit\\'</li><li>Print report.</li></ul>';
						jQuery('.helpins').html('<h3>How to find your property information for '+catnames[1][i]+':</h3><ul><li>Go to <a href=\"'+tlink+'\" target=\"_blank\">'+tlinklab+'</a>;</li><li>'+councilhelp[i].split('|').join('</li><li>')+'</li><li>Enter this information into the PropertEASE dropdown box selections;</li><li>Click \\'Submit\\'</li><li>Print report.</li></ul>');
						i=c;
					}
				}
				i++;
			}
		}
		//helpins
		//councillinks
		//councilhelp
		i=0;
		c=catlevels[2].length;
		schemedd.options[0] = new Option('(Select One)',0);
		while(i<c) {
			if(catassociations[2][i]==ddval) {
				schemedd.options[schemedd.options.length] = new Option(catnames[2][i],catlevels[2][i]);

				$('.scheme-n')
			         .append($('<option></option>')
			         .attr('value',catlevels[2][i])
			         .text(catnames[2][i])); 

			}
			i++;
		}
		schemedd.selectedIndex=0;
		show_scheme();
	}
}
function get_restriction() {
	var ddval=jQuery('#scheme').val();
	var foundindex=0;
	var i=0;
	var u=0;
	var c=catlevels[2].length;
	var ca=0;
	var csa=0;
	destroy_restriction();
	hide_restriction();

	var $ = jQuery.noConflict();
	$('.zone-n').html('')
		 .append($('<option></option>')
         .attr('value',' - ')
         .text('Select Zone')); 

	if(ddval>0) {
		zonedd.options[0] = new Option('(Select One)',0);
		overlaysdd.options[0] = new Option('None Applicable',0);
		plandd.options[0] = new Option('(Select One)',0);
		plandd.options[1] = new Option('None Applicable',0);
		
		while(i<c) {
			if(catlevels[2][i]==ddval) {
				ca=zoneassociations[i].length;
				u=0;
				while(u<ca) {
					foundindex=zones.indexOf(zoneassociations[i][u]);
					if(foundindex>=0) {
						zonedd.options[zonedd.options.length] = new Option(zonenames[foundindex],zones[foundindex]);

						$('.zone-n')
					         .append($('<option></option>')
					         .attr('value',zones[foundindex])
					         .text(zonenames[foundindex])); 

					}
					u++;
				}
				ca=overlayassociations[i].length;
				u=0;
				while(u<ca) {
					foundindex=overlays.indexOf(overlayassociations[i][u]);
					if(foundindex>=0) {
						overlaysdd.options[overlaysdd.options.length] = new Option(overlaynames[foundindex],overlays[foundindex]);
						$('.overlays-n select')
					         .append($('<option></option>')
					         .attr('value',overlays[foundindex])
					         .text(overlaynames[foundindex])); 
					}
					u++;
				}

				$('.precinct-n').html('')
					 .append(
					 	$('<option></option>')
			         	.attr('value','0')
			         	.text('Select Neighbourhood Plan + Precinct'),
			         	$('<option></option>')
			         	.attr('value','0')
			         	.text('None Applicable')
			         ); 

				ca=precinctassociations[i].length;
				u=0;
				while(u<ca) {
					foundindex=precincts.indexOf(precinctassociations[i][u]);
					if(foundindex>=0) {
						plandd.options[plandd.options.length] = new Option(precinctnames[foundindex],precincts[foundindex]);

						$('.precinct-n')
					         .append($('<option></option>')
					         .attr('value',precincts[foundindex])
					         .text(precinctnames[foundindex])); 

					}
					u++;
				}
			}
			i++;
		}
		zonedd.selectedIndex=0;
		overlaysdd.selectedIndex=-1;
		plandd.selectedIndex=0;
		show_restriction();
	}
}
function get_submit() {
	var ddval=jQuery('#zone').val();
	if(ddval>0) {
		show_submit();
	} else {
		hide_submit();
	}
}
show_ref();
get_state();

jQuery(document).ready(function($){
	$('.state-n').change(function(){
		$('#state').val($(this).val());
		get_council();
		$('.step-process ul li:nth-child(2)').addClass('active');
		$(this).parent('span').parent('a').addClass('filled-step');
	});
	$('.council-n').change(function(){
		$('#council').val($(this).val());
		get_scheme();
		$('.step-process ul li:nth-child(3)').addClass('active');
		$(this).parent('span').parent('a').addClass('filled-step');
	});
	$('.scheme-n').change(function(){
		$('#scheme').val($(this).val());
		get_restriction();
		$('.step-process ul li:nth-child(4)').addClass('active');
		$('#collapsethree').addClass('filled-step');
		console.log('scheme');
	});
	$('.zone-n').change(function(){
		$('#zone').val($(this).val());
		get_submit();
		$('.step-process ul li:nth-child(5)').addClass('active');
		$('#headingFour').find('a').addClass('filled-step');
	});
	$('.overlays-n select').change(function(){

		$('#overlays').val($(this).val());
		$('.step-process ul li:nth-child(6)').addClass('active');
		$('#headingFive').find('a').addClass('filled-step');
	});
	$('.precinct-n').change(function(){
		$('#plan').val($(this).val());
		$('.step-process ul li:nth-child(7)').addClass('active');
		$(this).parent('span').parent('a').addClass('filled-step');
		$('.btn-sbm').addClass('btn-active-submit');
	});
	$('.btn-sbm').click(function(){
		$('#submitw input').trigger('click');
	});
	
});
";
	}
	public function profilegetpdfbutton($sr) {
		return '<form action="" method="post">
		<input type="submit" id="downloadpdf" value="Download PDF">
		<input name="pto" type="hidden" value="pdf">
		<input name="sr" type="hidden" value="'.intval($sr).'">
		</form>';
	}


	public function profilegetformhtml() {
		return '<form action="" id="ptsearch" method="post" class="ptsearch" onsubmit="return execute_submit();">
<h2>New Search</h2>
<input name="pgmm" type="hidden" value="1">
<p id="refw" style="display:none;">
	<label><strong>Search reference:</strong></label>
	<input name="reference" type="text" value="">
</p>
<p id="statew" style="display:none;">
	<label><strong>Select State:</strong></label>
	<select id="state" name="state" onchange="get_council();">
	</select>
</p>
<p id="councilw" style="display:none;">
	<label><strong>Select Local Council:</strong></label>
	<select id="council" name="council" onchange="get_scheme();">
	</select>
</p>
<p id="schemew" style="display:none;">
	<label><strong>Select Planning Scheme:</strong></label>
	<select id="scheme" name="scheme" onchange="get_restriction();">
	</select>
</p>
<p id="zonew" style="display:none;">
	<label><strong>Select Zone:</strong></label>
	<select id="zone" name="zone" onchange="get_submit();">
	</select>
</p>
<p id="overlaysw" style="display:none;">
	<label><strong>Select Overlays:</strong></label>
	<select size="8" id="overlays" name="overlays[]" multiple="multiple">
	</select><br><span class="small">Hold the CTRL or Cmd key to select multiple options.</span>
</p>
<p id="planw" style="display:none;">
	<label><strong>Select Neighbourhood Plan + Precinct:</strong></label>
	<select id="plan" name="plan">
	</select>
</p>
<p id="submitw" style="display:none;">&nbsp;</p>
</form>';
	}

	public function profileoverrideprepare(&$dbfields,&$numfields,&$textfields,&$setfields,&$clearfields) {
		if(empty($numfields)) { $numfields=array(); }
		if(empty($textfields)) { $textfields=array(); }
		if(empty($setfields)) { $setfields=array(); }
		if(empty($clearfields)) { $clearfields=array(); }
		if(!is_array($numfields)) { $numfields=array($numfields.''); }
		if(!is_array($textfields)) { $textfields=array($textfields.''); }
		if(!is_array($setfields)) { $setfields=array($setfields.''); }
		if(!is_array($clearfields)) { $clearfields=array($clearfields.''); }
		$fielddata=array();
		foreach($numfields as $numfield) {
			if(isset($dbfields['field_'.$numfield])) {
				$fielddata[$numfield]=trim($dbfields['field_'.$numfield]);
			} else {
				$fielddata[$numfield]='';
			}
		}
		return $fielddata;
	}
	public function profilegroupoverlayoverride(&$data,&$dbfields,&$orphantext,$primarynumfield,$ismax=false,$textfields=array()) { //,$numfields=array(),$setfields=array(),$clearfields=array()
		$setfields=array();
		$clearfields=array();
		$numfields=array();
		$fielddata=profilef::profileoverrideprepare($dbfields,$numfields,$textfields,$setfields,$clearfields);
		if(isset($dbfields['field_'.$primarynumfield])) {
			$primaryfielddata=trim($dbfields['field_'.$primarynumfield]);
		} else {
			$primaryfielddata='';
		}
		$or=false;
		if(strlen($primaryfielddata)>0) {
			$primaryfielddata=floatval($primaryfielddata);
			if(is_null($data['field_'.$primarynumfield])) {
				$or=true;
			} else {
				if($ismax) {
					if(floatval($data['field_'.$primarynumfield])>$primaryfielddata) {
						$or=true;
					}
				} else {
					if(floatval($data['field_'.$primarynumfield])<$primaryfielddata) {
						$or=true;
					}
				}
			}
		} else {
			foreach($textfields as $textfield) {
				if(empty($orphantext[$textfield])) {
					$orphantext[$textfield]=$dbfields['field_'.$textfield];
				} else {
					$orphantext[$textfield].='\n\n'.$dbfields['field_'.$textfield];
				}
			}
		}
		if($or) {
			/*foreach($setfields as $setfield) {
				$data['field_'.$setfield]=true;
			}
			foreach($clearfields as $clearfield) {
				$data['field_'.$clearfield]=false;
			}*/
			if(strlen($primaryfielddata)<1) {
				$data['field_'.$primarynumfield]=NULL;
			} else {
				$data['field_'.$primarynumfield]=$primaryfielddata;
			}
			foreach($fielddata as $fk => $fd) {
				if(strlen($fd)<1) {
					$data['field_'.$fk]=NULL;
				} else {
					$data['field_'.$fk]=$fd;
				}
			}
			foreach($textfields as $textfield) {
				if(isset($dbfields['field_'.$textfield])) {
					$data['field_'.$textfield]=$dbfields['field_'.$textfield];
				} else {
					$data['field_'.$textfield]='';
				}
			}
		}
	}
	public function profilegroupoverride(&$data,&$dbfields,$numfields=array(),$textfields=array(),$setfields=array(),$clearfields=array()) {
		$fielddata=profilef::profileoverrideprepare($dbfields,$numfields,$textfields,$setfields,$clearfields);
		$or=false;
		foreach($fielddata as $fd) {
			if(!$or) {
				if(strlen($fd)>0) {
					$or=true;
				}
			}
		}
		if(!$or) {
			foreach($textfields as $textfield) {
				if(!$or) {
					if(isset($dbfields['field_'.$textfield])) {
						if(trim($dbfields['field_'.$textfield])!='') {
							$or=true;
						}
					}
				}
			}
		}
		if($or) {
			foreach($setfields as $setfield) {
				$data[$setfield]=true;
			}
			foreach($clearfields as $clearfield) {
				$data[$clearfield]=false;
			}
			foreach($fielddata as $fk => $fd) {
				if(strlen($fd)<1) {
					$data[$fk]=NULL;
				} else {
					$data[$fk]=$fd;
				}
			}
			foreach($textfields as $textfield) {
				if(isset($dbfields['field_'.$textfield])) {
					$data[$textfield]=$dbfields['field_'.$textfield];
				} else {
					$data[$textfield]='';
				}
			}
		}
	}
	public function profilegetsearch($searchid) {
		$sectionid=profilef::profilesectionid();
		$user=JFactory::getUser();
		$ret=array();
		$uid=intval($user->id);
		if($uid>0) {
			$db =& JFactory::getDBO();
			$db->setQuery("SELECT #__sobipro_object.name, #__sobipro_object.owner, DATE_FORMAT(#__sobipro_object.createdTime,'%d.%m.%Y') AS report_date, #__sobipro_field_data.baseData FROM #__sobipro_object INNER JOIN #__sobipro_field_data ON #__sobipro_object.id=#__sobipro_field_data.sid AND #__sobipro_object.id=".intval($searchid)." INNER JOIN #__sobipro_field ON #__sobipro_field_data.fid=#__sobipro_field.fid AND 'field_searchdata'=#__sobipro_field.nid WHERE #__sobipro_object.state=1 AND #__sobipro_object.oType='entry' AND #__sobipro_object.owner=".$uid);
			if($row=$db->loadAssoc()) {
				$ret['reference']=trim($row['name']);
				$ret['date']=trim($row['report_date']);
				$search=trim($row['baseData']);
				$search=explode(';',$search);
				$ret['state']=intval($search[0]);
				$ret['council']=intval($search[1]);
				$ret['scheme']=intval($search[2]);
				$ret['zone']=intval($search[3]);
				$overlays=explode(',',$search[4]);
				foreach($overlays as $okey => $oval) {
					$overlays[$okey]=intval($oval);
				}
				$db->setQuery("SELECT #__osmembership_subscribers.first_name, #__osmembership_subscribers.last_name, #__users.name FROM #__users LEFT OUTER JOIN #__osmembership_subscribers ON #__users.id=#__osmembership_subscribers.user_id WHERE #__users.id>0 AND #__users.id=".intval($row['owner']));
				if($userrow=$db->loadAssoc()) {
					$fn=trim($userrow['first_name']);
					if(!empty($fn)) {
						$sn=trim($userrow['last_name']);
						if(!empty($sn)) {
							$ret['by']=$fn.' '.$sn;
						} else {
							$ret['by']=$fn;
						}
					} else {
						$fn=trim($userrow['name']);
						if(!empty($fn)) {
							$ret['by']=$fn;
						} else {
							$ret['by']='';
						}
					}
				}
				$ret['overlays']=$overlays;
				$ret['plan']=intval($search[5]);
			}
		}
		return $ret;
	}
	public function profileexpiresinglesearch() {
		$user=JFactory::getUser();
		$uid=intval($user->id);
		if($uid>0) {
			$db =& JFactory::getDBO();
			$db->setQuery('SELECT id FROM #__osmembership_subscribers WHERE user_id='.$uid.' AND plan_id=3 AND published=1 AND to_date>NOW() ORDER BY to_date ASC LIMIT 1');
			$sid=intval($db->loadResult());
			if($sid>0) {
				$db->setQuery('UPDATE #__osmembership_subscribers SET to_date=DATE_SUB(NOW(), INTERVAL 1 DAY), published=2 WHERE id='.$sid);
				$db->execute();
			}
		}
	}
	public function profileexpireconciergesearch() {
		$user=JFactory::getUser();
		$uid=intval($user->id);
		if($uid>0) {
			$db =& JFactory::getDBO();
			$db->setQuery('SELECT id FROM #__osmembership_subscribers WHERE user_id='.$uid.' AND plan_id=6 AND published=1 AND to_date>NOW() ORDER BY to_date ASC LIMIT 1');
			$sid=intval($db->loadResult());
			if($sid>0) {
				$db->setQuery('UPDATE #__osmembership_subscribers SET to_date=DATE_SUB(NOW(), INTERVAL 1 DAY), published=2 WHERE id='.$sid);
				$db->execute();
			}
		}
	}
	public function profilecansearch() {
		$user=JFactory::getUser();
		$uid=intval($user->id);
		if($uid>0) {
			$db =& JFactory::getDBO();
			$db->setQuery('SELECT COUNT(*) FROM #__osmembership_subscribers WHERE user_id='.$uid);
			if(intval($db->loadResult())>0) {
				$db->setQuery('SELECT COUNT(*) FROM #__osmembership_subscribers WHERE user_id='.$uid.' AND published=1 AND to_date>NOW()');
				if(intval($db->loadResult())>0) {
					return true;
				}
				return false;
			}
			return true;
		}
		return false;
	}
	public function profileconcierge() {
		$user=JFactory::getUser();
		$uid=intval($user->id);
		if($uid>0) {
			$db =& JFactory::getDBO();
			$db->setQuery('SELECT COUNT(*) FROM #__osmembership_subscribers WHERE user_id='.$uid.' AND plan_id=6 AND published=1 AND to_date>NOW()');
			if(intval($db->loadResult())>0) {
				$db->setQuery('SELECT COUNT(*) FROM #__osmembership_subscribers WHERE user_id='.$uid.' AND plan_id!=6 AND published=1 AND to_date>NOW()');
				if(intval($db->loadResult())>0) {
					return false;
				}
				return true;
			}
		}
		return false;
	}
	public function profilegetsearchlist() {
		$sectionid=profilef::profilesectionid();
		$user=JFactory::getUser();
		$uid=intval($user->id);
		$catid=1404;
		if($uid>0) {
			$db =& JFactory::getDBO();
			$db->setQuery("SELECT #__sobipro_object.name AS search_reference, DATE_FORMAT(#__sobipro_object.createdTime,'%d.%m.%Y') AS search_date, LPAD(#__sobipro_object.id, 12, '0') AS search_id, #__sobipro_object.id FROM #__sobipro_object INNER JOIN #__sobipro_relations USING(id) WHERE #__sobipro_object.state=1 AND #__sobipro_relations.pid=".$catid." AND #__sobipro_object.oType='entry' AND #__sobipro_object.owner=".$uid." ORDER BY #__sobipro_object.createdTime DESC");
			$rows=$db->loadAssocList();
			if($rows) {
				return $rows;
			}
		}
		return array();
	}
	public function profilesectionid() {
		return 1298;
	}
	public function profilesavesearch() {
		$sectionid=profilef::profilesectionid();
		
		$user=JFactory::getUser();
		$uid=intval($user->id);
		if($uid>0) {
			$db =& JFactory::getDBO();
			$db->setQuery("SELECT nid,fid FROM #__sobipro_field WHERE section=".$sectionid);
			$rows=$db->loadAssocList();
			$namefieldid=0;
			$catfieldid=0;
			$searchfieldid=0;
			//a:1:{i:0;s:4:"1255";}
			foreach($rows as $row) {
				if($row['nid']=='field_name') {
					$namefieldid=intval($row['fid']);
				}
				if($row['nid']=='field_category') {
					$catfieldid=intval($row['fid']);
				}
				if($row['nid']=='field_searchdata') {
					$searchfieldid=intval($row['fid']);
				}
			}
			if($namefieldid>0&&$catfieldid>0&&$searchfieldid>0&&$sectionid>0) {
				$catid=1404;
				$catstring=$catid.'';
				$catstring=base64_encode('a:1:{i:0;s:'.strlen($catstring).':"'.$catstring.'";}');
				$searchstr=intval($_REQUEST['state']).';'.intval($_REQUEST['council']).';'.intval($_REQUEST['scheme']).';'.intval($_REQUEST['zone']).';';
				$overlays=$_REQUEST['overlays'];
				if(empty($overlays)) {
					$overlays=array(0);
				} else if(!is_array($overlays)) {
					$overlays=array(intval($overlays));
				} else {
					foreach($overlays as $okey => $oval) {
						$overlays[$okey]=intval($oval);
					}
				}
				$searchstr.=implode(',',$overlays).';'.intval($_REQUEST['plan']).';';
				$ref=trim($_REQUEST['reference']);
				$db->setQuery("INSERT INTO #__sobipro_object SET name='".$db->escape($ref)."', oType='entry'");
				$db->execute();
				$oid=intval($db->insertid());
				if($oid>0) {
					$ip=$db->escape($_SERVER['REMOTE_ADDR']);
					$db->setQuery("UPDATE #__sobipro_object SET nid=CONCAT('pdsearch-',id), approved=1, confirmed=1, createdTime=NOW(), updatedTime=NOW(), validSince=NOW(), owner=".$uid.", updater=".$uid.", ownerIP='".$ip."', updaterIP='".$ip."', state=1, version=1 WHERE id=".$oid);
					$db->execute();
					$db->setQuery("INSERT INTO #__sobipro_relations SET id=".$oid.", pid=".$catid.", oType='entry', validSince=NOW(), position=0, validUntil='0000-00-00 00:00:00'");
					$db->execute();
					$db->setQuery("INSERT INTO #__sobipro_field_data (fid,sid,section,baseData) VALUES (".$namefieldid.",".$oid.",".$sectionid.",'".$db->escape($ref)."'), (".$catfieldid.",".$oid.",".$sectionid.",'".$db->escape($catstring)."'), (".$searchfieldid.",".$oid.",".$sectionid.",'".$db->escape($searchstr)."')");
					$db->execute();
					$db->setQuery("UPDATE #__sobipro_field_data SET lang='en-GB',enabled=1,approved=1,createdTime=NOW(), updatedTime=NOW(), createdBy=".$uid.", updatedBy=".$uid.", createdIP='".$ip."', updatedIP='".$ip."' WHERE sid=".$oid);
					$db->execute();
					profilef::profileexpiresinglesearch();
				}
				return $oid;
			}
		}
		return 0;
	}
	public function profilegetresults($searchid,$simple=false) {
		$app = JFactory::getApplication();
		$searchid=intval($searchid);
		$search=profilef::profilegetsearch($searchid);
		$data=profilef::profilegetdata($search['state'],$search['council'],$search['scheme'],$search['zone'],$search['overlays'],$search['plan']);
		$ht='';
		if(!$simple) {
			$ht.='<div class="ptheader">
	<div class="ptlogo">
		<div class="pti">';
		} else {
			$ht.='<table cellspacing="0" width="100%" cellpadding="6" border="0">
	<tr align="left" valign="top">
		<td rowspan="2">';
		}
		$ht.='<img src="'.JURI::base().'templates/'.$app->getTemplate().'/images/report-propertease-logo.gif" alt="Propertease">';
		if(!$simple) {
			$ht.='</div>
	</div>
	<div class="pthinfo">
		<div class="pthaddy">
			<div class="pti">
				<a href="http://www.propertease.com.au/">www.PropertEASE.com.au</a><br>
				<a href="mailto:info@propertease.com.au">info@PropertEASE.com.au</a>
			</div>
		</div>
		<div class="pthinfot">';
		} else {
			$ht.='</td>
		<td align="right">
			www.PropertEASE.com.au<br>
			info@PropertEASE.com.au
		</td>
	</tr>
	<tr align="left" valign="top">
		<td>';
		}
		$ht.='<table '.($simple?'cellspacing="0" cellpadding="6" border="0"':'').'>
				<tr'.($simple?' align="left" valign="top"':'').'>
					<th'.($simple?' align="right"':'').'>Report for:</th>
					<td>'.(empty($search['by'])?'N/A':(profilef::hte($search['by']))).'</td>
				</tr>
				<tr'.($simple?' align="left" valign="top"':'').'>
					<th'.($simple?' align="right"':'').'>Date:</th>
					<td>'.(empty($search['date'])?'N/A':(profilef::hte($search['date']))).'</td>
				</tr>
				<tr'.($simple?' align="left" valign="top"':'').'>
					<th'.($simple?' align="right"':'').'>Reference:</th>
					<td>'.(empty($search['reference'])?'N/A':(profilef::hte($search['reference']))).'</td>
				</tr>
			</table>';
		if(!$simple) {
			$ht.='</div>
	</div>
</div>';
		} else {
			$ht.='</td>
	</tr>
	<tr>
		<td bgcolor="#7AB700" colspan="2">&nbsp;</td>
	</tr>
</table>';
		}


		$ht.='<table '.($simple?'cellspacing="0" width="100%" cellpadding="6" border="1" bordercolor="#B58813"':'class="ptresults"').'>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Local Council</th>
    <td colspan="2">'.(empty($data['council'])?'N/A':(profilef::hte($data['council']))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Planning Scheme </th>
    <td colspan="2">'.(empty($data['scheme'])?'N/A':(profilef::hte($data['scheme']))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Zoning</th>
    <td colspan="2">'.(empty($data['zone'])?'N/A':(profilef::hte($data['zone']))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Overlays</th>
    <td colspan="2">';


		$overlaynames=array();
		foreach($data['overlays'] as $overlay) {
			$overlaynames[]=$overlay[0];
		}
		
		$ht.=profilef::hte((count($overlaynames)?implode(', ',$overlaynames):'N/A')).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Neighbourhood Plan <sup>1</sup> + Precinct</th>
    <td colspan="2">'.(empty($data['precinct'])?'':(profilef::hte($data['precinct']))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':' class="ptresheader"').'>
    <th colspan="3"'.($simple?' bgcolor="#F4E8AE"':'').'>RECONFIGURATION OF A LOT (e.g. Subdivision)</th>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Minimum Lot Size and Frontage Required'.($data['minfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['min'])?(is_null($data['minfrontage'])?'N/A':$data['minfrontage'].'m frontage'):(is_null($data['minfrontage'])?$data['min'].'sqm':$data['min'].'sqm and '.$data['minfrontage'].'m frontage')).'</td>
    <td'.(!$simple?(empty($data['lotinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['lotinfo'])?$data['lotinfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':' class="ptresheader"').'>
    <th colspan="3"'.($simple?' bgcolor="#F4E8AE"':'').'>MULTIPLE DWELLING ('.(!empty($data['muddefinition'])?profilef::hte($data['muddefinition']).') '.(!empty($data['muddisclaimer'])?'<sup>3</sup>':''):'Any multiple residential development)').'</th>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Minimum Lot Size and Frontage Required for unit development'.($data['minmultiplefromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['minmultiple'])?(is_null($data['minmultiplefrontage'])?'N/A':$data['minmultiplefrontage'].'m frontage'):(is_null($data['minmultiplefrontage'])?$data['minmultiple'].'sqm':$data['minmultiple'].'sqm and '.$data['minmultiplefrontage'].'m frontage')).'</td>
    <td'.(!$simple?(empty($data['lotmultipleinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['lotmultipleinfo'])?$data['lotmultipleinfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Height Prescribed'.($data['maxheightfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxstorey'])?(is_null($data['maxheight'])?'N/A':$data['maxheight'].'m above Natural Ground Level'):(is_null($data['maxheight'])?$data['maxstorey'].' storeys':$data['maxstorey'].' storeys and '.$data['maxheight'].'m above Natural Ground Level')).'</td>
    <td'.(!$simple?(empty($data['heightinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['heightinfo'])?$data['heightinfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Gross Floor Area/Plot Ratio Prescribed'.($data['maxgfafromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxgfa'])?'N/A':$data['maxgfa'].'% of the total site area').'</td>
    <td'.(!$simple?(empty($data['gfainfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['gfainfo'])?$data['gfainfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Site Cover Prescribed'.($data['maxcoverfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxcover'])?'N/A':$data['maxcover'].'% of the total site area').'</td>
    <td'.(!$simple?(empty($data['coverinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['coverinfo'])?$data['coverinfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Density Prescribed'.($data['maxdensityfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxdensity'])?'N/A':$data['maxdensity'].' per hectare').'</td>
    <td'.(!$simple?(empty($data['densityinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['densityinfo'])?$data['densityinfo']:'N/A'))).'</td>
  </tr>';
  		if(count($data['overlays'])>0) {
			$ht.='
  <tr'.($simple?' align="left" valign="top"':' class="ptresheader"').'>
    <th colspan="3"'.($simple?' bgcolor="#F4E8AE"':'').'>ADDITIONAL OVERLAY INFORMATION </th>
  </tr>';
  			foreach($data['overlays'] as $overlay) {
				$ht.='
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>'.profilef::hte($overlay[0]).'</th>
    <td colspan="2">'.nl2br(profilef::hte($overlay[1])).'</td>
  </tr>';
  			}
  		}
		if(!empty($data['mudsetback'])||!empty($data['housesetback'])||!empty($data['smalllotsetback'])||!empty($data['secondarysetback'])) {
			$ht.='
  <tr'.($simple?' align="left" valign="top"':' class="ptresheader"').'>
    <th colspan="3"'.($simple?' bgcolor="#F4E8AE"':'').'>GENERAL INFORMATION (Note, any information following are general provisions that may be altered by the zoning, neighbourhood plan and/or overlays of a site)</th>
  </tr>';
			if(!empty($data['mudsetback'])) {
				$ht.='
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Prescribed setbacks for a Multiple Unit Dwelling'.($data['mudsetbackfromplan']?' <sup>2</sup>':'').'</th>
    <td colspan="2">'.nl2br(profilef::hte($data['mudsetback'])).'</td>
  </tr>';
			}
			if(!empty($data['housesetback'])) {
				$ht.='
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Prescribed setbacks for a House'.($data['housesetbackfromplan']?' <sup>2</sup>':'').'</th>
    <td colspan="2">'.nl2br(profilef::hte($data['housesetback'])).'</td>
  </tr>';
			}
			if(!empty($data['smalllotsetback'])) {
				$ht.='
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Prescribed setbacks for Small Lot House (450sqm or less)'.($data['smalllotsetbackfromplan']?' <sup>2</sup>':'').'</th>
    <td colspan="2">'.nl2br(profilef::hte($data['smalllotsetback'])).'</td>
  </tr>';
			}
			if(!empty($data['secondarysetback'])) {
				$ht.='
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Prescribed secondary Dwelling Provisions'.($data['secondarysetbackfromplan']?' <sup>2</sup>':'').'</th>
    <td colspan="2">'.nl2br(profilef::hte($data['secondarysetback'])).'</td>
  </tr>';
			}
		}
		$ht.='
</table>
'.($simple?'<table width="100%" border="0" cellpadding="6" cellspacing="0" bgcolor="#008CCC"><tr><td color="#FFFFFF">':'<div class="ptextra">').'
<sup>1</sup> Neighbourhood Plan can refer to a Local Plan, Precinct, Local Area... etc. dependent on the planning scheme.<br>
<sup>2</sup> Provision taken from a Local or Neighbourhood Plan or Precinct.<br>
'.(!empty($data['muddisclaimer'])?'<sup>3</sup> '.profilef::hte($data['muddisclaimer']).'<br>':'').'<br>
Please refer to the local planning scheme for definitions of administrative definitions (e.g. Gross Floor Area, Plot Ratio, Site Cover... etc.).<br><br>
We further note the following acronyms may be used throughout this website: GFA (Gross Floor Area), MCU (Material Change Of Use), SQM (Square Metres).
'.($simple?'</td></tr></table>':'</div>').'
';

		return $ht;
	}

	public function profilegetresults_new($searchid,$simple=false) {
		$app = JFactory::getApplication();
		$searchid=intval($searchid);
		$search=profilef::profilegetsearch($searchid);
		$data=profilef::profilegetdata($search['state'],$search['council'],$search['scheme'],$search['zone'],$search['overlays'],$search['plan']);
		/*$ht='';
		if(!$simple) {
			$ht.='<div class="ptheader">
	<div class="ptlogo">
		<div class="pti">';
		} else {
			$ht.='<table cellspacing="0" width="100%" cellpadding="6" border="0">
	<tr align="left" valign="top">
		<td rowspan="2">';
		}
		$ht.='<img src="'.JURI::base().'templates/'.$app->getTemplate().'/images/report-propertease-logo.gif" alt="Propertease">';
		if(!$simple) {
			$ht.='</div>
	</div>
	<div class="pthinfo">
		<div class="pthaddy">
			<div class="pti">
				<a href="http://www.propertease.com.au/">www.PropertEASE.com.au</a><br>
				<a href="mailto:info@propertease.com.au">info@PropertEASE.com.au</a>
			</div>
		</div>
		<div class="pthinfot">';
		} else {
			$ht.='</td>
		<td align="right">
			www.PropertEASE.com.au<br>
			info@PropertEASE.com.au
		</td>
	</tr>
	<tr align="left" valign="top">
		<td>';
		}
		$ht.='<table '.($simple?'cellspacing="0" cellpadding="6" border="0"':'').'>
				<tr'.($simple?' align="left" valign="top"':'').'>
					<th'.($simple?' align="right"':'').'>Report for:</th>
					<td>'.(empty($search['by'])?'N/A':(profilef::hte($search['by']))).'</td>
				</tr>
				<tr'.($simple?' align="left" valign="top"':'').'>
					<th'.($simple?' align="right"':'').'>Date:</th>
					<td>'.(empty($search['date'])?'N/A':(profilef::hte($search['date']))).'</td>
				</tr>
				<tr'.($simple?' align="left" valign="top"':'').'>
					<th'.($simple?' align="right"':'').'>Reference:</th>
					<td>'.(empty($search['reference'])?'N/A':(profilef::hte($search['reference']))).'</td>
				</tr>
			</table>';
		if(!$simple) {
			$ht.='</div>
	</div>
</div>';
		} else {
			$ht.='</td>
	</tr>
	<tr>
		<td bgcolor="#7AB700" colspan="2">&nbsp;</td>
	</tr>
</table>';
		}


		$ht.='<table '.($simple?'cellspacing="0" width="100%" cellpadding="6" border="1" bordercolor="#B58813"':'class="ptresults"').'>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Local Council</th>
    <td colspan="2">'.(empty($data['council'])?'N/A':(profilef::hte($data['council']))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Planning Scheme </th>
    <td colspan="2">'.(empty($data['scheme'])?'N/A':(profilef::hte($data['scheme']))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Zoning</th>
    <td colspan="2">'.(empty($data['zone'])?'N/A':(profilef::hte($data['zone']))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Overlays</th>
    <td colspan="2">';


		$overlaynames=array();
		foreach($data['overlays'] as $overlay) {
			$overlaynames[]=$overlay[0];
		}
		
		$ht.=profilef::hte((count($overlaynames)?implode(', ',$overlaynames):'N/A')).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Neighbourhood Plan <sup>1</sup> + Precinct</th>
    <td colspan="2">'.(empty($data['precinct'])?'':(profilef::hte($data['precinct']))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':' class="ptresheader"').'>
    <th colspan="3"'.($simple?' bgcolor="#F4E8AE"':'').'>RECONFIGURATION OF A LOT (e.g. Subdivision)</th>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Minimum Lot Size and Frontage Required'.($data['minfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['min'])?(is_null($data['minfrontage'])?'N/A':$data['minfrontage'].'m frontage'):(is_null($data['minfrontage'])?$data['min'].'sqm':$data['min'].'sqm and '.$data['minfrontage'].'m frontage')).'</td>
    <td'.(!$simple?(empty($data['lotinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['lotinfo'])?$data['lotinfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':' class="ptresheader"').'>
    <th colspan="3"'.($simple?' bgcolor="#F4E8AE"':'').'>MULTIPLE DWELLING ('.(!empty($data['muddefinition'])?profilef::hte($data['muddefinition']).') '.(!empty($data['muddisclaimer'])?'<sup>3</sup>':''):'Any multiple residential development)').'</th>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Minimum Lot Size and Frontage Required for unit development'.($data['minmultiplefromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['minmultiple'])?(is_null($data['minmultiplefrontage'])?'N/A':$data['minmultiplefrontage'].'m frontage'):(is_null($data['minmultiplefrontage'])?$data['minmultiple'].'sqm':$data['minmultiple'].'sqm and '.$data['minmultiplefrontage'].'m frontage')).'</td>
    <td'.(!$simple?(empty($data['lotmultipleinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['lotmultipleinfo'])?$data['lotmultipleinfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Height Prescribed'.($data['maxheightfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxstorey'])?(is_null($data['maxheight'])?'N/A':$data['maxheight'].'m above Natural Ground Level'):(is_null($data['maxheight'])?$data['maxstorey'].' storeys':$data['maxstorey'].' storeys and '.$data['maxheight'].'m above Natural Ground Level')).'</td>
    <td'.(!$simple?(empty($data['heightinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['heightinfo'])?$data['heightinfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Gross Floor Area/Plot Ratio Prescribed'.($data['maxgfafromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxgfa'])?'N/A':$data['maxgfa'].'% of the total site area').'</td>
    <td'.(!$simple?(empty($data['gfainfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['gfainfo'])?$data['gfainfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Site Cover Prescribed'.($data['maxcoverfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxcover'])?'N/A':$data['maxcover'].'% of the total site area').'</td>
    <td'.(!$simple?(empty($data['coverinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['coverinfo'])?$data['coverinfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Density Prescribed'.($data['maxdensityfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxdensity'])?'N/A':$data['maxdensity'].' per hectare').'</td>
    <td'.(!$simple?(empty($data['densityinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['densityinfo'])?$data['densityinfo']:'N/A'))).'</td>
  </tr>';
  		if(count($data['overlays'])>0) {
			$ht.='
  <tr'.($simple?' align="left" valign="top"':' class="ptresheader"').'>
    <th colspan="3"'.($simple?' bgcolor="#F4E8AE"':'').'>ADDITIONAL OVERLAY INFORMATION </th>
  </tr>';
  			foreach($data['overlays'] as $overlay) {
				$ht.='
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>'.profilef::hte($overlay[0]).'</th>
    <td colspan="2">'.nl2br(profilef::hte($overlay[1])).'</td>
  </tr>';
  			}
  		}
		if(!empty($data['mudsetback'])||!empty($data['housesetback'])||!empty($data['smalllotsetback'])||!empty($data['secondarysetback'])) {
			$ht.='
  <tr'.($simple?' align="left" valign="top"':' class="ptresheader"').'>
    <th colspan="3"'.($simple?' bgcolor="#F4E8AE"':'').'>GENERAL INFORMATION (Note, any information following are general provisions that may be altered by the zoning, neighbourhood plan and/or overlays of a site)</th>
  </tr>';
			if(!empty($data['mudsetback'])) {
				$ht.='
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Prescribed setbacks for a Multiple Unit Dwelling'.($data['mudsetbackfromplan']?' <sup>2</sup>':'').'</th>
    <td colspan="2">'.nl2br(profilef::hte($data['mudsetback'])).'</td>
  </tr>';
			}
			if(!empty($data['housesetback'])) {
				$ht.='
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Prescribed setbacks for a House'.($data['housesetbackfromplan']?' <sup>2</sup>':'').'</th>
    <td colspan="2">'.nl2br(profilef::hte($data['housesetback'])).'</td>
  </tr>';
			}
			if(!empty($data['smalllotsetback'])) {
				$ht.='
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Prescribed setbacks for Small Lot House (450sqm or less)'.($data['smalllotsetbackfromplan']?' <sup>2</sup>':'').'</th>
    <td colspan="2">'.nl2br(profilef::hte($data['smalllotsetback'])).'</td>
  </tr>';
			}
			if(!empty($data['secondarysetback'])) {
				$ht.='
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Prescribed secondary Dwelling Provisions'.($data['secondarysetbackfromplan']?' <sup>2</sup>':'').'</th>
    <td colspan="2">'.nl2br(profilef::hte($data['secondarysetback'])).'</td>
  </tr>';
			}
		}
		$ht.='
</table>
'.($simple?'<table width="100%" border="0" cellpadding="6" cellspacing="0" bgcolor="#008CCC"><tr><td color="#FFFFFF">':'<div class="ptextra">').'
<sup>1</sup> Neighbourhood Plan can refer to a Local Plan, Precinct, Local Area... etc. dependent on the planning scheme.<br>
<sup>2</sup> Provision taken from a Local or Neighbourhood Plan or Precinct.<br>
'.(!empty($data['muddisclaimer'])?'<sup>3</sup> '.profilef::hte($data['muddisclaimer']).'<br>':'').'<br>
Please refer to the local planning scheme for definitions of administrative definitions (e.g. Gross Floor Area, Plot Ratio, Site Cover... etc.).<br><br>
We further note the following acronyms may be used throughout this website: GFA (Gross Floor Area), MCU (Material Change Of Use), SQM (Square Metres).
'.($simple?'</td></tr></table>':'</div>').'
';
*/
		
		$newreport = '<div class="row">
		<h3 class="orange-arrow-down"><span class="light">Below, you can </span>review<span class="light">,</span> <i class="green">downlod</i> <span class="light">your</span> last report</h3>
		<div class="col-sm-12">
		<ul class="nav nav-tabs">
		<li class="active"><a href="#overview" data-toggle="tab">Overview </a></li>
		<li class=""><a href="#reconfiguration" data-toggle="tab">Reconfiguration of a Lot</a></li>
		<li class=""><a href="#multiple" data-toggle="tab">Multiple Dwelling </a></li>
		<li class=""><a href="#additional" data-toggle="tab">Additional Overlay Information</a></li>
		<li class=""><a href="#general" data-toggle="tab">General Information</a></li>
		</ul>
		<div class="tab-content">
		<div id="overview" class="tab-pane fade active in">
		<ul>
		<li><a href="#"><span class="blk">Local Council</span> - '.(empty($data['council'])?'N/A':(profilef::hte($data['council']))).'</a></li>
		<li><a href="#"><span class="blk">Planning Scheme</span> - '.(empty($data['scheme'])?'N/A':(profilef::hte($data['scheme']))).'</a></li>
		<li><a href="#"><span class="blk">Zoning </span> - '.(empty($data['zone'])?'N/A':(profilef::hte($data['zone']))).'</a></li>
		<li><a href="#"><span class="blk">Overlays</span> - '; 
		$overlaynames=array();
		foreach($data['overlays'] as $overlay) {
			$overlaynames[]=$overlay[0];
		}
		$newreport.=profilef::hte((count($overlaynames)?implode(', ',$overlaynames):'N/A')).'</a></li>
		<li><a href="#"><span class="blk">Neighbourhood Plan + Precinct </span> - '.(empty($data['precinct'])?'':(profilef::hte($data['precinct']))).' </a></li>
		</ul>
		<div class="info">
		<p>Report for:<span class="blk"> '.(empty($search['by'])?'N/A':(profilef::hte($search['by']))).'</span></p>
		<p>Date: <span class="blk">'.(empty($search['date'])?'N/A':(profilef::hte($search['date']))).'</span></p>
		<p>Reference: <span class="blk">'.(empty($search['reference'])?'N/A':(profilef::hte($search['reference']))).'</span></p>
		<a class="blk" id="getpdf" href="javascript:void(0);">Download .pdf</a></div>
		</div>
		<div id="reconfiguration" class="tab-pane fade"> <table class="table table-striped"><tr><th>Minimum Lot Size and Frontage Required'.($data['minfromplan']?' <sup>2</sup>':'').'</th></tr>
    <tr><td>'.(is_null($data['min'])?(is_null($data['minfrontage'])?'N/A':$data['minfrontage'].'m frontage'):(is_null($data['minfrontage'])?$data['min'].'sqm':$data['min'].'sqm and '.$data['minfrontage'].'m frontage')).'</td></tr>
    <tr><td'.(!$simple?(empty($data['lotinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['lotinfo'])?$data['lotinfo']:'N/A'))).'</td></tr></table></div>
		<div id="multiple" class="tab-pane fade"><table class="table table-striped"><tr'.($simple?' align="left" valign="top"':' class="ptresheader"').'>
    <th colspan="3"'.($simple?' bgcolor="#F4E8AE"':'').'>MULTIPLE DWELLING ('.(!empty($data['muddefinition'])?profilef::hte($data['muddefinition']).') '.(!empty($data['muddisclaimer'])?'<sup>3</sup>':''):'Any multiple residential development)').'</th>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Minimum Lot Size and Frontage Required for unit development'.($data['minmultiplefromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['minmultiple'])?(is_null($data['minmultiplefrontage'])?'N/A':$data['minmultiplefrontage'].'m frontage'):(is_null($data['minmultiplefrontage'])?$data['minmultiple'].'sqm':$data['minmultiple'].'sqm and '.$data['minmultiplefrontage'].'m frontage')).'</td>
    <td'.(!$simple?(empty($data['lotmultipleinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['lotmultipleinfo'])?$data['lotmultipleinfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Height Prescribed'.($data['maxheightfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxstorey'])?(is_null($data['maxheight'])?'N/A':$data['maxheight'].'m above Natural Ground Level'):(is_null($data['maxheight'])?$data['maxstorey'].' storeys':$data['maxstorey'].' storeys and '.$data['maxheight'].'m above Natural Ground Level')).'</td>
    <td'.(!$simple?(empty($data['heightinfo'])?' class="ptdeadinfo"':''):'').'>'.str_replace("\n","<br>",nl2br(profilef::hte((!empty($data['heightinfo'])?$data['heightinfo']:'N/A')))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Gross Floor Area/Plot Ratio Prescribed'.($data['maxgfafromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxgfa'])?'N/A':$data['maxgfa'].'% of the total site area').'</td>
    <td'.(!$simple?(empty($data['gfainfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['gfainfo'])?$data['gfainfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Site Cover Prescribed'.($data['maxcoverfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxcover'])?'N/A':$data['maxcover'].'% of the total site area').'</td>
    <td'.(!$simple?(empty($data['coverinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['coverinfo'])?$data['coverinfo']:'N/A'))).'</td>
  </tr>
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>Maximum Density Prescribed'.($data['maxdensityfromplan']?' <sup>2</sup>':'').'</th>
    <td>'.(is_null($data['maxdensity'])?'N/A':$data['maxdensity'].' per hectare').'</td>
    <td'.(!$simple?(empty($data['densityinfo'])?' class="ptdeadinfo"':''):'').'>'.nl2br(profilef::hte((!empty($data['densityinfo'])?$data['densityinfo']:'N/A'))).'</td>
  </tr></table></div>
		<div id="additional" class="tab-pane fade">'; foreach($data['overlays'] as $overlay) {
				$newreport.='<table class="table table-striped">
  <tr'.($simple?' align="left" valign="top"':'').'>
    <th>'.profilef::hte($overlay[0]).'</th>
    <td colspan="2">'.nl2br(profilef::hte($overlay[1])).'</td>
  </tr></table>';
  			}
  		$newreport.='</div>
		<div id="general" class="tab-pane fade"><sup>1</sup> Neighbourhood Plan can refer to a Local Plan, Precinct, Local Area... etc. dependent on the planning scheme.<br>
<sup>2</sup> Provision taken from a Local or Neighbourhood Plan or Precinct.<br>
'.(!empty($data['muddisclaimer'])?'<sup>3</sup> '.profilef::hte($data['muddisclaimer']).'<br>':'').'<br>
Please refer to the local planning scheme for definitions of administrative definitions (e.g. Gross Floor Area, Plot Ratio, Site Cover... etc.).<br><br>
We further note the following acronyms may be used throughout this website: GFA (Gross Floor Area), MCU (Material Change Of Use), SQM (Square Metres).</div>
		</div>
		</div>
		</div>';

		//return $newreport.$ht;
		return $newreport;
	}

	public function profilegetdata($stateid=0,$councilid=0,$schemeid=0,$zoneid=0,$overlayids=array(),$precinctid=0) {
		$stateid=intval($stateid);
		$councilid=intval($councilid);
		$schemeid=intval($schemeid);
		$zoneid=intval($zoneid);
		$overlayidsnew=array();
		if(!is_array($overlayids)) {
			$overlayids=explode(',',$overlayids);
		}
		$hz=false;
		foreach($overlayids as $overlayid) {
			$overlayid=intval($overlayid);
			if($overlayid==0) {
				if(!$hz) {
					$hz=true;
				}
			}
			$overlayidsnew[]=$overlayid;
		}
		if($hz) {
			$overlayidsnew=array();
		}
		unset($overlayids);
		$precinctid=intval($precinctid);
		
		$data=array();
		if($councilid>0) {
			$data['council']=profilef::profilegetname($councilid);
		}
		if($schemeid>0) {
			$data['scheme']=profilef::profilegetname($schemeid);
		}
		$data['muddefinition']='';
		$data['muddisclaimer']='';
		$md=profilef::profilegetmuddefinition($stateid);
		if(!empty($md[0])) {
			$data['muddefinition']=$md[0];
			$data['muddisclaimer']=$md[1];
		}
		$md=profilef::profilegetmuddefinition($councilid);
		if(!empty($md[0])) {
			$data['muddefinition']=$md[0];
			$data['muddisclaimer']=$md[1];
		}
		$md=profilef::profilegetmuddefinition($schemeid);
		if(!empty($md[0])) {
			$data['muddefinition']=$md[0];
			$data['muddisclaimer']=$md[1];
		}
		if(empty($data['muddefinition'])) {
			$data['muddefinition']='';
			$data['muddisclaimer']='';
		}
		
		
		$numfields=array('maxcover','maxdensity','maxstorey','min','minfrontage','minmultiple','minmultiplefrontage','maxheight','maxgfa');
		
		$generaltextfields=array('mudsetback','housesetback','smalllotsetback','secondarysetback');
		
		foreach($generaltextfields as $textfield) {
			$overlaydata['field_'.$textfield]='';
			$data[$textfield]='';
			$data[$textfield.'fromzone']=false;
			$data[$textfield.'fromplan']=false;
			$data[$textfield.'fromoverlay']=false;
		}
		foreach($numfields as $fieldname) {
			$overlaydata['field_'.$fieldname]=NULL;
			$data[$fieldname]=NULL;
		}
		$overlaydata['field_heightinfo']='';
		$overlaydata['field_lotinfo']='';
		$overlaydata['field_lotmultipleinfo']='';
		$overlaydata['field_gfainfo']='';
		$overlaydata['field_densityinfo']='';
		$overlaydata['field_coverinfo']='';
		$overlaydata['field_storeyinfo']='';
		$overlaydata['field_frontagelotinfo']='';
		$overlaydata['field_frontagelotmultipleinfo']='';
		$data['heightinfo']='';
		$data['lotinfo']='';
		$data['lotmultipleinfo']='';
		$data['gfainfo']='';
		$data['densityinfo']='';
		$data['coverinfo']='';
		$data['maxheightfromzone']=false;
		$data['minfromzone']=false;
		$data['minmultiplefromzone']=false;
		$data['maxgfafromzone']=false;
		$data['maxdensityfromzone']=false;
		$data['maxcoverfromzone']=false;
		
		
		if($zoneid>0) {
			$zonefields=profilef::profilegetdetailfields($zoneid);
			$data['zone']=$zonefields['field_name'];
			
			profilef::profilegroupoverride($data,$zonefields,array('maxheight','maxstorey'),'heightinfo','maxheightfromzone');
			profilef::profilegroupoverride($data,$zonefields,array('min','minfrontage'),'lotinfo','minfromzone');
			profilef::profilegroupoverride($data,$zonefields,array('minmultiple','minmultiplefrontage'),'lotmultipleinfo','minmultiplefromzone');
			profilef::profilegroupoverride($data,$zonefields,'maxgfa','gfainfo','maxgfafromzone');
			profilef::profilegroupoverride($data,$zonefields,'maxdensity','densityinfo','maxdensityfromzone');
			profilef::profilegroupoverride($data,$zonefields,'maxcover','coverinfo','maxcoverfromzone');
			foreach($generaltextfields as $textfield) {
				if(isset($zonefields['field_'.$textfield])) {
					$text=trim($zonefields['field_'.$textfield]);
					if(!empty($text)) {
						$data[$textfield]=$text;
						$data[$textfield.'fromzone']=true;
					}
				}
			}
		}
		
		$data['maxheightfromplan']=false;
		$data['minfromplan']=false;
		$data['minmultiplefromplan']=false;
		$data['maxgfafromplan']=false;
		$data['maxdensityfromplan']=false;
		$data['maxcoverfromplan']=false;
		
		$data['maxheightfromoverlay']=false;
		$data['minfromoverlay']=false;
		$data['minmultiplefromoverlay']=false;
		$data['maxgfafromoverlay']=false;
		$data['maxdensityfromoverlay']=false;
		$data['maxcoverfromoverlay']=false;
		
		if($precinctid>0) {
			$precinctfields=profilef::profilegetdetailfields($precinctid);
			$data['precinct']=$precinctfields['field_name'];
			profilef::profilegroupoverride($data,$precinctfields,array('maxheight','maxstorey'),'heightinfo','maxheightfromplan','maxheightfromzone');
			profilef::profilegroupoverride($data,$precinctfields,array('min','minfrontage'),'lotinfo','minfromplan','minfromzone');
			profilef::profilegroupoverride($data,$precinctfields,array('minmultiple','minmultiplefrontage'),'lotmultipleinfo','minmultiplefromplan','minmultiplefromzone');
			profilef::profilegroupoverride($data,$precinctfields,'maxgfa','gfainfo','maxgfafromplan','maxgfafromzone');
			profilef::profilegroupoverride($data,$precinctfields,'maxdensity','densityinfo','maxdensityfromplan','maxdensityfromzone');
			profilef::profilegroupoverride($data,$precinctfields,'maxcover','coverinfo','maxcoverfromplan','maxcoverfromzone');
			foreach($generaltextfields as $textfield) {
				if(isset($precinctfields['field_'.$textfield])) {
					$text=trim($precinctfields['field_'.$textfield]);
					if(!empty($text)) {
						$data[$textfield]=$text;
						$data[$textfield.'fromzone']=false;
						$data[$textfield.'fromplan']=true;
					}
				}
			}
		}
		$data['overlays']=array();
		$orphantext=array();
		if(!empty($overlayidsnew)) {
			$mergekeys=array(
				array('heightinfo','storeyinfo'),
				array('lotinfo','frontagelotinfo'),
				array('lotmultipleinfo','frontagelotmultipleinfo')
			);
			foreach($overlayidsnew as $overlayid) {
				$overlayfields=profilef::profilegetdetailfields($overlayid);
				$overlayname=$overlayfields['field_name'];
				$data['overlays'][]=array($overlayname,$overlayfields['field_overlayinfo']);
				
				foreach($mergekeys as $mergekeyspair) {
					$overlayfields['field_'.$mergekeyspair[1]]=$overlayfields['field_'.$mergekeyspair[0]];
				}
				
				profilef::profilegroupoverlayoverride($overlaydata,$overlayfields,$orphantext,'maxheight',true,'heightinfo');//,'maxstorey'
				profilef::profilegroupoverlayoverride($overlaydata,$overlayfields,$orphantext,'min',false,'lotinfo');//,'minfrontage'
				profilef::profilegroupoverlayoverride($overlaydata,$overlayfields,$orphantext,'minmultiple',false,'lotmultipleinfo');//,'minmultiplefrontage'
				
				profilef::profilegroupoverlayoverride($overlaydata,$overlayfields,$orphantext,'maxstorey',true,'storeyinfo');
				profilef::profilegroupoverlayoverride($overlaydata,$overlayfields,$orphantext,'minfrontage',false,'frontagelotinfo');
				profilef::profilegroupoverlayoverride($overlaydata,$overlayfields,$orphantext,'minmultiplefrontage',false,'frontagelotmultipleinfo');
				
				profilef::profilegroupoverlayoverride($overlaydata,$overlayfields,$orphantext,'maxgfa',true,'gfainfo');
				profilef::profilegroupoverlayoverride($overlaydata,$overlayfields,$orphantext,'maxdensity',true,'densityinfo');
				profilef::profilegroupoverlayoverride($overlaydata,$overlayfields,$orphantext,'maxcover',true,'coverinfo');
				
				foreach($mergekeys as $mergekeyspair) {
					$test=trim($overlaydata['field_'.$mergekeyspair[0]]);
					if(empty($test)) {
						$test=trim($overlaydata['field_'.$mergekeyspair[1]]);
						if(!empty($test)) {
							$overlaydata['field_'.$mergekeyspair[0]]=$test;
						}
					}
					unset($overlaydata['field_'.$mergekeyspair[1]]);
				}
				
				foreach($generaltextfields as $textfield) {
					if(isset($overlayfields['field_'.$textfield])) {
						$text=trim($overlayfields['field_'.$textfield]);
						if(!empty($text)) {
							$texttest=trim($overlaydata['field_'.$textfield].'');
							if(!empty($texttest)) {
								$overlaydata['field_'.$textfield].="\n\n".$text;
							} else {
								$overlaydata['field_'.$textfield]=$text;
							}
						}
					}
				}
				//$primarynumfield,$ismax=false,$textfields=array(),$numfields=array(),$setfields=array(),$clearfields=array()
				/*profilef::profilegroupoverlayoverride($data,$overlayfields,'maxheight',true,'heightinfo','maxstorey','maxheightfromoverlay',array('maxheightfromplan','maxheightfromzone'));
				profilef::profilegroupoverlayoverride($data,$overlayfields,'min',false,'lotinfo','minfrontage','minfromoverlay',array('minfromplan','minfromzone'));
				profilef::profilegroupoverlayoverride($data,$overlayfields,'minmultiple',false,'lotmultipleinfo','minmultiplefrontage','minmultiplefromoverlay',array('minmultiplefromplan','minmultiplefromzone'));
				profilef::profilegroupoverlayoverride($data,$overlayfields,'maxgfa',true,'gfainfo','maxgfafromoverlay',array('maxgfafromplan','maxgfafromzone'));
				profilef::profilegroupoverlayoverride($data,$overlayfields,'maxdensity',true,'densityinfo','maxdensityfromoverlay',array('maxdensityfromplan','maxdensityfromzone'));
				profilef::profilegroupoverlayoverride($data,$overlayfields,'maxcover',true,'coverinfo','maxcoverfromoverlay',array('maxcoverfromplan','maxcoverfromzone'));
				foreach($generaltextfields as $textfield) {
					if(isset($overlayfields['field_'.$textfield])) {
						$text=trim($overlayfields['field_'.$textfield]);
						if(!empty($text)) {
							$data[$textfield]=$text;
							$data[$textfield.'fromzone']=false;
							$data[$textfield.'fromplan']=false;
							$data[$textfield.'fromoverlay']=true;
						}
					}
				}*/
			}
			foreach($mergekeys as $mergekeyspair) {
				if(isset($orphantext[$mergekeyspair[1]])) {
					$test=trim($orphantext[$mergekeyspair[0]]);
					if(empty($test)) {
						$test=trim($orphantext[$mergekeyspair[1]]);
						if(!empty($test)) {
							$orphantext[$mergekeyspair[0]]=$test;
						}
					}
					unset($orphantext[$mergekeyspair[1]]);
				}
			}
			$basictextf=array('heightinfo','lotinfo','lotmultipleinfo','gfainfo','densityinfo','coverinfo');
			foreach($basictextf as $textfield) {
				if(isset($orphantext[$textfield])) {
					$test=trim($overlaydata['field_'.$textfield]);
					if(empty($test)) {
						$overlaydata['field_'.$textfield]=$orphantext[$textfield];
					}
				}
			}
			//foreach
			profilef::profilegroupoverride($data,$overlaydata,array('maxheight','maxstorey'),'heightinfo','maxheightfromoverlay',array('maxheightfromplan','maxheightfromzone'));
			profilef::profilegroupoverride($data,$overlaydata,array('min','minfrontage'),'lotinfo','minfromoverlay',array('minfromplan','minfromzone'));
			profilef::profilegroupoverride($data,$overlaydata,array('minmultiple','minmultiplefrontage'),'lotmultipleinfo','minmultiplefromoverlay',array('minmultiplefromplan','minmultiplefromzone'));
			profilef::profilegroupoverride($data,$overlaydata,'maxgfa','gfainfo','maxgfafromoverlay',array('maxgfafromplan','maxgfafromzone'));
			profilef::profilegroupoverride($data,$overlaydata,'maxdensity','densityinfo','maxdensityfromoverlay',array('maxdensityfromplan','maxdensityfromzone'));
			profilef::profilegroupoverride($data,$overlaydata,'maxcover','coverinfo','maxcoverfromoverlay',array('maxcoverfromplan','maxcoverfromzone'));
			foreach($generaltextfields as $textfield) {
				if(isset($overlaydata['field_'.$textfield])) {
					$text=trim($overlaydata['field_'.$textfield]);
					if(!empty($text)) {
						$data[$textfield]=$text;
						$data[$textfield.'fromzone']=false;
						$data[$textfield.'fromplan']=false;
						$data[$textfield.'fromoverlay']=true;
					}
				}
			}
		}
		//echo '<!-- '.str_replace('-->','- - >',print_r($overlayidsnew,true).print_r($overlayfields,true)).$overlayid.' -->';
		return $data;
	}
}
?>