<?php

//
// Copyright (C) 2006-2007 Next Generation CMS (http://ngcms.ru/)
// Name: multimaster.php
// Description: multidomain mastering
// Author: Vitaly A Ponomarev, vp7@mail.ru
//

function multi_multisites(){
 global $config, $siteDomainName, $multiDomainName, $multiconfig, $multimaster;

 $siteDomainName  = '';
 $multiDomainName = '';

 // ����������� �������������� ������������
 @include root.'conf/multiconfig.php';
 if (!is_array($multiconfig)) { return; }

 // ���� �� ������ ������������ - �� ������������ ������������
 if ( !$multimaster || (!is_array($multiconfig[$multimaster])) ||
     (!$multiconfig[$multimaster]['active']) || (!is_array($multiconfig[$multimaster]['domains']))) { return; }
 // ������ ��� ���������� break'��
 do {
	// �������� �������
	foreach($multiconfig[$multimaster]['domains'] as $mdom){
		if (($_SERVER['SERVER_NAME'] == $mdom)||($_SERVER['HTTP_HOST'] == $mdom)) {
			// ������ ����
			$siteDomainName = $mdom;
			$multiDomainName = $multimaster;
			break;
		}
	}

	// �������� ��������� ������� �������
	foreach ($multiconfig as $mname => $mrec) {
		if (!is_array($mrec['domains'])) { continue; }
		foreach ($mrec['domains'] as $mdom){
 			if (($_SERVER['SERVER_NAME'] == $mdom)||($_SERVER['HTTP_HOST'] == $mdom)) {
 				// ������ ����
 				$siteDomainName = $mdom;
 				$multiDomainName = $mname;
 				break;
			}
		}
		if ($siteDomainName) { break; }
	}
 } while (0);

 // ���� ��� �� ������ ����������, �� �������� ������ ����� � �������
 if (!$siteDomainName) { $siteDomainName = $multiconfig[$multimaster]['domains'][0]; }

 // ���� ����������� ������ - ������ ����
 if ($multiDomainName && ($multiDomainName != $multimaster)) {
 }

 $siteDomainName = trim($siteDomainName);

}


function multi_multidomains() {
 global $config, $siteDomainName, $multiDomainName, $multimaster, $multiconfig, $multimaster;

 $newdomain = '';

 // ����������� �������� ������� mydomains
 if ($config['mydomains']) {
	$domlist = explode("\n",$config['mydomains']);
 	if (!is_array($domlist)) return 0;
 	foreach ($domlist as $domain) {
 		$domain = trim($domain);
 		if (($_SERVER['SERVER_NAME'] == $domain)||($_SERVER['HTTP_HOST'] == $domain)) {
 			// We found it. First - get default domain
 			$newdomain = $domain;
 			break;
 		}
    }
 }

 // ���� �� ������� �� ������ ����������, �� ����������� ����� ������ ����
 if (!$newdomain) {
 	// ���� ������ ����� � mydomains, �� �������� ������. ����� - �������� ���� ��������� � �����������
 	if (is_array($domlist)) { $newdomain = $domlist[0]; }
 	else { $newdomain = $siteDomainName; }
 }

 //
 // ������������ ������ ����� {domain}, {domainID} � ������-�����
 //
 $newdomain = trim($newdomain);
 $newdomainid = trim($multiDomainName);
 if ($newdomain) {
	 foreach (array('home_url', 'admin_url', 'avatars_url', 'photos_url', 'images_url', 'files_url', 'avatars_dir', 'photos_dir', 'images_dir', 'files_dir') as $vn) {
		$config[$vn] = str_replace('{domain}',$newdomain,$config[$vn]);
 		$config[$vn] = str_replace('{domainid}', $newdomainid, $config[$vn]);
 	}
 }
}
