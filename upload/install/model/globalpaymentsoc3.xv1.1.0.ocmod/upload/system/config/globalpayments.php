<?php 
$_['globalpayments_setting'] = array(
	'service' => array(
		'hpp' => array(
			'production' => array(
				'url' => 'https://pay.realexpayments.com/pay'
			),
			'sandbox' => array(
				'url' => 'https://pay.sandbox.realexpayments.com/pay'
			)
		),
		'api' => array(
			'production' => array(
				'url' => 'https://api.realexpayments.com/epage-remote.cgi'
			),
			'sandbox' => array(
				'url' => 'https://api.sandbox.realexpayments.com/epage-remote.cgi'
			)
		)
	),
	'order_status' => array(
		'success_settled' => array(
			'code' => 'success_settled',
			'name' => 'text_success_settled_status',
			'id' => 5
		),
		'success_unsettled' => array(
			'code' => 'success_unsettled',
			'name' => 'text_success_unsettled_status',
			'id' => 5
		),
		'decline' => array(
			'code' => 'decline',
			'name' => 'text_decline_status',
			'id' => 7
		),
		'failed' => array(
			'code' => 'failed',
			'name' => 'text_failed_status',
			'id' => 10
		)
	),
	'checkout' => array(
		'api' => array(
			'form_align' => 'right',
			'form_size' => 'large',
			'secure_status' => true,
			'secure_2_scenario' => array(
				'authentication_could_not_be_performed' => 0,
				'authentication_failed' => 0, 
				'authentication_issuer_rejected' => 0,
				'authentication_attempted_but_not_successful' => 1
			),
			'secure_1_scenario' => array(
				'cardholder_not_enrolled' => 0,
				'unable_to_verify_enrolment' => 0, 
				'invalid_response_from_enrolment_server' => 0,
				'enrolled_but_invalid_response_from_acs' => 0,
				'authentication_attempt_acknowledge' => 1,
				'incorrect_password_entered' => 0, 
				'authentication_unavailable' => 0,
				'invalid_response_from_acs' => 0
			)
		)
	),
	'form_align' => array(
		'left' => array(
			'code' => 'left',
			'name' => 'text_align_left'
		),
		'center' => array(
			'code' => 'center',
			'name' => 'text_align_center'
		),
		'right' => array(
			'code' => 'right',
			'name' => 'text_align_right'
		)
	),
	'form_size' => array(
		'small' => array(
			'code' => 'small',
			'name' => 'text_small'
		),
		'medium' => array(
			'code' => 'medium',
			'name' => 'text_medium'
		),
		'large' => array(
			'code' => 'large',
			'name' => 'text_large'
		),
		'responsive' => array(
			'code' => 'responsive',
			'name' => 'text_responsive'
		)
	),
	'form_width' => array(
		'small' => '350px',
		'medium' => '400px',
		'large' => '500px',
		'responsive' => ''
	),
	'secure_2_scenario' => array(
		'authentication_could_not_be_performed' => array(
			'code' => 'authentication_could_not_be_performed',
			'name' => 'text_3ds2_authentication_could_not_be_performed',
			'error' => 'error_3ds2_authentication_could_not_be_performed',
			'recommended' => 0
		),
		'authentication_failed' => array(
			'code' => 'authentication_failed',
			'name' => 'text_3ds2_authentication_failed',
			'error' => 'error_3ds2_authentication_failed',
			'recommended' => 0
		),
		'authentication_issuer_rejected' => array(
			'code' => 'authentication_issuer_rejected',
			'name' => 'text_3ds2_authentication_issuer_rejected',
			'error' => 'error_3ds2_authentication_issuer_rejected',
			'recommended' => 0
		),
		'authentication_attempted_but_not_successful' => array(
			'code' => 'authentication_attempted_but_not_successful',
			'name' => 'text_3ds2_authentication_attempted_but_not_successful',
			'error' => 'error_3ds2_authentication_attempted_but_not_successful',
			'recommended' => 1
		)
	),
	'secure_1_scenario' => array(
		'cardholder_not_enrolled' => array(
			'code' => 'cardholder_not_enrolled',
			'name' => 'text_3ds1_cardholder_not_enrolled',
			'error' => 'error_3ds1_cardholder_not_enrolled',
			'recommended' => 0
		),
		'unable_to_verify_enrolment' => array(
			'code' => 'unable_to_verify_enrolment',
			'name' => 'text_3ds1_unable_to_verify_enrolment',
			'error' => 'error_3ds1_unable_to_verify_enrolment',
			'recommended' => 0
		),
		'invalid_response_from_enrolment_server' => array(
			'code' => 'invalid_response_from_enrolment_server',
			'name' => 'text_3ds1_invalid_response_from_enrolment_server',
			'error' => 'error_3ds1_invalid_response_from_enrolment_server',
			'recommended' => 0
		),
		'enrolled_but_invalid_response_from_acs' => array(
			'code' => 'enrolled_but_invalid_response_from_acs',
			'name' => 'text_3ds1_enrolled_but_invalid_response_from_acs',
			'error' => 'error_3ds1_enrolled_but_invalid_response_from_acs',
			'recommended' => 0
		),
		'authentication_attempt_acknowledge' => array(
			'code' => 'authentication_attempt_acknowledge',
			'name' => 'text_3ds1_authentication_attempt_acknowledge',
			'error' => 'error_3ds1_authentication_attempt_acknowledge',
			'recommended' => 1
		),
		'incorrect_password_entered' => array(
			'code' => 'incorrect_password_entered',
			'name' => 'text_3ds1_incorrect_password_entered',
			'error' => 'error_3ds1_incorrect_password_entered',
			'recommended' => 0
		),
		'authentication_unavailable' => array(
			'code' => 'authentication_unavailable',
			'name' => 'text_3ds1_authentication_unavailable',
			'error' => 'error_3ds1_authentication_unavailable',
			'recommended' => 0
		),
		'invalid_response_from_acs' => array(
			'code' => 'invalid_response_from_acs',
			'name' => 'text_3ds1_invalid_response_from_acs',
			'error' => 'error_3ds1_invalid_response_from_acs',
			'recommended' => 0
		)
	),
	'country' => array(
		'AFG' => array('country_code' => '004', 'phone_code' => '93'),
		'ALB' => array('country_code' => '008', 'phone_code' => '355'),
		'DZA' => array('country_code' => '012', 'phone_code' => '213'),
		'ASM' => array('country_code' => '016', 'phone_code' => '1'),
		'AND' => array('country_code' => '020', 'phone_code' => '376'),
		'AGO' => array('country_code' => '024', 'phone_code' => '244'),
		'AIA' => array('country_code' => '660', 'phone_code' => '1'),
		'ATA' => array('country_code' => '010', 'phone_code' => ''),
		'ATG' => array('country_code' => '028', 'phone_code' => '1'),
		'ARG' => array('country_code' => '032', 'phone_code' => '54'),
		'ARM' => array('country_code' => '051', 'phone_code' => '374'),
		'ABW' => array('country_code' => '533', 'phone_code' => '297'),
		'AUS' => array('country_code' => '036', 'phone_code' => '61'),
		'AUT' => array('country_code' => '040', 'phone_code' => '43'),
		'AZE' => array('country_code' => '031', 'phone_code' => '994'),
		'BHS' => array('country_code' => '044', 'phone_code' => '1'),
		'BHR' => array('country_code' => '048', 'phone_code' => '973'),
		'BGD' => array('country_code' => '050', 'phone_code' => '880'),
		'BRB' => array('country_code' => '052', 'phone_code' => '1'),
		'BLR' => array('country_code' => '112', 'phone_code' => '375'),
		'BEL' => array('country_code' => '056', 'phone_code' => '32'),
		'BLZ' => array('country_code' => '084', 'phone_code' => '501'),
		'BEN' => array('country_code' => '204', 'phone_code' => '229'),
		'BMU' => array('country_code' => '060', 'phone_code' => '1'),
		'BTN' => array('country_code' => '064', 'phone_code' => '975'),
		'BOL' => array('country_code' => '068', 'phone_code' => '591'),
		'BIH' => array('country_code' => '070', 'phone_code' => '387'),
		'BWA' => array('country_code' => '072', 'phone_code' => '267'),
		'BVT' => array('country_code' => '074', 'phone_code' => ''),
		'BRA' => array('country_code' => '076', 'phone_code' => '55'),
		'IOT' => array('country_code' => '086', 'phone_code' => '246'),
		'BRN' => array('country_code' => '096', 'phone_code' => '673'),
		'BGR' => array('country_code' => '100', 'phone_code' => '359'),
		'BFA' => array('country_code' => '854', 'phone_code' => '226'),
		'BDI' => array('country_code' => '108', 'phone_code' => '257'),
		'KHM' => array('country_code' => '116', 'phone_code' => '855'),
		'CMR' => array('country_code' => '120', 'phone_code' => '237'),
		'CAN' => array('country_code' => '124', 'phone_code' => '1'),
		'CPV' => array('country_code' => '132', 'phone_code' => '238'),
		'CYM' => array('country_code' => '136', 'phone_code' => '1'),
		'CAF' => array('country_code' => '140', 'phone_code' => '236'),
		'TCD' => array('country_code' => '148', 'phone_code' => '235'),
		'CHL' => array('country_code' => '152', 'phone_code' => '56'),
		'CHN' => array('country_code' => '156', 'phone_code' => '86'),
		'CXR' => array('country_code' => '162', 'phone_code' => ''),
		'CCK' => array('country_code' => '166', 'phone_code' => ''),
		'COL' => array('country_code' => '170', 'phone_code' => '57'),
		'COM' => array('country_code' => '174', 'phone_code' => '269'),
		'COG' => array('country_code' => '178', 'phone_code' => '242'),
		'COK' => array('country_code' => '184', 'phone_code' => '682'),
		'CRI' => array('country_code' => '188', 'phone_code' => '506'),
		'CIV' => array('country_code' => '384', 'phone_code' => '225'),
		'HRV' => array('country_code' => '191', 'phone_code' => '385'),
		'CUB' => array('country_code' => '192', 'phone_code' => '53'),
		'CYP' => array('country_code' => '196', 'phone_code' => '357'),
		'CZE' => array('country_code' => '203', 'phone_code' => '420'),
		'DNK' => array('country_code' => '208', 'phone_code' => '45'),
		'DJI' => array('country_code' => '262', 'phone_code' => '253'),
		'DMA' => array('country_code' => '212', 'phone_code' => '1'),
		'DOM' => array('country_code' => '214', 'phone_code' => '1'),
		'TLS' => array('country_code' => '626', 'phone_code' => '670'),
		'ECU' => array('country_code' => '218', 'phone_code' => '593'),
		'EGY' => array('country_code' => '818', 'phone_code' => '20'),
		'SLV' => array('country_code' => '222', 'phone_code' => '503'),
		'GNQ' => array('country_code' => '226', 'phone_code' => '240'),
		'ERI' => array('country_code' => '232', 'phone_code' => '291'),
		'EST' => array('country_code' => '233', 'phone_code' => '372'),
		'ETH' => array('country_code' => '231', 'phone_code' => '251'),
		'FLK' => array('country_code' => '238', 'phone_code' => '500'),
		'FRO' => array('country_code' => '234', 'phone_code' => '298'),
		'FJI' => array('country_code' => '242', 'phone_code' => '679'),
		'FIN' => array('country_code' => '246', 'phone_code' => '358'),
		'FRA' => array('country_code' => '250', 'phone_code' => '33'),
		'GUF' => array('country_code' => '254', 'phone_code' => '594'),
		'PYF' => array('country_code' => '258', 'phone_code' => '689'),
		'ATF' => array('country_code' => '260', 'phone_code' => ''),
		'GAB' => array('country_code' => '266', 'phone_code' => '241'),
		'GMB' => array('country_code' => '270', 'phone_code' => '220'),
		'GEO' => array('country_code' => '268', 'phone_code' => '995'),
		'DEU' => array('country_code' => '276', 'phone_code' => '49'),
		'GHA' => array('country_code' => '288', 'phone_code' => '233'),
		'GIB' => array('country_code' => '292', 'phone_code' => '350'),
		'GRC' => array('country_code' => '300', 'phone_code' => '30'),
		'GRL' => array('country_code' => '304', 'phone_code' => '299'),
		'GRD' => array('country_code' => '308', 'phone_code' => '1'),
		'GLP' => array('country_code' => '312', 'phone_code' => '590'),
		'GUM' => array('country_code' => '316', 'phone_code' => '1'),
		'GTM' => array('country_code' => '320', 'phone_code' => '502'),
		'GIN' => array('country_code' => '324', 'phone_code' => '224'),
		'GNB' => array('country_code' => '624', 'phone_code' => '245'),
		'GUY' => array('country_code' => '328', 'phone_code' => '592'),
		'HTI' => array('country_code' => '332', 'phone_code' => '509'),
		'HMD' => array('country_code' => '334', 'phone_code' => ''),
		'HND' => array('country_code' => '340', 'phone_code' => '504'),
		'HKG' => array('country_code' => '344', 'phone_code' => '852'),
		'HUN' => array('country_code' => '348', 'phone_code' => '36'),
		'ISL' => array('country_code' => '352', 'phone_code' => '354'),
		'IND' => array('country_code' => '356', 'phone_code' => '91'),
		'IDN' => array('country_code' => '360', 'phone_code' => '62'),
		'IRN' => array('country_code' => '364', 'phone_code' => '98'),
		'IRQ' => array('country_code' => '368', 'phone_code' => '964'),
		'IRL' => array('country_code' => '372', 'phone_code' => '353'),
		'ISR' => array('country_code' => '376', 'phone_code' => '972'),
		'ITA' => array('country_code' => '380', 'phone_code' => '39'),
		'JAM' => array('country_code' => '388', 'phone_code' => '1'),
		'JPN' => array('country_code' => '392', 'phone_code' => '81'),
		'JOR' => array('country_code' => '400', 'phone_code' => '962'),
		'KAZ' => array('country_code' => '398', 'phone_code' => '7'),
		'KEN' => array('country_code' => '404', 'phone_code' => '254'),
		'KIR' => array('country_code' => '296', 'phone_code' => '686'),
		'PRK' => array('country_code' => '408', 'phone_code' => '850'),
		'KOR' => array('country_code' => '410', 'phone_code' => '82'),
		'KWT' => array('country_code' => '414', 'phone_code' => '965'),
		'KGZ' => array('country_code' => '417', 'phone_code' => '996'),
		'LAO' => array('country_code' => '418', 'phone_code' => '856'),
		'LVA' => array('country_code' => '428', 'phone_code' => '371'),
		'LBN' => array('country_code' => '422', 'phone_code' => '961'),
		'LSO' => array('country_code' => '426', 'phone_code' => '266'),
		'LBR' => array('country_code' => '430', 'phone_code' => '231'),
		'LBY' => array('country_code' => '434', 'phone_code' => '218'),
		'LIE' => array('country_code' => '438', 'phone_code' => '423'),
		'LTU' => array('country_code' => '440', 'phone_code' => '370'),
		'LUX' => array('country_code' => '442', 'phone_code' => '352'),
		'MAC' => array('country_code' => '446', 'phone_code' => '853'),
		'MKD' => array('country_code' => '807', 'phone_code' => '389'),
		'MDG' => array('country_code' => '450', 'phone_code' => '261'),
		'MWI' => array('country_code' => '454', 'phone_code' => '265'),
		'MYS' => array('country_code' => '458', 'phone_code' => '60'),
		'MDV' => array('country_code' => '462', 'phone_code' => '960'),
		'MLI' => array('country_code' => '466', 'phone_code' => '223'),
		'MLT' => array('country_code' => '470', 'phone_code' => '356'),
		'MHL' => array('country_code' => '584', 'phone_code' => '692'),
		'MTQ' => array('country_code' => '474', 'phone_code' => '596'),
		'MRT' => array('country_code' => '478', 'phone_code' => '222'),
		'MUS' => array('country_code' => '480', 'phone_code' => '230'),
		'MYT' => array('country_code' => '175', 'phone_code' => '262'),
		'MEX' => array('country_code' => '484', 'phone_code' => '52'),
		'FSM' => array('country_code' => '583', 'phone_code' => '691'),
		'MDA' => array('country_code' => '498', 'phone_code' => '373'),
		'MCO' => array('country_code' => '492', 'phone_code' => '377'),
		'MNG' => array('country_code' => '496', 'phone_code' => '976'),
		'MSR' => array('country_code' => '500', 'phone_code' => '1'),
		'MAR' => array('country_code' => '504', 'phone_code' => '212'),
		'MOZ' => array('country_code' => '508', 'phone_code' => '258'),
		'MMR' => array('country_code' => '104', 'phone_code' => '95'),
		'NAM' => array('country_code' => '516', 'phone_code' => '264'),
		'NRU' => array('country_code' => '520', 'phone_code' => '674'),
		'NPL' => array('country_code' => '524', 'phone_code' => '977'),
		'NLD' => array('country_code' => '528', 'phone_code' => '31'),
		'ANT' => array('country_code' => '', 'phone_code' => '599'),
		'NCL' => array('country_code' => '540', 'phone_code' => '687'),
		'NZL' => array('country_code' => '554', 'phone_code' => '64'),
		'NIC' => array('country_code' => '558', 'phone_code' => '505'),
		'NER' => array('country_code' => '562', 'phone_code' => '227'),
		'NGA' => array('country_code' => '566', 'phone_code' => '234'),
		'NIU' => array('country_code' => '570', 'phone_code' => '683'),
		'NFK' => array('country_code' => '574', 'phone_code' => '672'),
		'MNP' => array('country_code' => '580', 'phone_code' => '1'),
		'NOR' => array('country_code' => '578', 'phone_code' => '47'),
		'OMN' => array('country_code' => '512', 'phone_code' => '968'),
		'PAK' => array('country_code' => '586', 'phone_code' => '92'),
		'PLW' => array('country_code' => '585', 'phone_code' => '680'),
		'PAN' => array('country_code' => '591', 'phone_code' => '507'),
		'PNG' => array('country_code' => '598', 'phone_code' => '675'),
		'PRY' => array('country_code' => '600', 'phone_code' => '595'),
		'PER' => array('country_code' => '604', 'phone_code' => '51'),
		'PHL' => array('country_code' => '608', 'phone_code' => '63'),
		'PCN' => array('country_code' => '612', 'phone_code' => ''),
		'POL' => array('country_code' => '616', 'phone_code' => '48'),
		'PRT' => array('country_code' => '620', 'phone_code' => '351'),
		'PRI' => array('country_code' => '630', 'phone_code' => '1'),
		'QAT' => array('country_code' => '634', 'phone_code' => '974'),
		'REU' => array('country_code' => '638', 'phone_code' => '262'),
		'ROM' => array('country_code' => '', 'phone_code' => '40'),
		'RUS' => array('country_code' => '643', 'phone_code' => '7'),
		'RWA' => array('country_code' => '646', 'phone_code' => '250'),
		'KNA' => array('country_code' => '659', 'phone_code' => '1'),
		'LCA' => array('country_code' => '662', 'phone_code' => '1'),
		'VCT' => array('country_code' => '670', 'phone_code' => '1'),
		'WSM' => array('country_code' => '882', 'phone_code' => '685'),
		'SMR' => array('country_code' => '674', 'phone_code' => '378'),
		'STP' => array('country_code' => '678', 'phone_code' => '239'),
		'SAU' => array('country_code' => '682', 'phone_code' => '966'),
		'SEN' => array('country_code' => '686', 'phone_code' => '221'),
		'SYC' => array('country_code' => '690', 'phone_code' => '248'),
		'SLE' => array('country_code' => '694', 'phone_code' => '232'),
		'SGP' => array('country_code' => '702', 'phone_code' => '65'),
		'SVK' => array('country_code' => '703', 'phone_code' => '421'),
		'SVN' => array('country_code' => '705', 'phone_code' => '386'),
		'SLB' => array('country_code' => '090', 'phone_code' => '677'),
		'SOM' => array('country_code' => '706', 'phone_code' => '252'),
		'ZAF' => array('country_code' => '710', 'phone_code' => '27'),
		'SGS' => array('country_code' => '239', 'phone_code' => ''),
		'ESP' => array('country_code' => '724', 'phone_code' => '34'),
		'LKA' => array('country_code' => '144', 'phone_code' => '94'),
		'SHN' => array('country_code' => '654', 'phone_code' => '290'),
		'SPM' => array('country_code' => '666', 'phone_code' => '508'),
		'SDN' => array('country_code' => '729', 'phone_code' => '249'),
		'SUR' => array('country_code' => '740', 'phone_code' => '597'),
		'SJM' => array('country_code' => '744', 'phone_code' => ''),
		'SWZ' => array('country_code' => '748', 'phone_code' => '268'),
		'SWE' => array('country_code' => '752', 'phone_code' => '46'),
		'CHE' => array('country_code' => '756', 'phone_code' => '41'),
		'SYR' => array('country_code' => '760', 'phone_code' => '963'),
		'TWN' => array('country_code' => '158', 'phone_code' => '886'),
		'TJK' => array('country_code' => '762', 'phone_code' => '992'),
		'TZA' => array('country_code' => '834', 'phone_code' => '255'),
		'THA' => array('country_code' => '764', 'phone_code' => '66'),
		'TGO' => array('country_code' => '768', 'phone_code' => '228'),
		'TKL' => array('country_code' => '772', 'phone_code' => '690'),
		'TON' => array('country_code' => '776', 'phone_code' => '676'),
		'TTO' => array('country_code' => '780', 'phone_code' => '1'),
		'TUN' => array('country_code' => '788', 'phone_code' => '216'),
		'TUR' => array('country_code' => '792', 'phone_code' => '90'),
		'TKM' => array('country_code' => '795', 'phone_code' => '993'),
		'TCA' => array('country_code' => '796', 'phone_code' => '1'),
		'TUV' => array('country_code' => '798', 'phone_code' => '688'),
		'UGA' => array('country_code' => '800', 'phone_code' => '256'),
		'UKR' => array('country_code' => '804', 'phone_code' => '380'),
		'ARE' => array('country_code' => '784', 'phone_code' => '971'),
		'GBR' => array('country_code' => '826', 'phone_code' => '44'),
		'USA' => array('country_code' => '840', 'phone_code' => '1'),
		'UMI' => array('country_code' => '581', 'phone_code' => ''),
		'URY' => array('country_code' => '858', 'phone_code' => '598'),
		'UZB' => array('country_code' => '860', 'phone_code' => '998'),
		'VUT' => array('country_code' => '548', 'phone_code' => '678'),
		'VAT' => array('country_code' => '336', 'phone_code' => '39'),
		'VEN' => array('country_code' => '862', 'phone_code' => '58'),
		'VNM' => array('country_code' => '704', 'phone_code' => '84'),
		'VGB' => array('country_code' => '092', 'phone_code' => '1'),
		'VIR' => array('country_code' => '850', 'phone_code' => '1'),
		'WLF' => array('country_code' => '876', 'phone_code' => '681'),
		'ESH' => array('country_code' => '732', 'phone_code' => ''),
		'YEM' => array('country_code' => '887', 'phone_code' => '967'),
		'COD' => array('country_code' => '180', 'phone_code' => '243'),
		'ZMB' => array('country_code' => '894', 'phone_code' => '260'),
		'ZWE' => array('country_code' => '716', 'phone_code' => '263'),
		'MNE' => array('country_code' => '499', 'phone_code' => '382'),
		'SRB' => array('country_code' => '688', 'phone_code' => '381'),
		'ALA' => array('country_code' => '248', 'phone_code' => ''),
		'BES' => array('country_code' => '535', 'phone_code' => ''),
		'CUW' => array('country_code' => '531', 'phone_code' => ''),
		'PSE' => array('country_code' => '275', 'phone_code' => '970'),
		'SSD' => array('country_code' => '728', 'phone_code' => ''),
		'BLM' => array('country_code' => '652', 'phone_code' => '590'),
		'MAF' => array('country_code' => '663', 'phone_code' => '590'),
		'ICA' => array('country_code' => '', 'phone_code' => ''),
		'ASC' => array('country_code' => '', 'phone_code' => ''),
		'UNK' => array('country_code' => '', 'phone_code' => '381'),
		'IMN' => array('country_code' => '833', 'phone_code' => ''),
		'SHN' => array('country_code' => '654', 'phone_code' => ''),
		'GGY' => array('country_code' => '831', 'phone_code' => ''),
		'JEY' => array('country_code' => '832', 'phone_code' => '')
	)
);
?>