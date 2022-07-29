<?php

class ControllerExtensionModuleIP2LocationRedirector extends Controller
{
	const VERSION = '2.2.1';
	const YEAR_CREATED = 2018;
	const EMAIL = 'support@ip2location.com';

	private $error = array();
	private $data = array();
	private $isRegionSupported = false;
	private $isDatabaseFound = false;

	public function install() {
		$countries = array('AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia, Plurinational State of', 'BQ' => 'Bonaire, Sint Eustatius and Saba', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'CV' => 'Cabo Verde', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo, The Democratic Republic of The', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote D\'ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'VA' => 'Holy See', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran, Islamic Republic of', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KP' => 'Korea, Democratic People\'s Republic of', 'KR' => 'Korea, Republic of', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People\'s Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia, The Former Yugoslav Republic of', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States of', 'MD' => 'Moldova, Republic of', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestine, State of', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena, Ascension and Tristan Da Cunha', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin (French Part)', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent and The Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten (Dutch Part)', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia and The South Sandwich Islands', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania, United Republic of', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Minor Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela, Bolivarian Republic of', 'VN' => 'Viet Nam', 'VG' => 'Virgin Islands, British', 'VI' => 'Virgin Islands, U.S.', 'WF' => 'Wallis and Futuna', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');

		$regions = array(array('AD', 'Andorra la Vella'), array('AD', 'Canillo'), array('AD', 'Encamp'), array('AD', 'Escaldes-Engordany'), array('AD', 'La Massana'), array('AD', 'Ordino'), array('AD', 'Sant Julia de Loria'), array('AE', '\'Ajman'), array('AE', 'Abu Zaby'), array('AE', 'Al Fujayrah'), array('AE', 'Ash Shariqah'), array('AE', 'Dubayy'), array('AE', 'Ra\'s al Khaymah'), array('AE', 'Umm al Qaywayn'), array('AF', 'Balkh'), array('AF', 'Bamyan'), array('AF', 'Daykundi'), array('AF', 'Ghor'), array('AF', 'Herat'), array('AF', 'Kabul'), array('AF', 'Kandahar'), array('AF', 'Khost'), array('AF', 'Kunar'), array('AF', 'Kunduz'), array('AF', 'Logar'), array('AF', 'Nangarhar'), array('AF', 'Nimroz'), array('AF', 'Paktiya'), array('AF', 'Parwan'), array('AF', 'Uruzgan'), array('AG', 'Saint John'), array('AG', 'Saint Mary'), array('AG', 'Saint Paul'), array('AI', 'Anguilla'), array('AL', 'Berat'), array('AL', 'Diber'), array('AL', 'Durres'), array('AL', 'Elbasan'), array('AL', 'Fier'), array('AL', 'Gjirokaster'), array('AL', 'Korce'), array('AL', 'Kukes'), array('AL', 'Lezhe'), array('AL', 'Shkoder'), array('AL', 'Tirane'), array('AL', 'Vlore'), array('AM', 'Aragacotn'), array('AM', 'Ararat'), array('AM', 'Armavir'), array('AM', 'Erevan'), array('AM', 'Gegark\'unik\''), array('AM', 'Kotayk\''), array('AM', 'Lori'), array('AM', 'Sirak'), array('AM', 'Syunik\''), array('AM', 'Tavus'), array('AM', 'Vayoc Jor'), array('AO', 'Bengo'), array('AO', 'Benguela'), array('AO', 'Bie'), array('AO', 'Cabinda'), array('AO', 'Cunene'), array('AO', 'Huambo'), array('AO', 'Huila'), array('AO', 'Kuando Kubango'), array('AO', 'Kwanza Norte'), array('AO', 'Kwanza Sul'), array('AO', 'Luanda'), array('AO', 'Lunda Norte'), array('AO', 'Lunda Sul'), array('AO', 'Malange'), array('AO', 'Moxico'), array('AO', 'Namibe'), array('AO', 'Uige'), array('AO', 'Zaire'), array('AR', 'Buenos Aires'), array('AR', 'Catamarca'), array('AR', 'Chaco'), array('AR', 'Chubut'), array('AR', 'Ciudad Autonoma de Buenos Aires'), array('AR', 'Cordoba'), array('AR', 'Corrientes'), array('AR', 'Entre Rios'), array('AR', 'Formosa'), array('AR', 'Jujuy'), array('AR', 'La Pampa'), array('AR', 'La Rioja'), array('AR', 'Mendoza'), array('AR', 'Misiones'), array('AR', 'Neuquen'), array('AR', 'Rio Negro'), array('AR', 'Salta'), array('AR', 'San Juan'), array('AR', 'San Luis'), array('AR', 'Santa Cruz'), array('AR', 'Santa Fe'), array('AR', 'Santiago del Estero'), array('AR', 'Tierra del Fuego'), array('AR', 'Tucuman'), array('AS', 'Eastern District'), array('AS', 'Western District'), array('AT', 'Burgenland'), array('AT', 'Karnten'), array('AT', 'Niederosterreich'), array('AT', 'Oberosterreich'), array('AT', 'Salzburg'), array('AT', 'Steiermark'), array('AT', 'Tirol'), array('AT', 'Vorarlberg'), array('AT', 'Wien'), array('AU', 'Australian Capital Territory'), array('AU', 'New South Wales'), array('AU', 'Northern Territory'), array('AU', 'Queensland'), array('AU', 'South Australia'), array('AU', 'Tasmania'), array('AU', 'Victoria'), array('AU', 'Western Australia'), array('AW', 'Aruba (general)'), array('AX', 'Eckeroe'), array('AX', 'Finstroem'), array('AX', 'Hammarland'), array('AX', 'Jomala'), array('AX', 'Mariehamn'), array('AX', 'Saltvik'), array('AZ', 'Abseron'), array('AZ', 'Agcabadi'), array('AZ', 'Agdas'), array('AZ', 'Agstafa'), array('AZ', 'Agsu'), array('AZ', 'Astara'), array('AZ', 'Baki'), array('AZ', 'Balakan'), array('AZ', 'Barda'), array('AZ', 'Beylaqan'), array('AZ', 'Bilasuvar'), array('AZ', 'Calilabad'), array('AZ', 'Daskasan'), array('AZ', 'Ganca'), array('AZ', 'Goycay'), array('AZ', 'Goygol'), array('AZ', 'Haciqabul'), array('AZ', 'Imisli'), array('AZ', 'Ismayilli'), array('AZ', 'Lankaran'), array('AZ', 'Lerik'), array('AZ', 'Masalli'), array('AZ', 'Mingacevir'), array('AZ', 'Naxcivan'), array('AZ', 'Oguz'), array('AZ', 'Qabala'), array('AZ', 'Qax'), array('AZ', 'Qazax'), array('AZ', 'Quba'), array('AZ', 'Qusar'), array('AZ', 'Saatli'), array('AZ', 'Sabirabad'), array('AZ', 'Saki'), array('AZ', 'Salyan'), array('AZ', 'Samaxi'), array('AZ', 'Samkir'), array('AZ', 'Samux'), array('AZ', 'Sirvan'), array('AZ', 'Sumqayit'), array('AZ', 'Tartar'), array('AZ', 'Tovuz'), array('AZ', 'Ucar'), array('AZ', 'Xacmaz'), array('AZ', 'Xizi'), array('AZ', 'Yardimli'), array('AZ', 'Yevlax'), array('AZ', 'Zaqatala'), array('AZ', 'Zardab'), array('BA', 'Federacija Bosna i Hercegovina'), array('BA', 'Republika Srpska'), array('BB', 'Christ Church'), array('BB', 'Saint James'), array('BB', 'Saint Joseph'), array('BB', 'Saint Michael'), array('BB', 'Saint Peter'), array('BD', 'Barisal'), array('BD', 'Chittagong'), array('BD', 'Dhaka'), array('BD', 'Khulna'), array('BD', 'Rajshahi'), array('BD', 'Rangpur'), array('BD', 'Sylhet'), array('BE', 'Antwerpen'), array('BE', 'Brabant Wallon'), array('BE', 'Brussels Hoofdstedelijk Gewest'), array('BE', 'Hainaut'), array('BE', 'Liege'), array('BE', 'Limburg'), array('BE', 'Luxembourg'), array('BE', 'Namur'), array('BE', 'Oost-Vlaanderen'), array('BE', 'Vlaams-Brabant'), array('BE', 'West-Vlaanderen'), array('BF', 'Boulkiemde'), array('BF', 'Comoe'), array('BF', 'Gourma'), array('BF', 'Houet'), array('BF', 'Kadiogo'), array('BF', 'Mouhoun'), array('BF', 'Passore'), array('BF', 'Tui'), array('BF', 'Yatenga'), array('BG', 'Blagoevgrad'), array('BG', 'Burgas'), array('BG', 'Dobrich'), array('BG', 'Gabrovo'), array('BG', 'Haskovo'), array('BG', 'Kardzhali'), array('BG', 'Kyustendil'), array('BG', 'Lovech'), array('BG', 'Montana'), array('BG', 'Pazardzhik'), array('BG', 'Pernik'), array('BG', 'Pleven'), array('BG', 'Plovdiv'), array('BG', 'Razgrad'), array('BG', 'Ruse'), array('BG', 'Shumen'), array('BG', 'Silistra'), array('BG', 'Sliven'), array('BG', 'Smolyan'), array('BG', 'Sofia'), array('BG', 'Sofia (stolitsa)'), array('BG', 'Stara Zagora'), array('BG', 'Targovishte'), array('BG', 'Varna'), array('BG', 'Veliko Tarnovo'), array('BG', 'Vidin'), array('BG', 'Vratsa'), array('BG', 'Yambol'), array('BH', 'Al Asimah'), array('BH', 'Al Muharraq'), array('BH', 'Ash Shamaliyah'), array('BI', 'Bujumbura Mairie'), array('BI', 'Bururi'), array('BI', 'Gitega'), array('BI', 'Kirundo'), array('BI', 'Mwaro'), array('BI', 'Ngozi'), array('BJ', 'Alibori'), array('BJ', 'Atacora'), array('BJ', 'Atlantique'), array('BJ', 'Borgou'), array('BJ', 'Collines'), array('BJ', 'Donga'), array('BJ', 'Littoral'), array('BJ', 'Mono'), array('BJ', 'Oueme'), array('BJ', 'Plateau'), array('BJ', 'Zou'), array('BL', 'Saint Barthelemy'), array('BM', 'Hamilton'), array('BM', 'Saint George'), array('BN', 'Belait'), array('BN', 'Brunei-Muara'), array('BN', 'Temburong'), array('BN', 'Tutong'), array('BO', 'Chuquisaca'), array('BO', 'Cochabamba'), array('BO', 'El Beni'), array('BO', 'La Paz'), array('BO', 'Oruro'), array('BO', 'Pando'), array('BO', 'Potosi'), array('BO', 'Santa Cruz'), array('BO', 'Tarija'), array('BQ', 'Bonaire'), array('BQ', 'Saba'), array('BQ', 'Sint Eustatius'), array('BR', 'Acre'), array('BR', 'Alagoas'), array('BR', 'Amapa'), array('BR', 'Amazonas'), array('BR', 'Bahia'), array('BR', 'Ceara'), array('BR', 'Distrito Federal'), array('BR', 'Espirito Santo'), array('BR', 'Goias'), array('BR', 'Maranhao'), array('BR', 'Mato Grosso'), array('BR', 'Mato Grosso do Sul'), array('BR', 'Minas Gerais'), array('BR', 'Para'), array('BR', 'Paraiba'), array('BR', 'Parana'), array('BR', 'Pernambuco'), array('BR', 'Piaui'), array('BR', 'Rio Grande do Norte'), array('BR', 'Rio Grande do Sul'), array('BR', 'Rio de Janeiro'), array('BR', 'Rondonia'), array('BR', 'Roraima'), array('BR', 'Santa Catarina'), array('BR', 'Sao Paulo'), array('BR', 'Sergipe'), array('BR', 'Tocantins'), array('BS', 'Freeport'), array('BS', 'Fresh Creek'), array('BS', 'Harbour Island'), array('BS', 'Long Island'), array('BS', 'Marsh Harbour'), array('BS', 'New Providence'), array('BS', 'Rock Sound'), array('BT', 'Chhukha'), array('BT', 'Dagana'), array('BT', 'Gasa'), array('BT', 'Monggar'), array('BT', 'Paro'), array('BT', 'Punakha'), array('BT', 'Thimphu'), array('BT', 'Trashi Yangtse'), array('BW', 'Central'), array('BW', 'Kgatleng'), array('BW', 'Kweneng'), array('BW', 'North-East'), array('BW', 'North-West'), array('BW', 'South-East'), array('BY', 'Brestskaya Voblasts\''), array('BY', 'Homyel\'skaya Voblasts\''), array('BY', 'Hrodzyenskaya Voblasts\''), array('BY', 'Mahilyowskaya Voblasts\''), array('BY', 'Minskaya Voblasts\''), array('BY', 'Vitsyebskaya Voblasts\''), array('BZ', 'Belize'), array('BZ', 'Cayo'), array('BZ', 'Corozal'), array('BZ', 'Orange Walk'), array('BZ', 'Stann Creek'), array('BZ', 'Toledo'), array('CA', 'Alberta'), array('CA', 'British Columbia'), array('CA', 'Manitoba'), array('CA', 'New Brunswick'), array('CA', 'Newfoundland and Labrador'), array('CA', 'Northwest Territories'), array('CA', 'Nova Scotia'), array('CA', 'Nunavut'), array('CA', 'Ontario'), array('CA', 'Prince Edward Island'), array('CA', 'Quebec'), array('CA', 'Saskatchewan'), array('CA', 'Yukon'), array('CD', 'Bandundu'), array('CD', 'Bas-Congo'), array('CD', 'Equateur'), array('CD', 'Kasai-Occidental'), array('CD', 'Kasai-Oriental'), array('CD', 'Katanga'), array('CD', 'Kinshasa'), array('CD', 'Maniema'), array('CD', 'Nord-Kivu'), array('CD', 'Orientale'), array('CD', 'Sud-Kivu'), array('CF', 'Bangui'), array('CF', 'Ouham'), array('CF', 'Ouham-Pende'), array('CG', 'Brazzaville'), array('CG', 'Cuvette'), array('CG', 'Cuvette-Ouest'), array('CG', 'Pointe-Noire'), array('CG', 'Sangha'), array('CH', 'Aargau'), array('CH', 'Appenzell Ausserrhoden'), array('CH', 'Appenzell Innerrhoden'), array('CH', 'Basel-Landschaft'), array('CH', 'Basel-Stadt'), array('CH', 'Bern'), array('CH', 'Fribourg'), array('CH', 'Geneve'), array('CH', 'Glarus'), array('CH', 'Graubunden'), array('CH', 'Jura'), array('CH', 'Luzern'), array('CH', 'Neuchatel'), array('CH', 'Nidwalden'), array('CH', 'Obwalden'), array('CH', 'Sankt Gallen'), array('CH', 'Schaffhausen'), array('CH', 'Schwyz'), array('CH', 'Solothurn'), array('CH', 'Thurgau'), array('CH', 'Ticino'), array('CH', 'Uri'), array('CH', 'Valais'), array('CH', 'Vaud'), array('CH', 'Zug'), array('CH', 'Zurich'), array('CI', 'Agneby'), array('CI', 'Bas-Sassandra'), array('CI', 'Dix-Huit Montagnes'), array('CI', 'Fromager'), array('CI', 'Haut-Sassandra'), array('CI', 'Lacs'), array('CI', 'Lagunes'), array('CI', 'Marahoue'), array('CI', 'Moyen-Cavally'), array('CI', 'Moyen-Comoe'), array('CI', 'N\'zi-Comoe'), array('CI', 'Savanes'), array('CI', 'Sud-Bandama'), array('CI', 'Sud-Comoe'), array('CI', 'Vallee du Bandama'), array('CI', 'Worodougou'), array('CI', 'Zanzan'), array('CK', 'Cook Islands'), array('CL', 'Antofagasta'), array('CL', 'Araucania'), array('CL', 'Arica y Parinacota'), array('CL', 'Atacama'), array('CL', 'Aysen'), array('CL', 'Biobio'), array('CL', 'Coquimbo'), array('CL', 'Libertador General Bernardo O\'Higgins'), array('CL', 'Los Lagos'), array('CL', 'Los Rios'), array('CL', 'Magallanes'), array('CL', 'Maule'), array('CL', 'Region Metropolitana de Santiago'), array('CL', 'Tarapaca'), array('CL', 'Valparaiso'), array('CM', 'Adamaoua'), array('CM', 'Centre'), array('CM', 'Est'), array('CM', 'Extreme-Nord'), array('CM', 'Littoral'), array('CM', 'Nord'), array('CM', 'Nord-Ouest'), array('CM', 'Ouest'), array('CM', 'Sud'), array('CM', 'Sud-Ouest'), array('CN', 'Anhui'), array('CN', 'Beijing'), array('CN', 'Chongqing'), array('CN', 'Fujian'), array('CN', 'Gansu'), array('CN', 'Guangdong'), array('CN', 'Guangxi'), array('CN', 'Guizhou'), array('CN', 'Hainan'), array('CN', 'Hebei'), array('CN', 'Heilongjiang'), array('CN', 'Henan'), array('CN', 'Hubei'), array('CN', 'Hunan'), array('CN', 'Jiangsu'), array('CN', 'Jiangxi'), array('CN', 'Jilin'), array('CN', 'Liaoning'), array('CN', 'Nei Mongol'), array('CN', 'Ningxia'), array('CN', 'Qinghai'), array('CN', 'Shaanxi'), array('CN', 'Shandong'), array('CN', 'Shanghai'), array('CN', 'Shanxi'), array('CN', 'Sichuan'), array('CN', 'Tianjin'), array('CN', 'Xinjiang'), array('CN', 'Xizang'), array('CN', 'Yunnan'), array('CN', 'Zhejiang'), array('CO', 'Amazonas'), array('CO', 'Antioquia'), array('CO', 'Arauca'), array('CO', 'Atlantico'), array('CO', 'Bolivar'), array('CO', 'Boyaca'), array('CO', 'Caldas'), array('CO', 'Caqueta'), array('CO', 'Casanare'), array('CO', 'Cauca'), array('CO', 'Cesar'), array('CO', 'Choco'), array('CO', 'Cordoba'), array('CO', 'Cundinamarca'), array('CO', 'Distrito Capital de Bogota'), array('CO', 'Guainia'), array('CO', 'Guaviare'), array('CO', 'Huila'), array('CO', 'La Guajira'), array('CO', 'Magdalena'), array('CO', 'Meta'), array('CO', 'Narino'), array('CO', 'Norte de Santander'), array('CO', 'Putumayo'), array('CO', 'Quindio'), array('CO', 'Risaralda'), array('CO', 'San Andres, Providencia y Santa Catalina'), array('CO', 'Santander'), array('CO', 'Sucre'), array('CO', 'Tolima'), array('CO', 'Valle del Cauca'), array('CO', 'Vaupes'), array('CO', 'Vichada'), array('CR', 'Alajuela'), array('CR', 'Cartago'), array('CR', 'Guanacaste'), array('CR', 'Heredia'), array('CR', 'Limon'), array('CR', 'Puntarenas'), array('CR', 'San Jose'), array('CU', 'Artemisa'), array('CU', 'Camaguey'), array('CU', 'Ciego de Avila'), array('CU', 'Cienfuegos'), array('CU', 'Granma'), array('CU', 'Guantanamo'), array('CU', 'Holguin'), array('CU', 'La Habana'), array('CU', 'Las Tunas'), array('CU', 'Matanzas'), array('CU', 'Mayabeque'), array('CU', 'Pinar del Rio'), array('CU', 'Sancti Spiritus'), array('CU', 'Santiago de Cuba'), array('CU', 'Villa Clara'), array('CV', 'Boa Vista'), array('CV', 'Brava'), array('CV', 'Porto Novo'), array('CV', 'Praia'), array('CV', 'Sal'), array('CV', 'Santa Catarina'), array('CV', 'Sao Filipe'), array('CV', 'Sao Miguel'), array('CV', 'Sao Vicente'), array('CW', 'Curacao'), array('CY', 'Ammochostos'), array('CY', 'Keryneia'), array('CY', 'Larnaka'), array('CY', 'Lefkosia'), array('CY', 'Lemesos'), array('CY', 'Pafos'), array('CZ', 'Jihocesky kraj'), array('CZ', 'Jihomoravsky kraj'), array('CZ', 'Karlovarsky kraj'), array('CZ', 'Kralovehradecky kraj'), array('CZ', 'Liberecky kraj'), array('CZ', 'Moravskoslezsky kraj'), array('CZ', 'Olomoucky kraj'), array('CZ', 'Pardubicky kraj'), array('CZ', 'Plzensky kraj'), array('CZ', 'Praha, hlavni mesto'), array('CZ', 'Stredocesky kraj'), array('CZ', 'Ustecky kraj'), array('CZ', 'Vysocina kraj'), array('CZ', 'Zlinsky kraj'), array('DE', 'Baden-Wurttemberg'), array('DE', 'Bayern'), array('DE', 'Berlin'), array('DE', 'Brandenburg'), array('DE', 'Bremen'), array('DE', 'Hamburg'), array('DE', 'Hessen'), array('DE', 'Mecklenburg-Vorpommern'), array('DE', 'Niedersachsen'), array('DE', 'Nordrhein-Westfalen'), array('DE', 'Rheinland-Pfalz'), array('DE', 'Saarland'), array('DE', 'Sachsen'), array('DE', 'Sachsen-Anhalt'), array('DE', 'Schleswig-Holstein'), array('DE', 'Thuringen'), array('DJ', 'Dikhil'), array('DJ', 'Djibouti'), array('DJ', 'Tadjourah'), array('DK', 'Hovedstaden'), array('DK', 'Midtjylland'), array('DK', 'Nordjylland'), array('DK', 'Sjelland'), array('DK', 'Syddanmark'), array('DM', 'Saint George'), array('DM', 'Saint John'), array('DM', 'Saint Joseph'), array('DM', 'Saint Paul'), array('DO', 'Azua'), array('DO', 'Baoruco'), array('DO', 'Barahona'), array('DO', 'Dajabon'), array('DO', 'Distrito Nacional'), array('DO', 'Duarte'), array('DO', 'El Seibo'), array('DO', 'Espaillat'), array('DO', 'Hato Mayor'), array('DO', 'Independencia'), array('DO', 'La Altagracia'), array('DO', 'La Romana'), array('DO', 'La Vega'), array('DO', 'Maria Trinidad Sanchez'), array('DO', 'Monsenor Nouel'), array('DO', 'Monte Cristi'), array('DO', 'Monte Plata'), array('DO', 'Pedernales'), array('DO', 'Peravia'), array('DO', 'Puerto Plata'), array('DO', 'Salcedo'), array('DO', 'Samana'), array('DO', 'San Cristobal'), array('DO', 'San Juan'), array('DO', 'San Pedro De Macoris'), array('DO', 'Sanchez Ramirez'), array('DO', 'Santiago'), array('DO', 'Santiago Rodriguez'), array('DO', 'Valverde'), array('DZ', 'Adrar'), array('DZ', 'Ain Defla'), array('DZ', 'Ain Temouchent'), array('DZ', 'Alger'), array('DZ', 'Annaba'), array('DZ', 'Batna'), array('DZ', 'Bechar'), array('DZ', 'Bejaia'), array('DZ', 'Biskra'), array('DZ', 'Blida'), array('DZ', 'Bordj Bou Arreridj'), array('DZ', 'Bouira'), array('DZ', 'Boumerdes'), array('DZ', 'Chlef'), array('DZ', 'Constantine'), array('DZ', 'Djelfa'), array('DZ', 'El Bayadh'), array('DZ', 'El Oued'), array('DZ', 'El Tarf'), array('DZ', 'Ghardaia'), array('DZ', 'Guelma'), array('DZ', 'Illizi'), array('DZ', 'Khenchela'), array('DZ', 'Laghouat'), array('DZ', 'Mascara'), array('DZ', 'Medea'), array('DZ', 'Mila'), array('DZ', 'Mostaganem'), array('DZ', 'Msila'), array('DZ', 'Naama'), array('DZ', 'Oran'), array('DZ', 'Ouargla'), array('DZ', 'Oum el Bouaghi'), array('DZ', 'Relizane'), array('DZ', 'Saida'), array('DZ', 'Setif'), array('DZ', 'Sidi Bel Abbes'), array('DZ', 'Skikda'), array('DZ', 'Souk Ahras'), array('DZ', 'Tamanrasset'), array('DZ', 'Tebessa'), array('DZ', 'Tiaret'), array('DZ', 'Tindouf'), array('DZ', 'Tipaza'), array('DZ', 'Tissemsilt'), array('DZ', 'Tizi Ouzou'), array('DZ', 'Tlemcen'), array('EC', 'Azuay'), array('EC', 'Bolivar'), array('EC', 'Canar'), array('EC', 'Carchi'), array('EC', 'Chimborazo'), array('EC', 'Cotopaxi'), array('EC', 'El Oro'), array('EC', 'Esmeraldas'), array('EC', 'Galapagos'), array('EC', 'Guayas'), array('EC', 'Imbabura'), array('EC', 'Loja'), array('EC', 'Los Rios'), array('EC', 'Manabi'), array('EC', 'Morona-Santiago'), array('EC', 'Napo'), array('EC', 'Orellana'), array('EC', 'Pastaza'), array('EC', 'Pichincha'), array('EC', 'Santa Elena'), array('EC', 'Sucumbios'), array('EC', 'Tungurahua'), array('EC', 'Zamora-Chinchipe'), array('EE', 'Harjumaa'), array('EE', 'Hiiumaa'), array('EE', 'Ida-Virumaa'), array('EE', 'Jarvamaa'), array('EE', 'Jogevamaa'), array('EE', 'Laane-Virumaa'), array('EE', 'Laanemaa'), array('EE', 'Parnumaa'), array('EE', 'Polvamaa'), array('EE', 'Raplamaa'), array('EE', 'Saaremaa'), array('EE', 'Tartumaa'), array('EE', 'Valgamaa'), array('EE', 'Viljandimaa'), array('EE', 'Vorumaa'), array('EG', 'Ad Daqahliyah'), array('EG', 'Al Bahr al Ahmar'), array('EG', 'Al Buhayrah'), array('EG', 'Al Fayyum'), array('EG', 'Al Gharbiyah'), array('EG', 'Al Iskandariyah'), array('EG', 'Al Ismailiyah'), array('EG', 'Al Jizah'), array('EG', 'Al Minufiyah'), array('EG', 'Al Minya'), array('EG', 'Al Qahirah'), array('EG', 'Al Qalyubiyah'), array('EG', 'Al Uqsur'), array('EG', 'Al Wadi al Jadid'), array('EG', 'As Suways'), array('EG', 'Ash Sharqiyah'), array('EG', 'Aswan'), array('EG', 'Asyut'), array('EG', 'Bani Suwayf'), array('EG', 'Bur Sa\'id'), array('EG', 'Dumyat'), array('EG', 'Janub Sina\''), array('EG', 'Kafr ash Shaykh'), array('EG', 'Matruh'), array('EG', 'Qina'), array('EG', 'Shamal Sina\''), array('EG', 'Suhaj'), array('ER', 'Al Awsat'), array('ES', 'Andalucia'), array('ES', 'Aragon'), array('ES', 'Asturias, Principado de'), array('ES', 'Canarias'), array('ES', 'Cantabria'), array('ES', 'Castilla y Leon'), array('ES', 'Castilla-La Mancha'), array('ES', 'Catalunya'), array('ES', 'Ceuta'), array('ES', 'Extremadura'), array('ES', 'Galicia'), array('ES', 'Illes Balears'), array('ES', 'La Rioja'), array('ES', 'Madrid, Comunidad de'), array('ES', 'Melilla'), array('ES', 'Murcia, Region de'), array('ES', 'Navarra, Comunidad Foral de'), array('ES', 'Pais Vasco'), array('ES', 'Valenciana, Comunidad'), array('ET', 'Adis Abeba'), array('ET', 'Afar'), array('ET', 'Amara'), array('ET', 'Dire Dawa'), array('ET', 'Oromiya'), array('ET', 'Sumale'), array('ET', 'Tigray'), array('ET', 'YeDebub Biheroch Bihereseboch na Hizboch'), array('FI', 'Etela-Karjala'), array('FI', 'Etela-Pohjanmaa'), array('FI', 'Etela-Savo'), array('FI', 'Kainuu'), array('FI', 'Kanta-Hame'), array('FI', 'Keski-Pohjanmaa'), array('FI', 'Keski-Suomi'), array('FI', 'Kymenlaakso'), array('FI', 'Lappi'), array('FI', 'Paijat-Hame'), array('FI', 'Pirkanmaa'), array('FI', 'Pohjanmaa'), array('FI', 'Pohjois-Karjala'), array('FI', 'Pohjois-Pohjanmaa'), array('FI', 'Pohjois-Savo'), array('FI', 'Satakunta'), array('FI', 'Uusimaa'), array('FI', 'Varsinais-Suomi'), array('FJ', 'Central'), array('FJ', 'Northern'), array('FJ', 'Western'), array('FK', 'Falkland Islands'), array('FM', 'Chuuk'), array('FM', 'Pohnpei'), array('FM', 'Yap'), array('FO', 'Eysturoy'), array('FO', 'Nordoyar'), array('FO', 'Sandoy'), array('FO', 'Streymoy'), array('FO', 'Suduroy'), array('FO', 'Vagar'), array('FR', 'Alsace'), array('FR', 'Aquitaine'), array('FR', 'Auvergne'), array('FR', 'Basse-Normandie'), array('FR', 'Bourgogne'), array('FR', 'Bretagne'), array('FR', 'Centre'), array('FR', 'Champagne-Ardenne'), array('FR', 'Corse'), array('FR', 'Franche-Comte'), array('FR', 'Haute-Normandie'), array('FR', 'Ile-de-France'), array('FR', 'Languedoc-Roussillon'), array('FR', 'Limousin'), array('FR', 'Lorraine'), array('FR', 'Midi-Pyrenees'), array('FR', 'Nord-Pas-de-Calais'), array('FR', 'Pays de la Loire'), array('FR', 'Picardie'), array('FR', 'Poitou-Charentes'), array('FR', 'Provence-Alpes-Cote d\'Azur'), array('FR', 'Rhone-Alpes'), array('GA', 'Estuaire'), array('GA', 'Haut-Ogooue'), array('GA', 'Moyen-Ogooue'), array('GA', 'Ngounie'), array('GA', 'Ogooue-Lolo'), array('GA', 'Ogooue-Maritime'), array('GA', 'Woleu-Ntem'), array('GB', 'England'), array('GB', 'Northern Ireland'), array('GB', 'Scotland'), array('GB', 'Wales'), array('GD', 'Saint Andrew'), array('GD', 'Saint David'), array('GD', 'Saint George'), array('GD', 'Saint John'), array('GD', 'Saint Mark'), array('GD', 'Saint Patrick'), array('GE', 'Abkhazia'), array('GE', 'Ajaria'), array('GE', 'Akhalk\'alak\'is Raioni'), array('GE', 'Baghdat\'is Raioni'), array('GE', 'Borjomis Raioni'), array('GE', 'Goris Raioni'), array('GE', 'Guria'), array('GE', 'Imereti'), array('GE', 'K\'arelis Raioni'), array('GE', 'Kakheti'), array('GE', 'Khashuris Raioni'), array('GE', 'Kvemo Kartli'), array('GE', 'Mtskheta-Mtianeti'), array('GE', 'Racha-Lechkhumi and Kvemo Svaneti'), array('GE', 'Samegrelo and Zemo Svaneti'), array('GE', 'Samtskhe-Javakheti'), array('GE', 'Shida Kartli'), array('GE', 'T\'bilisi'), array('GF', 'Guyane'), array('GG', 'Guernsey (general)'), array('GH', 'Ashanti'), array('GH', 'Brong-Ahafo'), array('GH', 'Central'), array('GH', 'Eastern'), array('GH', 'Greater Accra'), array('GH', 'Northern'), array('GH', 'Upper East'), array('GH', 'Volta'), array('GH', 'Western'), array('GI', 'Gibraltar'), array('GL', 'Kommune Kujalleq'), array('GL', 'Kommuneqarfik Sermersooq'), array('GL', 'Qaasuitsup Kommunia'), array('GL', 'Qeqqata Kommunia'), array('GM', 'Banjul'), array('GM', 'Central River'), array('GM', 'Lower River'), array('GM', 'North Bank'), array('GM', 'Western'), array('GN', 'Boke'), array('GN', 'Conakry'), array('GN', 'Coyah'), array('GN', 'Dabola'), array('GN', 'Dalaba'), array('GN', 'Dubreka'), array('GN', 'Fria'), array('GN', 'Gueckedou'), array('GN', 'Kankan'), array('GN', 'Kissidougou'), array('GN', 'Labe'), array('GN', 'Macenta'), array('GN', 'Nzerekore'), array('GN', 'Pita'), array('GN', 'Siguiri'), array('GP', 'Guadeloupe'), array('GQ', 'Bioko Norte'), array('GQ', 'Bioko Sur'), array('GQ', 'Litoral'), array('GQ', 'Wele-Nzas'), array('GR', 'Aitolia kai Akarnania'), array('GR', 'Akhaia'), array('GR', 'Argolis'), array('GR', 'Arkadhia'), array('GR', 'Arta'), array('GR', 'Attiki'), array('GR', 'Dhodhekanisos'), array('GR', 'Drama'), array('GR', 'Evritania'), array('GR', 'Evros'), array('GR', 'Evvoia'), array('GR', 'Florina'), array('GR', 'Fokis'), array('GR', 'Fthiotis'), array('GR', 'Grevena'), array('GR', 'Ilia'), array('GR', 'Imathia'), array('GR', 'Ioannina'), array('GR', 'Iraklion'), array('GR', 'Kardhitsa'), array('GR', 'Kastoria'), array('GR', 'Kavala'), array('GR', 'Kefallinia'), array('GR', 'Kerkira'), array('GR', 'Khalkidhiki'), array('GR', 'Khania'), array('GR', 'Khios'), array('GR', 'Kikladhes'), array('GR', 'Kilkis'), array('GR', 'Korinthia'), array('GR', 'Kozani'), array('GR', 'Lakonia'), array('GR', 'Larisa'), array('GR', 'Lasithi'), array('GR', 'Lesvos'), array('GR', 'Levkas'), array('GR', 'Magnisia'), array('GR', 'Messinia'), array('GR', 'Pella'), array('GR', 'Pieria'), array('GR', 'Preveza'), array('GR', 'Rethimni'), array('GR', 'Rodhopi'), array('GR', 'Samos'), array('GR', 'Serrai'), array('GR', 'Thesprotia'), array('GR', 'Thessaloniki'), array('GR', 'Trikala'), array('GR', 'Voiotia'), array('GR', 'Xanthi'), array('GR', 'Zakinthos'), array('GS', 'South Georgia and the South Sandwich Islands'), array('GT', 'Alta Verapaz'), array('GT', 'Baja Verapaz'), array('GT', 'Chimaltenango'), array('GT', 'Chiquimula'), array('GT', 'El Progreso'), array('GT', 'Escuintla'), array('GT', 'Guatemala'), array('GT', 'Huehuetenango'), array('GT', 'Izabal'), array('GT', 'Jalapa'), array('GT', 'Jutiapa'), array('GT', 'Peten'), array('GT', 'Quetzaltenango'), array('GT', 'Quiche'), array('GT', 'Retalhuleu'), array('GT', 'Sacatepequez'), array('GT', 'San Marcos'), array('GT', 'Santa Rosa'), array('GT', 'Solola'), array('GT', 'Suchitepequez'), array('GT', 'Totonicapan'), array('GT', 'Zacapa'), array('GU', 'Agana Heights Municipality'), array('GU', 'Agat Municipality'), array('GU', 'Asan-Maina Municipality'), array('GU', 'Barrigada Municipality'), array('GU', 'Chalan Pago-Ordot Municipality'), array('GU', 'Dededo Municipality'), array('GU', 'Hagatna Municipality'), array('GU', 'Inarajan Municipality'), array('GU', 'Mangilao Municipality'), array('GU', 'Merizo Municipality'), array('GU', 'Mongmong-Toto-Maite Municipality'), array('GU', 'Piti Municipality'), array('GU', 'Santa Rita Municipality'), array('GU', 'Sinajana Municipality'), array('GU', 'Talofofo Municipality'), array('GU', 'Tamuning-Tumon-Harmon Municipality'), array('GU', 'Yigo Municipality'), array('GU', 'Yona Municipality'), array('GW', 'Bissau'), array('GW', 'Gabu'), array('GY', 'Demerara-Mahaica'), array('GY', 'East Berbice-Corentyne'), array('GY', 'Essequibo Islands-West Demerara'), array('GY', 'Mahaica-Berbice'), array('GY', 'Upper Demerara-Berbice'), array('HK', 'Hong Kong (SAR)'), array('HN', 'Atlantida'), array('HN', 'Choluteca'), array('HN', 'Colon'), array('HN', 'Comayagua'), array('HN', 'Copan'), array('HN', 'Cortes'), array('HN', 'El Paraiso'), array('HN', 'Francisco Morazan'), array('HN', 'Intibuca'), array('HN', 'Islas de la Bahia'), array('HN', 'La Paz'), array('HN', 'Lempira'), array('HN', 'Ocotepeque'), array('HN', 'Olancho'), array('HN', 'Santa Barbara'), array('HN', 'Valle'), array('HN', 'Yoro'), array('HR', 'Bjelovarsko-bilogorska zupanija'), array('HR', 'Brodsko-posavska zupanija'), array('HR', 'Dubrovacko-neretvanska zupanija'), array('HR', 'Grad Zagreb'), array('HR', 'Istarska zupanija'), array('HR', 'Karlovacka zupanija'), array('HR', 'Koprivnicko-krizevacka zupanija'), array('HR', 'Krapinsko-zagorska zupanija'), array('HR', 'Licko-senjska zupanija'), array('HR', 'Medimurska zupanija'), array('HR', 'Osjecko-baranjska zupanija'), array('HR', 'Pozesko-slavonska zupanija'), array('HR', 'Primorsko-goranska zupanija'), array('HR', 'Sibensko-kninska zupanija'), array('HR', 'Sisacko-moslavacka zupanija'), array('HR', 'Splitsko-dalmatinska zupanija'), array('HR', 'Varazdinska zupanija'), array('HR', 'Viroviticko-podravska zupanija'), array('HR', 'Vukovarsko-srijemska zupanija'), array('HR', 'Zadarska zupanija'), array('HR', 'Zagrebacka zupanija'), array('HT', 'Artibonite'), array('HT', 'Centre'), array('HT', 'Grand\' Anse'), array('HT', 'Nippes'), array('HT', 'Nord'), array('HT', 'Nord-Est'), array('HT', 'Ouest'), array('HT', 'Sud'), array('HT', 'Sud-Est'), array('HU', 'Bacs-Kiskun'), array('HU', 'Baranya'), array('HU', 'Bekes'), array('HU', 'Borsod-Abauj-Zemplen'), array('HU', 'Budapest'), array('HU', 'Csongrad'), array('HU', 'Fejer'), array('HU', 'Gyor-Moson-Sopron'), array('HU', 'Hajdu-Bihar'), array('HU', 'Heves'), array('HU', 'Jasz-Nagykun-Szolnok'), array('HU', 'Komarom-Esztergom'), array('HU', 'Nograd'), array('HU', 'Pest'), array('HU', 'Somogy'), array('HU', 'Szabolcs-Szatmar-Bereg'), array('HU', 'Tolna'), array('HU', 'Vas'), array('HU', 'Veszprem'), array('HU', 'Zala'), array('ID', 'Aceh'), array('ID', 'Bali'), array('ID', 'Bangka Belitung'), array('ID', 'Banten'), array('ID', 'Bengkulu'), array('ID', 'Gorontalo'), array('ID', 'Jakarta Raya'), array('ID', 'Jambi'), array('ID', 'Jawa Barat'), array('ID', 'Jawa Tengah'), array('ID', 'Jawa Timur'), array('ID', 'Kalimantan Barat'), array('ID', 'Kalimantan Selatan'), array('ID', 'Kalimantan Tengah'), array('ID', 'Kalimantan Timur'), array('ID', 'Kepulauan Riau'), array('ID', 'Lampung'), array('ID', 'Maluku'), array('ID', 'Maluku Utara'), array('ID', 'Nusa Tenggara Barat'), array('ID', 'Nusa Tenggara Timur'), array('ID', 'Papua'), array('ID', 'Papua Barat'), array('ID', 'Riau'), array('ID', 'Sulawesi Barat'), array('ID', 'Sulawesi Selatan'), array('ID', 'Sulawesi Tengah'), array('ID', 'Sulawesi Tenggara'), array('ID', 'Sulawesi Utara'), array('ID', 'Sumatera Barat'), array('ID', 'Sumatera Selatan'), array('ID', 'Sumatera Utara'), array('ID', 'Yogyakarta'), array('IE', 'Carlow'), array('IE', 'Cavan'), array('IE', 'Clare'), array('IE', 'Cork'), array('IE', 'Donegal'), array('IE', 'Dublin'), array('IE', 'Galway'), array('IE', 'Kerry'), array('IE', 'Kildare'), array('IE', 'Kilkenny'), array('IE', 'Laois'), array('IE', 'Leitrim'), array('IE', 'Limerick'), array('IE', 'Longford'), array('IE', 'Louth'), array('IE', 'Mayo'), array('IE', 'Meath'), array('IE', 'Monaghan'), array('IE', 'Offaly'), array('IE', 'Roscommon'), array('IE', 'Sligo'), array('IE', 'Tipperary'), array('IE', 'Waterford'), array('IE', 'Westmeath'), array('IE', 'Wexford'), array('IE', 'Wicklow'), array('IL', 'HaDarom'), array('IL', 'HaMerkaz'), array('IL', 'HaTsafon'), array('IL', 'Hefa'), array('IL', 'Tel-Aviv'), array('IL', 'Yerushalayim'), array('IM', 'Isle of Man'), array('IN', 'Andaman and Nicobar Islands'), array('IN', 'Andhra Pradesh'), array('IN', 'Arunachal Pradesh'), array('IN', 'Assam'), array('IN', 'Bihar'), array('IN', 'Chandigarh'), array('IN', 'Chhattisgarh'), array('IN', 'Dadra and Nagar Haveli'), array('IN', 'Daman and Diu'), array('IN', 'Delhi'), array('IN', 'Goa'), array('IN', 'Gujarat'), array('IN', 'Haryana'), array('IN', 'Himachal Pradesh'), array('IN', 'Jammu and Kashmir'), array('IN', 'Jharkhand'), array('IN', 'Karnataka'), array('IN', 'Kerala'), array('IN', 'Lakshadweep'), array('IN', 'Madhya Pradesh'), array('IN', 'Maharashtra'), array('IN', 'Manipur'), array('IN', 'Meghalaya'), array('IN', 'Mizoram'), array('IN', 'Nagaland'), array('IN', 'Odisha'), array('IN', 'Puducherry'), array('IN', 'Punjab'), array('IN', 'Rajasthan'), array('IN', 'Sikkim'), array('IN', 'Tamil Nadu'), array('IN', 'Telangana'), array('IN', 'Tripura'), array('IN', 'Uttar Pradesh'), array('IN', 'Uttarakhand'), array('IN', 'West Bengal'), array('IO', 'British Indian Ocean Territory'), array('IQ', 'Al Anbar'), array('IQ', 'Al Basrah'), array('IQ', 'Al Muthanna'), array('IQ', 'Al Qadisiyah'), array('IQ', 'An Najaf'), array('IQ', 'Arbil'), array('IQ', 'As Sulaymaniyah'), array('IQ', 'Babil'), array('IQ', 'Baghdad'), array('IQ', 'Dahuk'), array('IQ', 'Dhi Qar'), array('IQ', 'Diyala'), array('IQ', 'Karbala\''), array('IQ', 'Kirkuk'), array('IQ', 'Maysan'), array('IQ', 'Ninawa'), array('IQ', 'Salah ad Din'), array('IQ', 'Wasit'), array('IR', 'Alborz'), array('IR', 'Ardabil'), array('IR', 'Azarbayjan-e Gharbi'), array('IR', 'Azarbayjan-e Sharqi'), array('IR', 'Bushehr'), array('IR', 'Chahar Mahal va Bakhtiari'), array('IR', 'Esfahan'), array('IR', 'Fars'), array('IR', 'Gilan'), array('IR', 'Golestan'), array('IR', 'Hamadan'), array('IR', 'Hormozgan'), array('IR', 'Ilam'), array('IR', 'Kerman'), array('IR', 'Kermanshah'), array('IR', 'Khorasan-e Jonubi'), array('IR', 'Khorasan-e Razavi'), array('IR', 'Khorasan-e Shomali'), array('IR', 'Khuzestan'), array('IR', 'Kohgiluyeh va Bowyer Ahmad'), array('IR', 'Kordestan'), array('IR', 'Lorestan'), array('IR', 'Markazi'), array('IR', 'Mazandaran'), array('IR', 'Qazvin'), array('IR', 'Qom'), array('IR', 'Semnan'), array('IR', 'Sistan va Baluchestan'), array('IR', 'Tehran'), array('IR', 'Yazd'), array('IR', 'Zanjan'), array('IS', 'Austurland'), array('IS', 'Hofudborgarsvaedi utan Reykjavikur'), array('IS', 'Nordurland eystra'), array('IS', 'Nordurland vestra'), array('IS', 'Sudurland'), array('IS', 'Sudurnes'), array('IS', 'Vestfirdir'), array('IS', 'Vesturland'), array('IT', 'Abruzzo'), array('IT', 'Basilicata'), array('IT', 'Calabria'), array('IT', 'Campania'), array('IT', 'Emilia-Romagna'), array('IT', 'Friuli-Venezia Giulia'), array('IT', 'Lazio'), array('IT', 'Liguria'), array('IT', 'Lombardia'), array('IT', 'Marche'), array('IT', 'Molise'), array('IT', 'Piemonte'), array('IT', 'Puglia'), array('IT', 'Sardegna'), array('IT', 'Sicilia'), array('IT', 'Toscana'), array('IT', 'Trentino-Alto Adige'), array('IT', 'Umbria'), array('IT', 'Valle d\'Aosta'), array('IT', 'Veneto'), array('JE', 'Jersey'), array('JM', 'Clarendon'), array('JM', 'Hanover'), array('JM', 'Kingston'), array('JM', 'Manchester'), array('JM', 'Portland'), array('JM', 'Saint Andrew'), array('JM', 'Saint Ann'), array('JM', 'Saint Catherine'), array('JM', 'Saint Elizabeth'), array('JM', 'Saint James'), array('JM', 'Saint Mary'), array('JM', 'Saint Thomas'), array('JM', 'Trelawny'), array('JM', 'Westmoreland'), array('JO', 'Al \'Aqabah'), array('JO', 'Al \'Asimah'), array('JO', 'Al Balqa\''), array('JO', 'Al Karak'), array('JO', 'Al Mafraq'), array('JO', 'At Tafilah'), array('JO', 'Az Zarqa\''), array('JO', 'Irbid'), array('JO', 'Ma\'an'), array('JO', 'Madaba'), array('JP', 'Aichi'), array('JP', 'Akita'), array('JP', 'Aomori'), array('JP', 'Chiba'), array('JP', 'Ehime'), array('JP', 'Fukui'), array('JP', 'Fukuoka'), array('JP', 'Fukushima'), array('JP', 'Gifu'), array('JP', 'Gunma'), array('JP', 'Hiroshima'), array('JP', 'Hokkaido'), array('JP', 'Hyogo'), array('JP', 'Ibaraki'), array('JP', 'Ishikawa'), array('JP', 'Iwate'), array('JP', 'Kagawa'), array('JP', 'Kagoshima'), array('JP', 'Kanagawa'), array('JP', 'Kochi'), array('JP', 'Kumamoto'), array('JP', 'Kyoto'), array('JP', 'Mie'), array('JP', 'Miyagi'), array('JP', 'Miyazaki'), array('JP', 'Nagano'), array('JP', 'Nagasaki'), array('JP', 'Nara'), array('JP', 'Niigata'), array('JP', 'Oita'), array('JP', 'Okayama'), array('JP', 'Okinawa'), array('JP', 'Osaka'), array('JP', 'Saga'), array('JP', 'Saitama'), array('JP', 'Shiga'), array('JP', 'Shimane'), array('JP', 'Shizuoka'), array('JP', 'Tochigi'), array('JP', 'Tokushima'), array('JP', 'Tokyo'), array('JP', 'Tottori'), array('JP', 'Toyama'), array('JP', 'Wakayama'), array('JP', 'Yamagata'), array('JP', 'Yamaguchi'), array('JP', 'Yamanashi'), array('KE', 'Central'), array('KE', 'Coast'), array('KE', 'Eastern'), array('KE', 'Nairobi Area'), array('KE', 'North-Eastern'), array('KE', 'Nyanza'), array('KE', 'Rift Valley'), array('KE', 'Western'), array('KG', 'Batken'), array('KG', 'Bishkek'), array('KG', 'Chu'), array('KG', 'Jalal-Abad'), array('KG', 'Naryn'), array('KG', 'Osh'), array('KG', 'Talas'), array('KG', 'Ysyk-Kol'), array('KH', 'Baat Dambang'), array('KH', 'Banteay Mean Chey'), array('KH', 'Kampong Chaam'), array('KH', 'Kampong Chhnang'), array('KH', 'Kampong Spueu'), array('KH', 'Kampong Thum'), array('KH', 'Kampot'), array('KH', 'Kandaal'), array('KH', 'Kaoh Kong'), array('KH', 'Kracheh'), array('KH', 'Krong Kaeb'), array('KH', 'Krong Pailin'), array('KH', 'Krong Preah Sihanouk'), array('KH', 'Mondol Kiri'), array('KH', 'Otdar Mean Chey'), array('KH', 'Phnom Penh'), array('KH', 'Pousaat'), array('KH', 'Preah Vihear'), array('KH', 'Prey Veaeng'), array('KH', 'Rotanak Kiri'), array('KH', 'Siem Reab'), array('KH', 'Stueng Traeng'), array('KH', 'Svaay Rieng'), array('KH', 'Taakaev'), array('KI', 'Gilbert Islands'), array('KM', 'Anjouan'), array('KM', 'Grande Comore'), array('KN', 'Saint George Basseterre'), array('KN', 'Saint Paul Charlestown'), array('KP', 'P\'yongyang'), array('KR', 'Busan'), array('KR', 'Chungbuk'), array('KR', 'Chungnam'), array('KR', 'Daegu'), array('KR', 'Daejeon'), array('KR', 'Gangwon'), array('KR', 'Gwangju'), array('KR', 'Gyeongbuk'), array('KR', 'Gyeonggi'), array('KR', 'Gyeongnam'), array('KR', 'Incheon'), array('KR', 'Jeju'), array('KR', 'Jeonbuk'), array('KR', 'Jeonnam'), array('KR', 'Seoul'), array('KR', 'Ulsan'), array('KW', 'Al Ahmadi'), array('KW', 'Al Asimah'), array('KW', 'Al Farwaniyah'), array('KW', 'Al Jahra'), array('KW', 'Hawalli'), array('KW', 'Mubarak al Kabir'), array('KY', 'Cayman Islands'), array('KZ', 'Almaty'), array('KZ', 'Almaty oblysy'), array('KZ', 'Aqmola oblysy'), array('KZ', 'Aqtobe oblysy'), array('KZ', 'Astana'), array('KZ', 'Atyrau oblysy'), array('KZ', 'Batys Qazaqstan oblysy'), array('KZ', 'Bayqonyr'), array('KZ', 'Mangghystau oblysy'), array('KZ', 'Ongtustik Qazaqstan oblysy'), array('KZ', 'Pavlodar oblysy'), array('KZ', 'Qaraghandy oblysy'), array('KZ', 'Qostanay oblysy'), array('KZ', 'Qyzylorda oblysy'), array('KZ', 'Shyghys Qazaqstan oblysy'), array('KZ', 'Soltustik Qazaqstan oblysy'), array('KZ', 'Zhambyl oblysy'), array('LA', 'Attapu'), array('LA', 'Bolikhamxai'), array('LA', 'Champasak'), array('LA', 'Houaphan'), array('LA', 'Khammouan'), array('LA', 'Louang Namtha'), array('LA', 'Louangphabang'), array('LA', 'Oudomxai'), array('LA', 'Phongsali'), array('LA', 'Savannakhet'), array('LA', 'Vientiane'), array('LA', 'Xaignabouli'), array('LA', 'Xiangkhouang'), array('LB', 'Aakkar'), array('LB', 'Baalbek-Hermel'), array('LB', 'Beqaa'), array('LB', 'Beyrouth'), array('LB', 'Liban-Nord'), array('LB', 'Liban-Sud'), array('LB', 'Mont-Liban'), array('LB', 'Nabatiye'), array('LC', 'Castries'), array('LC', 'Dennery'), array('LC', 'Gros Islet'), array('LC', 'Laborie'), array('LC', 'Soufriere'), array('LC', 'Vieux Fort'), array('LI', 'Balzers'), array('LI', 'Eschen'), array('LI', 'Gamprin'), array('LI', 'Mauren'), array('LI', 'Planken'), array('LI', 'Ruggell'), array('LI', 'Schaan'), array('LI', 'Schellenberg'), array('LI', 'Triesen'), array('LI', 'Triesenberg'), array('LI', 'Vaduz'), array('LK', 'Central Province'), array('LK', 'Eastern Province'), array('LK', 'North Central Province'), array('LK', 'North Western Province'), array('LK', 'Northern Province'), array('LK', 'Sabaragamuwa Province'), array('LK', 'Southern Province'), array('LK', 'Uva Province'), array('LK', 'Western Province'), array('LR', 'Bong'), array('LR', 'Grand Bassa'), array('LR', 'Grand Gedeh'), array('LR', 'Maryland'), array('LR', 'Montserrado'), array('LR', 'Nimba'), array('LR', 'River Gee'), array('LR', 'Sinoe'), array('LS', 'Butha-Buthe'), array('LS', 'Leribe'), array('LS', 'Maseru'), array('LS', 'Mohale\'s Hoek'), array('LS', 'Quthing'), array('LT', 'Alytaus Apskritis'), array('LT', 'Kauno Apskritis'), array('LT', 'Klaipedos Apskritis'), array('LT', 'Marijampoles Apskritis'), array('LT', 'Panevezio Apskritis'), array('LT', 'Siauliu Apskritis'), array('LT', 'Taurages Apskritis'), array('LT', 'Telsiu Apskritis'), array('LT', 'Utenos Apskritis'), array('LT', 'Vilniaus Apskritis'), array('LU', 'Diekirch'), array('LU', 'Grevenmacher'), array('LU', 'Luxembourg'), array('LV', 'Adazu'), array('LV', 'Aglonas'), array('LV', 'Aizkraukles'), array('LV', 'Aizputes'), array('LV', 'Alojas'), array('LV', 'Aluksnes'), array('LV', 'Babites'), array('LV', 'Baltinavas'), array('LV', 'Balvu'), array('LV', 'Bauskas'), array('LV', 'Beverinas'), array('LV', 'Brocenu'), array('LV', 'Carnikavas'), array('LV', 'Cesu'), array('LV', 'Cesvaines'), array('LV', 'Daugavpils'), array('LV', 'Dobeles'), array('LV', 'Dundagas'), array('LV', 'Gulbenes'), array('LV', 'Iecavas'), array('LV', 'Incukalna'), array('LV', 'Jaunjelgavas'), array('LV', 'Jaunpiebalgas'), array('LV', 'Jaunpils'), array('LV', 'Jekabpils'), array('LV', 'Jelgava'), array('LV', 'Jelgavas'), array('LV', 'Jurmala'), array('LV', 'Kekavas'), array('LV', 'Kokneses'), array('LV', 'Kraslavas'), array('LV', 'Kuldigas'), array('LV', 'Liepaja'), array('LV', 'Liepajas'), array('LV', 'Limbazu'), array('LV', 'Lubanas'), array('LV', 'Ludzas'), array('LV', 'Madonas'), array('LV', 'Malpils'), array('LV', 'Ogres'), array('LV', 'Olaines'), array('LV', 'Ozolnieku'), array('LV', 'Preilu'), array('LV', 'Rezeknes'), array('LV', 'Riga'), array('LV', 'Rigas'), array('LV', 'Rojas'), array('LV', 'Salacgrivas'), array('LV', 'Saldus'), array('LV', 'Sejas'), array('LV', 'Siguldas'), array('LV', 'Skrundas'), array('LV', 'Stopinu'), array('LV', 'Talsu'), array('LV', 'Tukuma'), array('LV', 'Valkas'), array('LV', 'Valmieras'), array('LV', 'Vecumnieku'), array('LV', 'Ventspils'), array('LY', 'Al Butnan'), array('LY', 'Al Jabal al Akhdar'), array('LY', 'Al Jabal al Gharbi'), array('LY', 'Al Jafarah'), array('LY', 'Al Jufrah'), array('LY', 'Al Marj'), array('LY', 'Al Marqab'), array('LY', 'Al Wahat'), array('LY', 'An Nuqat al Khams'), array('LY', 'Az Zawiyah'), array('LY', 'Banghazi'), array('LY', 'Darnah'), array('LY', 'Misratah'), array('LY', 'Murzuq'), array('LY', 'Sabha'), array('LY', 'Surt'), array('LY', 'Tarabulus'), array('LY', 'Wadi ash Shati\''), array('MA', 'Chaouia-Ouardigha'), array('MA', 'Doukhala-Abda'), array('MA', 'Fes-Boulemane'), array('MA', 'Gharb-Chrarda-Beni Hssen'), array('MA', 'Grand Casablanca'), array('MA', 'Guelmim-Es Semara'), array('MA', 'L\'Oriental'), array('MA', 'Marrakech-Tensift-Al Haouz'), array('MA', 'Meknes-Tafilalet'), array('MA', 'Rabat-Sale-Zemmour-Zaer'), array('MA', 'Souss-Massa-Draa'), array('MA', 'Tadla-Azilal'), array('MA', 'Tanger-Tetouan'), array('MA', 'Taza-Al Hoceima-Taounate'), array('MC', 'Monaco'), array('MD', 'Anenii Noi'), array('MD', 'Balti'), array('MD', 'Basarabeasca'), array('MD', 'Bender'), array('MD', 'Briceni'), array('MD', 'Cahul'), array('MD', 'Calarasi'), array('MD', 'Cantemir'), array('MD', 'Causeni'), array('MD', 'Chisinau'), array('MD', 'Cimislia'), array('MD', 'Criuleni'), array('MD', 'Donduseni'), array('MD', 'Drochia'), array('MD', 'Dubasari'), array('MD', 'Edinet'), array('MD', 'Falesti'), array('MD', 'Floresti'), array('MD', 'Gagauzia, Unitatea teritoriala autonoma'), array('MD', 'Glodeni'), array('MD', 'Hincesti'), array('MD', 'Ialoveni'), array('MD', 'Leova'), array('MD', 'Nisporeni'), array('MD', 'Ocnita'), array('MD', 'Orhei'), array('MD', 'Rezina'), array('MD', 'Riscani'), array('MD', 'Singerei'), array('MD', 'Soldanesti'), array('MD', 'Soroca'), array('MD', 'Stefan Voda'), array('MD', 'Stinga Nistrului, unitatea teritoriala din'), array('MD', 'Straseni'), array('MD', 'Taraclia'), array('MD', 'Telenesti'), array('MD', 'Ungheni'), array('ME', 'Bar'), array('ME', 'Budva'), array('ME', 'Cetinje'), array('ME', 'Danilovgrad'), array('ME', 'Herceg-Novi'), array('ME', 'Kolasin'), array('ME', 'Kotor'), array('ME', 'Mojkovac'), array('ME', 'Niksic'), array('ME', 'Podgorica'), array('ME', 'Tivat'), array('ME', 'Ulcinj'), array('ME', 'Zabljak'), array('MF', 'Saint Martin'), array('MG', 'Antananarivo'), array('MG', 'Antsiranana'), array('MG', 'Fianarantsoa'), array('MG', 'Mahajanga'), array('MG', 'Toamasina'), array('MG', 'Toliara'), array('MH', 'Majuro Atoll'), array('MK', 'Aracinovo'), array('MK', 'Berovo'), array('MK', 'Bitola'), array('MK', 'Bogdanci'), array('MK', 'Bogovinje'), array('MK', 'Bosilovo'), array('MK', 'Brvenica'), array('MK', 'Centar Zupa'), array('MK', 'Cesinovo-Oblesevo'), array('MK', 'Cucer Sandevo'), array('MK', 'Debar'), array('MK', 'Delcevo'), array('MK', 'Demir Hisar'), array('MK', 'Dojran'), array('MK', 'Dolneni'), array('MK', 'Gevgelija'), array('MK', 'Gostivar'), array('MK', 'Ilinden'), array('MK', 'Kavadarci'), array('MK', 'Kicevo'), array('MK', 'Kocani'), array('MK', 'Kondovo'), array('MK', 'Kratovo'), array('MK', 'Kriva Palanka'), array('MK', 'Krusevo'), array('MK', 'Kumanovo'), array('MK', 'Lipkovo'), array('MK', 'Lozovo'), array('MK', 'Makedonska Kamenica'), array('MK', 'Mavrovo i Rostusa'), array('MK', 'Negotino'), array('MK', 'Novo Selo'), array('MK', 'Ohrid'), array('MK', 'Pehcevo'), array('MK', 'Petrovec'), array('MK', 'Prilep'), array('MK', 'Probistip'), array('MK', 'Radovis'), array('MK', 'Rankovce'), array('MK', 'Resen'), array('MK', 'Rosoman'), array('MK', 'Skopje'), array('MK', 'Sopiste'), array('MK', 'Stip'), array('MK', 'Struga'), array('MK', 'Strumica'), array('MK', 'Studenicani'), array('MK', 'Sveti Nikole'), array('MK', 'Tearce'), array('MK', 'Tetovo'), array('MK', 'Valandovo'), array('MK', 'Vasilevo'), array('MK', 'Veles'), array('MK', 'Vinica'), array('MK', 'Vrapciste'), array('MK', 'Zelenikovo'), array('MK', 'Zelino'), array('ML', 'Bamako'), array('ML', 'Gao'), array('ML', 'Kayes'), array('ML', 'Kidal'), array('ML', 'Koulikoro'), array('ML', 'Mopti'), array('ML', 'Segou'), array('ML', 'Sikasso'), array('ML', 'Tombouctou'), array('MM', 'Ayeyawady'), array('MM', 'Bago'), array('MM', 'Magway'), array('MM', 'Mandalay'), array('MM', 'Mon'), array('MM', 'Nay Pyi Taw'), array('MM', 'Sagaing'), array('MM', 'Shan'), array('MM', 'Yangon'), array('MN', 'Arhangay'), array('MN', 'Bayanhongor'), array('MN', 'Darhan uul'), array('MN', 'Dornod'), array('MN', 'Dornogovi'), array('MN', 'Govi-Altay'), array('MN', 'Hovsgol'), array('MN', 'Omnogovi'), array('MN', 'Orhon'), array('MN', 'Ovorhangay'), array('MN', 'Selenge'), array('MN', 'Tov'), array('MN', 'Ulaanbaatar'), array('MN', 'Uvs'), array('MO', 'Macau'), array('MP', 'Northern Mariana Islands'), array('MQ', 'Martinique'), array('MR', 'Assaba'), array('MR', 'Brakna'), array('MR', 'Dakhlet Nouadhibou'), array('MR', 'Guidimaka'), array('MR', 'Inchiri'), array('MR', 'Nouakchott'), array('MR', 'Tiris Zemmour'), array('MR', 'Trarza'), array('MS', 'Saint Anthony'), array('MS', 'Saint Peter'), array('MT', 'Malta'), array('MU', 'Black River'), array('MU', 'Flacq'), array('MU', 'Grand Port'), array('MU', 'Moka'), array('MU', 'Pamplemousses'), array('MU', 'Plaines Wilhems'), array('MU', 'Port Louis'), array('MU', 'Riviere du Rempart'), array('MU', 'Savanne'), array('MV', 'Alifu'), array('MV', 'Baa'), array('MV', 'Gaafu Dhaalu'), array('MV', 'Haa Alifu'), array('MV', 'Haa Dhaalu'), array('MV', 'Kaafu'), array('MV', 'Laamu'), array('MV', 'Maale'), array('MV', 'Meemu'), array('MV', 'Noonu'), array('MV', 'Raa'), array('MV', 'Seenu'), array('MV', 'Thaa'), array('MW', 'Balaka'), array('MW', 'Blantyre'), array('MW', 'Lilongwe'), array('MW', 'Machinga'), array('MW', 'Mangochi'), array('MW', 'Mzimba'), array('MW', 'Ntchisi'), array('MW', 'Salima'), array('MW', 'Zomba'), array('MX', 'Aguascalientes'), array('MX', 'Baja California'), array('MX', 'Baja California Sur'), array('MX', 'Campeche'), array('MX', 'Chiapas'), array('MX', 'Chihuahua'), array('MX', 'Coahuila'), array('MX', 'Colima'), array('MX', 'Distrito Federal'), array('MX', 'Durango'), array('MX', 'Guanajuato'), array('MX', 'Guerrero'), array('MX', 'Hidalgo'), array('MX', 'Jalisco'), array('MX', 'Mexico'), array('MX', 'Michoacan'), array('MX', 'Morelos'), array('MX', 'Nayarit'), array('MX', 'Nuevo Leon'), array('MX', 'Oaxaca'), array('MX', 'Puebla'), array('MX', 'Queretaro'), array('MX', 'Quintana Roo'), array('MX', 'San Luis Potosi'), array('MX', 'Sinaloa'), array('MX', 'Sonora'), array('MX', 'Tabasco'), array('MX', 'Tamaulipas'), array('MX', 'Tlaxcala'), array('MX', 'Veracruz'), array('MX', 'Yucatan'), array('MX', 'Zacatecas'), array('MY', 'Johor'), array('MY', 'Kedah'), array('MY', 'Kelantan'), array('MY', 'Melaka'), array('MY', 'Negeri Sembilan'), array('MY', 'Pahang'), array('MY', 'Perak'), array('MY', 'Perlis'), array('MY', 'Pulau Pinang'), array('MY', 'Sabah'), array('MY', 'Sarawak'), array('MY', 'Selangor'), array('MY', 'Terengganu'), array('MY', 'Wilayah Persekutuan Kuala Lumpur'), array('MY', 'Wilayah Persekutuan Labuan'), array('MY', 'Wilayah Persekutuan Putrajaya'), array('MZ', 'Cabo Delgado'), array('MZ', 'Gaza'), array('MZ', 'Inhambane'), array('MZ', 'Manica'), array('MZ', 'Maputo'), array('MZ', 'Nampula'), array('MZ', 'Niassa'), array('MZ', 'Sofala'), array('MZ', 'Tete'), array('MZ', 'Zambezia'), array('NA', 'Erongo'), array('NA', 'Hardap'), array('NA', 'Karas'), array('NA', 'Khomas'), array('NA', 'Kunene'), array('NA', 'Ohangwena'), array('NA', 'Okavango'), array('NA', 'Omaheke'), array('NA', 'Omusati'), array('NA', 'Oshana'), array('NA', 'Oshikoto'), array('NA', 'Otjozondjupa'), array('NA', 'Zambezi'), array('NC', 'Province Nord'), array('NC', 'Province Sud'), array('NC', 'Province des iles Loyaute'), array('NE', 'Agadez'), array('NE', 'Diffa'), array('NE', 'Dosso'), array('NE', 'Niamey'), array('NE', 'Tahoua'), array('NE', 'Zinder'), array('NF', 'Norfolk Island'), array('NG', 'Abia'), array('NG', 'Abuja Federal Capital Territory'), array('NG', 'Adamawa'), array('NG', 'Akwa Ibom'), array('NG', 'Anambra'), array('NG', 'Bauchi'), array('NG', 'Bayelsa'), array('NG', 'Benue'), array('NG', 'Borno'), array('NG', 'Cross River'), array('NG', 'Delta'), array('NG', 'Ebonyi'), array('NG', 'Edo'), array('NG', 'Ekiti'), array('NG', 'Enugu'), array('NG', 'Gombe'), array('NG', 'Imo'), array('NG', 'Jigawa'), array('NG', 'Kaduna'), array('NG', 'Kano'), array('NG', 'Katsina'), array('NG', 'Kebbi'), array('NG', 'Kogi'), array('NG', 'Kwara'), array('NG', 'Lagos'), array('NG', 'Nasarawa'), array('NG', 'Niger'), array('NG', 'Ogun'), array('NG', 'Ondo'), array('NG', 'Osun'), array('NG', 'Oyo'), array('NG', 'Plateau'), array('NG', 'Rivers'), array('NG', 'Sokoto'), array('NG', 'Taraba'), array('NG', 'Yobe'), array('NG', 'Zamfara'), array('NI', 'Atlantico Norte'), array('NI', 'Atlantico Sur'), array('NI', 'Boaco'), array('NI', 'Carazo'), array('NI', 'Chinandega'), array('NI', 'Chontales'), array('NI', 'Esteli'), array('NI', 'Granada'), array('NI', 'Jinotega'), array('NI', 'Leon'), array('NI', 'Madriz'), array('NI', 'Managua'), array('NI', 'Masaya'), array('NI', 'Matagalpa'), array('NI', 'Nueva Segovia'), array('NI', 'Rio San Juan'), array('NI', 'Rivas'), array('NL', 'Drenthe'), array('NL', 'Flevoland'), array('NL', 'Fryslan'), array('NL', 'Gelderland'), array('NL', 'Groningen'), array('NL', 'Limburg'), array('NL', 'Noord-Brabant'), array('NL', 'Noord-Holland'), array('NL', 'Overijssel'), array('NL', 'Utrecht'), array('NL', 'Zeeland'), array('NL', 'Zuid-Holland'), array('NO', 'Akershus'), array('NO', 'Aust-Agder'), array('NO', 'Buskerud'), array('NO', 'Finnmark'), array('NO', 'Hedmark'), array('NO', 'Hordaland'), array('NO', 'More og Romsdal'), array('NO', 'Nord-Trondelag'), array('NO', 'Nordland'), array('NO', 'Oppland'), array('NO', 'Oslo'), array('NO', 'Ostfold'), array('NO', 'Rogaland'), array('NO', 'Sogn og Fjordane'), array('NO', 'Sor-Trondelag'), array('NO', 'Telemark'), array('NO', 'Troms'), array('NO', 'Vest-Agder'), array('NO', 'Vestfold'), array('NP', 'Bagmati'), array('NP', 'Bheri'), array('NP', 'Dhawalagiri'), array('NP', 'Gandaki'), array('NP', 'Janakpur'), array('NP', 'Karnali'), array('NP', 'Kosi'), array('NP', 'Lumbini'), array('NP', 'Mahakali'), array('NP', 'Mechi'), array('NP', 'Narayani'), array('NP', 'Rapti'), array('NP', 'Sagarmatha'), array('NP', 'Seti'), array('NR', 'Yaren'), array('NU', 'Niue'), array('NZ', 'Auckland'), array('NZ', 'Bay of Plenty'), array('NZ', 'Canterbury'), array('NZ', 'Gisborne'), array('NZ', 'Hawke\'s Bay'), array('NZ', 'Manawatu-Wanganui'), array('NZ', 'Marlborough'), array('NZ', 'Nelson'), array('NZ', 'Northland'), array('NZ', 'Otago'), array('NZ', 'Southland'), array('NZ', 'Taranaki'), array('NZ', 'Tasman'), array('NZ', 'Waikato'), array('NZ', 'Wellington'), array('NZ', 'West Coast'), array('OM', 'Ad Dakhiliyah'), array('OM', 'Al Buraymi'), array('OM', 'Al Wusta'), array('OM', 'Az Zahirah'), array('OM', 'Janub al Batinah'), array('OM', 'Janub ash Sharqiyah'), array('OM', 'Masqat'), array('OM', 'Musandam'), array('OM', 'Shamal al Batinah'), array('OM', 'Shamal ash Sharqiyah'), array('OM', 'Zufar'), array('PA', 'Bocas del Toro'), array('PA', 'Chiriqui'), array('PA', 'Cocle'), array('PA', 'Colon'), array('PA', 'Darien'), array('PA', 'Herrera'), array('PA', 'Los Santos'), array('PA', 'Panama'), array('PA', 'San Blas'), array('PA', 'Veraguas'), array('PE', 'Amazonas'), array('PE', 'Ancash'), array('PE', 'Apurimac'), array('PE', 'Arequipa'), array('PE', 'Ayacucho'), array('PE', 'Cajamarca'), array('PE', 'Callao'), array('PE', 'Cusco'), array('PE', 'Huancavelica'), array('PE', 'Huanuco'), array('PE', 'Ica'), array('PE', 'Junin'), array('PE', 'La Libertad'), array('PE', 'Lambayeque'), array('PE', 'Lima'), array('PE', 'Loreto'), array('PE', 'Madre de Dios'), array('PE', 'Moquegua'), array('PE', 'Pasco'), array('PE', 'Piura'), array('PE', 'Puno'), array('PE', 'San Martin'), array('PE', 'Tacna'), array('PE', 'Tumbes'), array('PE', 'Ucayali'), array('PF', 'Iles Marquises'), array('PF', 'Iles Sous-le-Vent'), array('PF', 'Iles du Vent'), array('PG', 'East New Britain'), array('PG', 'Enga'), array('PG', 'Gulf'), array('PG', 'Madang'), array('PG', 'Manus'), array('PG', 'Morobe'), array('PG', 'National Capital District'), array('PG', 'New Ireland'), array('PG', 'Southern Highlands'), array('PG', 'Western Highlands'), array('PH', 'Abra'), array('PH', 'Agusan del Norte'), array('PH', 'Agusan del Sur'), array('PH', 'Aklan'), array('PH', 'Albay'), array('PH', 'Antique'), array('PH', 'Apayao'), array('PH', 'Aurora'), array('PH', 'Basilan'), array('PH', 'Bataan'), array('PH', 'Batanes'), array('PH', 'Batangas'), array('PH', 'Benguet'), array('PH', 'Bohol'), array('PH', 'Bukidnon'), array('PH', 'Bulacan'), array('PH', 'Cagayan'), array('PH', 'Camarines Norte'), array('PH', 'Camarines Sur'), array('PH', 'Camiguin'), array('PH', 'Capiz'), array('PH', 'Catanduanes'), array('PH', 'Cavite'), array('PH', 'Cebu'), array('PH', 'Cotabato'), array('PH', 'Davao Oriental'), array('PH', 'Davao del Sur'), array('PH', 'Eastern Samar'), array('PH', 'Ifugao'), array('PH', 'Ilocos Norte'), array('PH', 'Ilocos Sur'), array('PH', 'Iloilo'), array('PH', 'Isabela'), array('PH', 'La Union'), array('PH', 'Laguna'), array('PH', 'Lanao del Norte'), array('PH', 'Lanao del Sur'), array('PH', 'Leyte'), array('PH', 'Maguindanao'), array('PH', 'Marinduque'), array('PH', 'Masbate'), array('PH', 'Mindoro Occidental'), array('PH', 'Mindoro Oriental'), array('PH', 'Misamis Occidental'), array('PH', 'Misamis Oriental'), array('PH', 'Mountain Province'), array('PH', 'National Capital Region'), array('PH', 'Negros Occidental'), array('PH', 'Negros Oriental'), array('PH', 'Northern Samar'), array('PH', 'Nueva Ecija'), array('PH', 'Nueva Vizcaya'), array('PH', 'Palawan'), array('PH', 'Pampanga'), array('PH', 'Pangasinan'), array('PH', 'Quezon'), array('PH', 'Quirino'), array('PH', 'Rizal'), array('PH', 'Romblon'), array('PH', 'Samar (Western Samar)'), array('PH', 'Siquijor'), array('PH', 'Sorsogon'), array('PH', 'South Cotabato'), array('PH', 'Southern Leyte'), array('PH', 'Sultan Kudarat'), array('PH', 'Sulu'), array('PH', 'Surigao del Norte'), array('PH', 'Surigao del Sur'), array('PH', 'Tarlac'), array('PH', 'Tawi-Tawi'), array('PH', 'Zambales'), array('PH', 'Zamboanga del Norte'), array('PH', 'Zamboanga del Sur'), array('PK', 'Azad Kashmir'), array('PK', 'Balochistan'), array('PK', 'Federally Administered Tribal Areas'), array('PK', 'Gilgit-Baltistan'), array('PK', 'Islamabad'), array('PK', 'Khyber Pakhtunkhwa'), array('PK', 'Punjab'), array('PK', 'Sindh'), array('PL', 'Dolnoslaskie'), array('PL', 'Kujawsko-Pomorskie'), array('PL', 'Lodzkie'), array('PL', 'Lubelskie'), array('PL', 'Lubuskie'), array('PL', 'Malopolskie'), array('PL', 'Mazowieckie'), array('PL', 'Opolskie'), array('PL', 'Podkarpackie'), array('PL', 'Podlaskie'), array('PL', 'Pomorskie'), array('PL', 'Slaskie'), array('PL', 'Swietokrzyskie'), array('PL', 'Warminsko-Mazurskie'), array('PL', 'Wielkopolskie'), array('PL', 'Zachodniopomorskie'), array('PM', 'Saint Pierre and Miquelon'), array('PN', 'Pitcairn Islands'), array('PR', 'Adjuntas'), array('PR', 'Aguada'), array('PR', 'Aguadilla'), array('PR', 'Aguas Buenas'), array('PR', 'Aibonito'), array('PR', 'Anasco'), array('PR', 'Arecibo'), array('PR', 'Arroyo'), array('PR', 'Barceloneta'), array('PR', 'Barranquitas'), array('PR', 'Bayamon'), array('PR', 'Cabo Rojo'), array('PR', 'Caguas'), array('PR', 'Camuy'), array('PR', 'Canovanas'), array('PR', 'Carolina'), array('PR', 'Catano'), array('PR', 'Cayey'), array('PR', 'Ceiba'), array('PR', 'Ciales'), array('PR', 'Cidra'), array('PR', 'Coamo'), array('PR', 'Comerio'), array('PR', 'Corozal'), array('PR', 'Culebra'), array('PR', 'Dorado'), array('PR', 'Fajardo'), array('PR', 'Florida'), array('PR', 'Guanica'), array('PR', 'Guayama'), array('PR', 'Guayanilla'), array('PR', 'Guaynabo'), array('PR', 'Gurabo'), array('PR', 'Hatillo'), array('PR', 'Hormigueros'), array('PR', 'Humacao'), array('PR', 'Isabela'), array('PR', 'Juana Diaz'), array('PR', 'Lajas'), array('PR', 'Lares'), array('PR', 'Las Marias'), array('PR', 'Las Piedras'), array('PR', 'Loiza'), array('PR', 'Luquillo'), array('PR', 'Manati'), array('PR', 'Maricao'), array('PR', 'Maunabo'), array('PR', 'Mayaguez'), array('PR', 'Moca'), array('PR', 'Morovis'), array('PR', 'Municipio de Jayuya'), array('PR', 'Municipio de Juncos'), array('PR', 'Naguabo'), array('PR', 'Naranjito'), array('PR', 'Patillas'), array('PR', 'Penuelas'), array('PR', 'Ponce'), array('PR', 'Quebradillas'), array('PR', 'Rincon'), array('PR', 'Rio Grande'), array('PR', 'Sabana Grande'), array('PR', 'Salinas'), array('PR', 'San German'), array('PR', 'San Juan'), array('PR', 'San Lorenzo'), array('PR', 'San Sebastian'), array('PR', 'Santa Isabel Municipio'), array('PR', 'Toa Alta'), array('PR', 'Toa Baja'), array('PR', 'Trujillo Alto'), array('PR', 'Utuado'), array('PR', 'Vega Alta'), array('PR', 'Vega Baja'), array('PR', 'Vieques'), array('PR', 'Villalba'), array('PR', 'Yabucoa'), array('PR', 'Yauco'), array('PS', 'Gaza'), array('PS', 'West Bank'), array('PT', 'Aveiro'), array('PT', 'Beja'), array('PT', 'Braga'), array('PT', 'Braganca'), array('PT', 'Castelo Branco'), array('PT', 'Coimbra'), array('PT', 'Evora'), array('PT', 'Faro'), array('PT', 'Guarda'), array('PT', 'Leiria'), array('PT', 'Lisboa'), array('PT', 'Portalegre'), array('PT', 'Porto'), array('PT', 'Regiao Autonoma da Madeira'), array('PT', 'Regiao Autonoma dos Acores'), array('PT', 'Santarem'), array('PT', 'Setubal'), array('PT', 'Viana do Castelo'), array('PT', 'Vila Real'), array('PT', 'Viseu'), array('PW', 'Airai'), array('PW', 'Koror'), array('PW', 'Melekeok'), array('PW', 'Peleliu'), array('PY', 'Alto Parana'), array('PY', 'Asuncion'), array('PY', 'Boqueron'), array('PY', 'Caaguazu'), array('PY', 'Canindeyu'), array('PY', 'Central'), array('PY', 'Concepcion'), array('PY', 'Cordillera'), array('PY', 'Guaira'), array('PY', 'Itapua'), array('PY', 'Misiones'), array('PY', 'Neembucu'), array('PY', 'Paraguari'), array('PY', 'Presidente Hayes'), array('PY', 'San Pedro'), array('QA', 'Ad Dawhah'), array('QA', 'Al Khawr wa adh Dhakhirah'), array('QA', 'Al Wakrah'), array('QA', 'Ar Rayyan'), array('QA', 'Ash Shamal'), array('QA', 'Az Za\'ayin'), array('QA', 'Umm Salal'), array('RE', 'Reunion'), array('RO', 'Alba'), array('RO', 'Arad'), array('RO', 'Arges'), array('RO', 'Bacau'), array('RO', 'Bihor'), array('RO', 'Bistrita-Nasaud'), array('RO', 'Botosani'), array('RO', 'Braila'), array('RO', 'Brasov'), array('RO', 'Bucuresti'), array('RO', 'Buzau'), array('RO', 'Calarasi'), array('RO', 'Caras-Severin'), array('RO', 'Cluj'), array('RO', 'Constanta'), array('RO', 'Covasna'), array('RO', 'Dambovita'), array('RO', 'Dolj'), array('RO', 'Galati'), array('RO', 'Giurgiu'), array('RO', 'Gorj'), array('RO', 'Harghita'), array('RO', 'Hunedoara'), array('RO', 'Ialomita'), array('RO', 'Iasi'), array('RO', 'Ilfov'), array('RO', 'Maramures'), array('RO', 'Mehedinti'), array('RO', 'Mures'), array('RO', 'Neamt'), array('RO', 'Olt'), array('RO', 'Prahova'), array('RO', 'Salaj'), array('RO', 'Satu Mare'), array('RO', 'Sibiu'), array('RO', 'Suceava'), array('RO', 'Teleorman'), array('RO', 'Timis'), array('RO', 'Tulcea'), array('RO', 'Valcea'), array('RO', 'Vaslui'), array('RO', 'Vrancea'), array('RS', 'Central Serbia'), array('RS', 'Kosovo'), array('RS', 'Vojvodina'), array('RU', 'Adygeya, Respublika'), array('RU', 'Altay, Respublika'), array('RU', 'Altayskiy kray'), array('RU', 'Amurskaya oblast\''), array('RU', 'Arkhangel\'skaya oblast\''), array('RU', 'Astrakhanskaya oblast\''), array('RU', 'Bashkortostan, Respublika'), array('RU', 'Belgorodskaya oblast\''), array('RU', 'Bryanskaya oblast\''), array('RU', 'Buryatiya, Respublika'), array('RU', 'Chechenskaya Respublika'), array('RU', 'Chelyabinskaya oblast\''), array('RU', 'Chukotskiy avtonomnyy okrug'), array('RU', 'Chuvashskaya Respublika'), array('RU', 'Dagestan, Respublika'), array('RU', 'Ingushetiya, Respublika'), array('RU', 'Irkutskaya oblast\''), array('RU', 'Ivanovskaya oblast\''), array('RU', 'Kabardino-Balkarskaya Respublika'), array('RU', 'Kaliningradskaya oblast\''), array('RU', 'Kalmykiya, Respublika'), array('RU', 'Kaluzhskaya oblast\''), array('RU', 'Kamchatskiy kray'), array('RU', 'Karachayevo-Cherkesskaya Respublika'), array('RU', 'Kareliya, Respublika'), array('RU', 'Kemerovskaya oblast\''), array('RU', 'Khabarovskiy kray'), array('RU', 'Khakasiya, Respublika'), array('RU', 'Khanty-Mansiyskiy avtonomnyy okrug-Yugra'), array('RU', 'Kirovskaya oblast\''), array('RU', 'Komi, Respublika'), array('RU', 'Kostromskaya oblast\''), array('RU', 'Krasnodarskiy kray'), array('RU', 'Krasnoyarskiy kray'), array('RU', 'Kurganskaya oblast\''), array('RU', 'Kurskaya oblast\''), array('RU', 'Leningradskaya oblast\''), array('RU', 'Lipetskaya oblast\''), array('RU', 'Magadanskaya oblast\''), array('RU', 'Mariy El, Respublika'), array('RU', 'Mordoviya, Respublika'), array('RU', 'Moskovskaya oblast\''), array('RU', 'Moskva'), array('RU', 'Murmanskaya oblast\''), array('RU', 'Nenetskiy avtonomnyy okrug'), array('RU', 'Nizhegorodskaya oblast\''), array('RU', 'Novgorodskaya oblast\''), array('RU', 'Novosibirskaya oblast\''), array('RU', 'Omskaya oblast\''), array('RU', 'Orenburgskaya oblast\''), array('RU', 'Orlovskaya oblast\''), array('RU', 'Penzenskaya oblast\''), array('RU', 'Permskiy kray'), array('RU', 'Primorskiy kray'), array('RU', 'Pskovskaya oblast\''), array('RU', 'Rostovskaya oblast\''), array('RU', 'Ryazanskaya oblast\''), array('RU', 'Sakha, Respublika'), array('RU', 'Sakhalinskaya oblast\''), array('RU', 'Samarskaya oblast\''), array('RU', 'Sankt-Peterburg'), array('RU', 'Saratovskaya oblast\''), array('RU', 'Severnaya Osetiya-Alaniya, Respublika'), array('RU', 'Smolenskaya oblast\''), array('RU', 'Stavropol\'skiy kray'), array('RU', 'Sverdlovskaya oblast\''), array('RU', 'Tambovskaya oblast\''), array('RU', 'Tatarstan, Respublika'), array('RU', 'Tomskaya oblast\''), array('RU', 'Tul\'skaya oblast\''), array('RU', 'Tverskaya oblast\''), array('RU', 'Tyumenskaya oblast\''), array('RU', 'Tyva, Respublika'), array('RU', 'Udmurtskaya Respublika'), array('RU', 'Ul\'yanovskaya oblast\''), array('RU', 'Vladimirskaya oblast\''), array('RU', 'Volgogradskaya oblast\''), array('RU', 'Vologodskaya oblast\''), array('RU', 'Voronezhskaya oblast\''), array('RU', 'Yamalo-Nenetskiy avtonomnyy okrug'), array('RU', 'Yaroslavskaya oblast\''), array('RU', 'Yevreyskaya avtonomnaya oblast\''), array('RU', 'Zabaykal\'skiy kray'), array('RW', 'Est'), array('RW', 'Nord'), array('RW', 'Ouest'), array('RW', 'Sud'), array('RW', 'Ville de Kigali'), array('SA', '\'Asir'), array('SA', 'Al Bahah'), array('SA', 'Al Hudud ash Shamaliyah'), array('SA', 'Al Jawf'), array('SA', 'Al Madinah al Munawwarah'), array('SA', 'Al Qasim'), array('SA', 'Ar Riyad'), array('SA', 'Ash Sharqiyah'), array('SA', 'Ha\'il'), array('SA', 'Jazan'), array('SA', 'Makkah al Mukarramah'), array('SA', 'Najran'), array('SA', 'Tabuk'), array('SB', 'Guadalcanal'), array('SC', 'English River'), array('SD', 'Blue Nile'), array('SD', 'Gedaref'), array('SD', 'Gezira'), array('SD', 'Kassala'), array('SD', 'Khartoum'), array('SD', 'North Darfur'), array('SD', 'North Kordofan'), array('SD', 'Northern'), array('SD', 'Red Sea'), array('SD', 'River Nile'), array('SD', 'Sennar'), array('SD', 'South Darfur'), array('SD', 'South Kordofan'), array('SD', 'White Nile'), array('SE', 'Blekinge Lan'), array('SE', 'Dalarnas Lan'), array('SE', 'Gavleborgs Lan'), array('SE', 'Gotlands Lan'), array('SE', 'Hallands Lan'), array('SE', 'Jamtlands Lan'), array('SE', 'Jonkopings Lan'), array('SE', 'Kalmar Lan'), array('SE', 'Kronobergs Lan'), array('SE', 'Norrbottens Lan'), array('SE', 'Orebro Lan'), array('SE', 'Ostergotlands Lan'), array('SE', 'Skane Lan'), array('SE', 'Sodermanlands Lan'), array('SE', 'Stockholms Lan'), array('SE', 'Uppsala Lan'), array('SE', 'Varmlands Lan'), array('SE', 'Vasterbottens Lan'), array('SE', 'Vasternorrlands Lan'), array('SE', 'Vastmanlands Lan'), array('SE', 'Vastra Gotaland'), array('SG', 'Singapore'), array('SH', 'Saint Helena'), array('SI', 'Ajdovscina'), array('SI', 'Bled'), array('SI', 'Bohinj'), array('SI', 'Borovnica'), array('SI', 'Bovec'), array('SI', 'Brezice'), array('SI', 'Brezovica'), array('SI', 'Celje'), array('SI', 'Cerknica'), array('SI', 'Cerkno'), array('SI', 'Crensovci'), array('SI', 'Crnomelj'), array('SI', 'Destrnik'), array('SI', 'Divaca'), array('SI', 'Domzale'), array('SI', 'Dravograd'), array('SI', 'Gornja Radgona'), array('SI', 'Grosuplje'), array('SI', 'Hoce-Slivnica'), array('SI', 'Horjul'), array('SI', 'Hrastnik'), array('SI', 'Idrija'), array('SI', 'Ig'), array('SI', 'Ilirska Bistrica'), array('SI', 'Ivancna Gorica'), array('SI', 'Izola-Isola'), array('SI', 'Jesenice'), array('SI', 'Kamnik'), array('SI', 'Kanal'), array('SI', 'Kidricevo'), array('SI', 'Kobarid'), array('SI', 'Kocevje'), array('SI', 'Koper-Capodistria'), array('SI', 'Kranj'), array('SI', 'Kranjska Gora'), array('SI', 'Krsko'), array('SI', 'Lasko'), array('SI', 'Lenart'), array('SI', 'Lendava'), array('SI', 'Litija'), array('SI', 'Ljubljana'), array('SI', 'Ljutomer'), array('SI', 'Log-Dragomer'), array('SI', 'Logatec'), array('SI', 'Lovrenc na Pohorju'), array('SI', 'Maribor'), array('SI', 'Medvode'), array('SI', 'Menges'), array('SI', 'Metlika'), array('SI', 'Mezica'), array('SI', 'Miklavz na Dravskem Polju'), array('SI', 'Miren-Kostanjevica'), array('SI', 'Mislinja'), array('SI', 'Mozirje'), array('SI', 'Murska Sobota'), array('SI', 'Muta'), array('SI', 'Nova Gorica'), array('SI', 'Novo Mesto'), array('SI', 'Odranci'), array('SI', 'Oplotnica'), array('SI', 'Ormoz'), array('SI', 'Piran'), array('SI', 'Pivka'), array('SI', 'Poljcane'), array('SI', 'Polzela'), array('SI', 'Postojna'), array('SI', 'Prebold'), array('SI', 'Prevalje'), array('SI', 'Ptuj'), array('SI', 'Racam'), array('SI', 'Radece'), array('SI', 'Radenci'), array('SI', 'Radlje ob Dravi'), array('SI', 'Radovljica'), array('SI', 'Ravne na Koroskem'), array('SI', 'Ribnica'), array('SI', 'Rogaska Slatina'), array('SI', 'Ruse'), array('SI', 'Sempeter-Vrtojba'), array('SI', 'Sencur'), array('SI', 'Sentilj'), array('SI', 'Sentjur pri Celju'), array('SI', 'Sevnica'), array('SI', 'Sezana'), array('SI', 'Skofja Loka'), array('SI', 'Skofljica'), array('SI', 'Slovenj Gradec'), array('SI', 'Slovenska Bistrica'), array('SI', 'Slovenske Konjice'), array('SI', 'Sostanj'), array('SI', 'Store'), array('SI', 'Straza'), array('SI', 'Tolmin'), array('SI', 'Trbovlje'), array('SI', 'Trebnje'), array('SI', 'Trzic'), array('SI', 'Trzin'), array('SI', 'Turnisce'), array('SI', 'Velenje'), array('SI', 'Vipava'), array('SI', 'Vodice'), array('SI', 'Vojnik'), array('SI', 'Vrhnika'), array('SI', 'Vuzenica'), array('SI', 'Zagorje ob Savi'), array('SI', 'Zalec'), array('SI', 'Zelezniki'), array('SI', 'Ziri'), array('SI', 'Zrece'), array('SI', 'Zuzemberk'), array('SJ', 'Svalbard and Jan Mayen'), array('SK', 'Banskobystricky kraj'), array('SK', 'Bratislavsky kraj'), array('SK', 'Kosicky kraj'), array('SK', 'Nitriansky kraj'), array('SK', 'Presovsky kraj'), array('SK', 'Trenciansky kraj'), array('SK', 'Trnavsky kraj'), array('SK', 'Zilinsky kraj'), array('SL', 'Eastern'), array('SL', 'Northern'), array('SL', 'Western Area'), array('SM', 'San Marino'), array('SM', 'Serravalle'), array('SN', 'Dakar'), array('SN', 'Diourbel'), array('SN', 'Fatick'), array('SN', 'Kaffrine'), array('SN', 'Kaolack'), array('SN', 'Kedougou'), array('SN', 'Kolda'), array('SN', 'Louga'), array('SN', 'Matam'), array('SN', 'Saint-Louis'), array('SN', 'Tambacounda'), array('SN', 'Thies'), array('SN', 'Ziguinchor'), array('SO', 'Banaadir'), array('SO', 'Jubbada Hoose'), array('SO', 'Mudug'), array('SO', 'Nugaal'), array('SO', 'Togdheer'), array('SO', 'Woqooyi Galbeed'), array('SR', 'Commewijne'), array('SR', 'Nickerie'), array('SR', 'Para'), array('SR', 'Paramaribo'), array('SR', 'Saramacca'), array('SR', 'Wanica'), array('SS', 'Central Equatoria'), array('SS', 'Eastern Equatoria'), array('SS', 'Lakes'), array('SS', 'Unity'), array('SS', 'Upper Nile'), array('SS', 'Western Equatoria'), array('ST', 'Principe'), array('ST', 'Sao Tome'), array('SV', 'Ahuachapan'), array('SV', 'Cabanas'), array('SV', 'Chalatenango'), array('SV', 'Cuscatlan'), array('SV', 'La Libertad'), array('SV', 'La Paz'), array('SV', 'La Union'), array('SV', 'Morazan'), array('SV', 'San Miguel'), array('SV', 'San Salvador'), array('SV', 'San Vicente'), array('SV', 'Santa Ana'), array('SV', 'Sonsonate'), array('SV', 'Usulutan'), array('SX', 'Sint Maarten'), array('SY', 'Al Hasakah'), array('SY', 'Al Ladhiqiyah'), array('SY', 'Ar Raqqah'), array('SY', 'As Suwayda\''), array('SY', 'Dar\'a'), array('SY', 'Dimashq'), array('SY', 'Halab'), array('SY', 'Hamah'), array('SY', 'Hims'), array('SY', 'Idlib'), array('SY', 'Rif Dimashq'), array('SY', 'Tartus'), array('SZ', 'Hhohho'), array('SZ', 'Lubombo'), array('SZ', 'Manzini'), array('TC', 'Turks and Caicos Islands'), array('TD', 'Chari-Baguirmi'), array('TD', 'Guera'), array('TD', 'Hadjer Lamis'), array('TD', 'Kanem'), array('TD', 'Logone-Occidental'), array('TD', 'Mayo-Kebbi-Est'), array('TD', 'Ouaddai'), array('TG', 'Kara'), array('TG', 'Maritime'), array('TG', 'Plateaux'), array('TH', 'Amnat Charoen'), array('TH', 'Ang Thong'), array('TH', 'Buri Ram'), array('TH', 'Chachoengsao'), array('TH', 'Chai Nat'), array('TH', 'Chaiyaphum'), array('TH', 'Chanthaburi'), array('TH', 'Chiang Mai'), array('TH', 'Chiang Rai'), array('TH', 'Chon Buri'), array('TH', 'Chumphon'), array('TH', 'Kalasin'), array('TH', 'Kamphaeng Phet'), array('TH', 'Kanchanaburi'), array('TH', 'Khon Kaen'), array('TH', 'Krabi'), array('TH', 'Krung Thep Maha Nakhon'), array('TH', 'Lampang'), array('TH', 'Lamphun'), array('TH', 'Loei'), array('TH', 'Lop Buri'), array('TH', 'Mae Hong Son'), array('TH', 'Maha Sarakham'), array('TH', 'Mukdahan'), array('TH', 'Nakhon Nayok'), array('TH', 'Nakhon Pathom'), array('TH', 'Nakhon Phanom'), array('TH', 'Nakhon Ratchasima'), array('TH', 'Nakhon Sawan'), array('TH', 'Nakhon Si Thammarat'), array('TH', 'Nan'), array('TH', 'Narathiwat'), array('TH', 'Nong Bua Lam Phu'), array('TH', 'Nong Khai'), array('TH', 'Nonthaburi'), array('TH', 'Pathum Thani'), array('TH', 'Pattani'), array('TH', 'Phangnga'), array('TH', 'Phatthalung'), array('TH', 'Phayao'), array('TH', 'Phetchabun'), array('TH', 'Phetchaburi'), array('TH', 'Phichit'), array('TH', 'Phitsanulok'), array('TH', 'Phra Nakhon Si Ayutthaya'), array('TH', 'Phrae'), array('TH', 'Phuket'), array('TH', 'Prachin Buri'), array('TH', 'Prachuap Khiri Khan'), array('TH', 'Ranong'), array('TH', 'Ratchaburi'), array('TH', 'Rayong'), array('TH', 'Roi Et'), array('TH', 'Sa Kaeo'), array('TH', 'Sakon Nakhon'), array('TH', 'Samut Prakan'), array('TH', 'Samut Sakhon'), array('TH', 'Samut Songkhram'), array('TH', 'Saraburi'), array('TH', 'Satun'), array('TH', 'Si Sa Ket'), array('TH', 'Sing Buri'), array('TH', 'Songkhla'), array('TH', 'Sukhothai'), array('TH', 'Suphan Buri'), array('TH', 'Surat Thani'), array('TH', 'Surin'), array('TH', 'Tak'), array('TH', 'Trang'), array('TH', 'Trat'), array('TH', 'Ubon Ratchathani'), array('TH', 'Udon Thani'), array('TH', 'Uthai Thani'), array('TH', 'Uttaradit'), array('TH', 'Yala'), array('TH', 'Yasothon'), array('TJ', 'Khatlon'), array('TJ', 'Kuhistoni Badakhshon'), array('TJ', 'Regions of Republican Subordination'), array('TJ', 'Sughd'), array('TJ', 'Tajikistan'), array('TK', 'Tokelau'), array('TL', 'Dili'), array('TM', 'Ahal'), array('TM', 'Balkan'), array('TM', 'Lebap'), array('TM', 'Mary'), array('TN', 'Aiana'), array('TN', 'Al Mahdia'), array('TN', 'Al Munastir'), array('TN', 'Bajah'), array('TN', 'Ben Arous'), array('TN', 'Bizerte'), array('TN', 'El Kef'), array('TN', 'Gabes'), array('TN', 'Gafsa'), array('TN', 'Jendouba'), array('TN', 'Kairouan'), array('TN', 'Kasserine'), array('TN', 'Kebili'), array('TN', 'Madanin'), array('TN', 'Manouba'), array('TN', 'Nabeul'), array('TN', 'Sfax'), array('TN', 'Sidi Bou Zid'), array('TN', 'Siliana'), array('TN', 'Sousse'), array('TN', 'Tataouine'), array('TN', 'Tozeur'), array('TN', 'Tunis'), array('TN', 'Zaghouan'), array('TO', 'Tongatapu'), array('TO', 'Vava\'u'), array('TR', 'Adana'), array('TR', 'Adiyaman'), array('TR', 'Afyonkarahisar'), array('TR', 'Agri'), array('TR', 'Aksaray'), array('TR', 'Amasya'), array('TR', 'Ankara'), array('TR', 'Antalya'), array('TR', 'Ardahan'), array('TR', 'Artvin'), array('TR', 'Aydin'), array('TR', 'Balikesir'), array('TR', 'Bartin'), array('TR', 'Batman'), array('TR', 'Bayburt'), array('TR', 'Bilecik'), array('TR', 'Bingol'), array('TR', 'Bitlis'), array('TR', 'Bolu'), array('TR', 'Burdur'), array('TR', 'Bursa'), array('TR', 'Canakkale'), array('TR', 'Cankiri'), array('TR', 'Corum'), array('TR', 'Denizli'), array('TR', 'Diyarbakir'), array('TR', 'Duzce'), array('TR', 'Edirne'), array('TR', 'Elazig'), array('TR', 'Erzincan'), array('TR', 'Erzurum'), array('TR', 'Eskisehir'), array('TR', 'Gaziantep'), array('TR', 'Giresun'), array('TR', 'Gumushane'), array('TR', 'Hakkari'), array('TR', 'Hatay'), array('TR', 'Igdir'), array('TR', 'Isparta'), array('TR', 'Istanbul'), array('TR', 'Izmir'), array('TR', 'Kahramanmaras'), array('TR', 'Karabuk'), array('TR', 'Karaman'), array('TR', 'Kars'), array('TR', 'Kastamonu'), array('TR', 'Kayseri'), array('TR', 'Kilis'), array('TR', 'Kirikkale'), array('TR', 'Kirklareli'), array('TR', 'Kirsehir'), array('TR', 'Kocaeli'), array('TR', 'Konya'), array('TR', 'Kutahya'), array('TR', 'Malatya'), array('TR', 'Manisa'), array('TR', 'Mardin'), array('TR', 'Mersin'), array('TR', 'Mugla'), array('TR', 'Mus'), array('TR', 'Nevsehir'), array('TR', 'Nigde'), array('TR', 'Ordu'), array('TR', 'Osmaniye'), array('TR', 'Rize'), array('TR', 'Sakarya'), array('TR', 'Samsun'), array('TR', 'Sanliurfa'), array('TR', 'Siirt'), array('TR', 'Sinop'), array('TR', 'Sirnak'), array('TR', 'Sivas'), array('TR', 'Tekirdag'), array('TR', 'Tokat'), array('TR', 'Trabzon'), array('TR', 'Tunceli'), array('TR', 'Usak'), array('TR', 'Van'), array('TR', 'Yalova'), array('TR', 'Yozgat'), array('TR', 'Zonguldak'), array('TT', 'Arima'), array('TT', 'Caroni'), array('TT', 'Mayaro'), array('TT', 'Port-of-Spain'), array('TT', 'Saint Andrew'), array('TT', 'Saint George'), array('TT', 'San Fernando'), array('TT', 'Tobago'), array('TT', 'Trinidad and Tobago'), array('TT', 'Victoria'), array('TV', 'Tuvalu'), array('TW', 'Fu-chien'), array('TW', 'Kao-hsiung'), array('TW', 'T\'ai-pei'), array('TW', 'T\'ai-wan'), array('TZ', 'Arusha'), array('TZ', 'Dar es Salaam'), array('TZ', 'Dodoma'), array('TZ', 'Iringa'), array('TZ', 'Kagera'), array('TZ', 'Kaskazini Unguja'), array('TZ', 'Kigoma'), array('TZ', 'Kilimanjaro'), array('TZ', 'Kusini Pemba'), array('TZ', 'Kusini Unguja'), array('TZ', 'Lindi'), array('TZ', 'Manyara'), array('TZ', 'Mara'), array('TZ', 'Mbeya'), array('TZ', 'Mjini Magharibi'), array('TZ', 'Morogoro'), array('TZ', 'Mtwara'), array('TZ', 'Mwanza'), array('TZ', 'Pwani'), array('TZ', 'Rukwa'), array('TZ', 'Ruvuma'), array('TZ', 'Shinyanga'), array('TZ', 'Singida'), array('TZ', 'Tabora'), array('TZ', 'Tanga'), array('UA', 'Avtonomna Respublika Krym'), array('UA', 'Cherkas\'ka Oblast\''), array('UA', 'Chernihivs\'ka Oblast\''), array('UA', 'Chernivets\'ka Oblast\''), array('UA', 'Dnipropetrovs\'ka Oblast\''), array('UA', 'Donets\'ka Oblast\''), array('UA', 'Ivano-Frankivs\'ka Oblast\''), array('UA', 'Kharkivs\'ka Oblast\''), array('UA', 'Khersons\'ka Oblast\''), array('UA', 'Khmel\'nyts\'ka Oblast\''), array('UA', 'Kirovohrads\'ka Oblast\''), array('UA', 'Kyiv'), array('UA', 'Kyivs\'ka Oblast\''), array('UA', 'L\'vivs\'ka Oblast\''), array('UA', 'Luhans\'ka Oblast\''), array('UA', 'Mykolaivs\'ka Oblast\''), array('UA', 'Odes\'ka Oblast\''), array('UA', 'Poltavs\'ka Oblast\''), array('UA', 'Rivnens\'ka Oblast\''), array('UA', 'Sevastopol\''), array('UA', 'Sums\'ka Oblast\''), array('UA', 'Ternopil\'s\'ka Oblast\''), array('UA', 'Vinnyts\'ka Oblast\''), array('UA', 'Volyns\'ka Oblast\''), array('UA', 'Zakarpats\'ka Oblast\''), array('UA', 'Zaporiz\'ka Oblast\''), array('UA', 'Zhytomyrs\'ka Oblast\''), array('UG', 'Bugiri'), array('UG', 'Gulu'), array('UG', 'Hoima'), array('UG', 'Jinja'), array('UG', 'Kabale'), array('UG', 'Kampala'), array('UG', 'Kamwenge'), array('UG', 'Kasese'), array('UG', 'Lira'), array('UG', 'Masaka'), array('UG', 'Mbale'), array('UG', 'Mbarara'), array('UG', 'Mityana'), array('UG', 'Moyo'), array('UG', 'Mukono'), array('UG', 'Tororo'), array('UG', 'Wakiso'), array('UM', 'Palmyra Atoll'), array('US', 'Alabama'), array('US', 'Alaska'), array('US', 'Arizona'), array('US', 'Arkansas'), array('US', 'California'), array('US', 'Colorado'), array('US', 'Connecticut'), array('US', 'Delaware'), array('US', 'District Of Columbia'), array('US', 'Florida'), array('US', 'Georgia'), array('US', 'Hawaii'), array('US', 'Idaho'), array('US', 'Illinois'), array('US', 'Indiana'), array('US', 'Iowa'), array('US', 'Kansas'), array('US', 'Kentucky'), array('US', 'Louisiana'), array('US', 'Maine'), array('US', 'Maryland'), array('US', 'Massachusetts'), array('US', 'Michigan'), array('US', 'Minnesota'), array('US', 'Mississippi'), array('US', 'Missouri'), array('US', 'Montana'), array('US', 'Nebraska'), array('US', 'Nevada'), array('US', 'New Hampshire'), array('US', 'New Jersey'), array('US', 'New Mexico'), array('US', 'New York'), array('US', 'North Carolina'), array('US', 'North Dakota'), array('US', 'Ohio'), array('US', 'Oklahoma'), array('US', 'Oregon'), array('US', 'Pennsylvania'), array('US', 'Rhode Island'), array('US', 'South Carolina'), array('US', 'South Dakota'), array('US', 'Tennessee'), array('US', 'Texas'), array('US', 'Utah'), array('US', 'Vermont'), array('US', 'Virginia'), array('US', 'Washington'), array('US', 'West Virginia'), array('US', 'Wisconsin'), array('US', 'Wyoming'), array('UY', 'Artigas'), array('UY', 'Canelones'), array('UY', 'Cerro Largo'), array('UY', 'Colonia'), array('UY', 'Durazno'), array('UY', 'Flores'), array('UY', 'Florida'), array('UY', 'Lavalleja'), array('UY', 'Maldonado'), array('UY', 'Montevideo'), array('UY', 'Paysandu'), array('UY', 'Rio Negro'), array('UY', 'Rivera'), array('UY', 'Rocha'), array('UY', 'Salto'), array('UY', 'San Jose'), array('UY', 'Soriano'), array('UY', 'Tacuarembo'), array('UY', 'Treinta y Tres'), array('UZ', 'Andijon'), array('UZ', 'Buxoro'), array('UZ', 'Farg\'ona'), array('UZ', 'Jizzax'), array('UZ', 'Namangan'), array('UZ', 'Navoiy'), array('UZ', 'Qashqadaryo'), array('UZ', 'Qoraqalpog\'iston Respublikasi'), array('UZ', 'Samarqand'), array('UZ', 'Sirdaryo'), array('UZ', 'Surxondaryo'), array('UZ', 'Toshkent'), array('UZ', 'Xorazm'), array('VA', 'Vatican City'), array('VC', 'Charlotte'), array('VC', 'Saint George'), array('VE', 'Amazonas'), array('VE', 'Anzoategui'), array('VE', 'Apure'), array('VE', 'Aragua'), array('VE', 'Barinas'), array('VE', 'Bolivar'), array('VE', 'Carabobo'), array('VE', 'Cojedes'), array('VE', 'Delta Amacuro'), array('VE', 'Distrito Federal'), array('VE', 'Falcon'), array('VE', 'Guarico'), array('VE', 'Lara'), array('VE', 'Merida'), array('VE', 'Miranda'), array('VE', 'Monagas'), array('VE', 'Nueva Esparta'), array('VE', 'Portuguesa'), array('VE', 'Sucre'), array('VE', 'Tachira'), array('VE', 'Trujillo'), array('VE', 'Vargas'), array('VE', 'Yaracuy'), array('VE', 'Zulia'), array('VG', 'British Virgin Islands'), array('VI', 'Virgin Islands'), array('VN', 'An Giang'), array('VN', 'Bac Giang'), array('VN', 'Bac Kan'), array('VN', 'Bac Lieu'), array('VN', 'Bac Ninh'), array('VN', 'Ben Tre'), array('VN', 'Binh Dinh'), array('VN', 'Binh Duong'), array('VN', 'Binh Phuoc'), array('VN', 'Binh Thuan'), array('VN', 'Ca Mau'), array('VN', 'Can Tho'), array('VN', 'Cao Bang'), array('VN', 'Da Nang'), array('VN', 'Dak Lak'), array('VN', 'Dien Bien'), array('VN', 'Dong Nai'), array('VN', 'Dong Thap'), array('VN', 'Gia Lai'), array('VN', 'Ha Giang'), array('VN', 'Ha Nam'), array('VN', 'Ha Noi'), array('VN', 'Ha Tinh'), array('VN', 'Hai Duong'), array('VN', 'Hai Phong'), array('VN', 'Ho Chi Minh'), array('VN', 'Hoa Binh'), array('VN', 'Hung Yen'), array('VN', 'Khanh Hoa'), array('VN', 'Kien Giang'), array('VN', 'Lai Chau'), array('VN', 'Lam Dong'), array('VN', 'Lang Son'), array('VN', 'Lao Cai'), array('VN', 'Long An'), array('VN', 'Nam Dinh'), array('VN', 'Nghe An'), array('VN', 'Ninh Binh'), array('VN', 'Ninh Thuan'), array('VN', 'Phu Tho'), array('VN', 'Phu Yen'), array('VN', 'Quang Binh'), array('VN', 'Quang Nam'), array('VN', 'Quang Ngai'), array('VN', 'Quang Ninh'), array('VN', 'Quang Tri'), array('VN', 'Soc Trang'), array('VN', 'Son La'), array('VN', 'Tay Ninh'), array('VN', 'Thai Binh'), array('VN', 'Thai Nguyen'), array('VN', 'Thanh Hoa'), array('VN', 'Thua Thien-Hue'), array('VN', 'Tien Giang'), array('VN', 'Tra Vinh'), array('VN', 'Tuyen Quang'), array('VN', 'Vinh Long'), array('VN', 'Vinh Phuc'), array('VN', 'Yen Bai'), array('VU', 'Sanma'), array('VU', 'Shefa'), array('VU', 'Tafea'), array('WF', 'Wallis and Futuna Islands'), array('WS', 'A\'ana'), array('WS', 'Tuamasaga'), array('YE', '\'Adan'), array('YE', 'Amanat al \'Asimah'), array('YE', 'Hadramawt'), array('YE', 'Lahij'), array('YE', 'Shabwah'), array('YE', 'Ta\'izz'), array('YT', 'Bandraboua'), array('YT', 'Chiconi'), array('YT', 'Dzaoudzi'), array('YT', 'Mamoudzou'), array('YT', 'Tsingoni'), array('ZA', 'Eastern Cape'), array('ZA', 'Free State'), array('ZA', 'Gauteng'), array('ZA', 'KwaZulu-Natal'), array('ZA', 'Limpopo'), array('ZA', 'Mpumalanga'), array('ZA', 'North-West'), array('ZA', 'Northern Cape'), array('ZA', 'Western Cape'), array('ZM', 'Central'), array('ZM', 'Copperbelt'), array('ZM', 'Eastern'), array('ZM', 'Luapula'), array('ZM', 'Lusaka'), array('ZM', 'North-Western'), array('ZM', 'Northern'), array('ZM', 'Southern'), array('ZM', 'Western'), array('ZW', 'Bulawayo'), array('ZW', 'Harare'), array('ZW', 'Manicaland'), array('ZW', 'Mashonaland Central'), array('ZW', 'Mashonaland East'), array('ZW', 'Mashonaland West'), array('ZW', 'Masvingo'), array('ZW', 'Matabeleland North'), array('ZW', 'Matabeleland South'), array('ZW', 'Midlands'));

		$this->db->query('CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . "ip2location_redirector` (
	`rule_id` INT(11) NOT NULL AUTO_INCREMENT, `origins` TEXT NOT NULL, `mode` CHAR(1) NOT NULL DEFAULT '1', `from` TEXT NOT NULL, `to` TEXT NOT NULL, `code` INT(11) NOT NULL DEFAULT '301', `status` TINYINT(1) NOT NULL DEFAULT '0', PRIMARY KEY (`rule_id`), INDEX `idx_status` (`status`))");

		$this->db->query('CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'ip2location_country` (`country_code` CHAR(2) NOT NULL, `country_name` VARCHAR(50) NOT NULL, PRIMARY KEY (`country_code`))');

		foreach ($countries as $code => $name) {
			$this->db->query('INSERT INTO `' . DB_PREFIX . "ip2location_country` VALUES ('" . $code . "', '" . addslashes($name) . "')");
		}

		$this->db->query('CREATE TABLE IF NOT EXISTS `' . DB_PREFIX . 'ip2location_region` (`country_code` CHAR(2) NOT NULL, `region_name` VARCHAR(100) NOT NULL, INDEX `idx_country_code` (`country_code`))');

		$marker = '';
		foreach ($regions as $row) {
			if ($marker != $row[0]) {
				$marker = $row[0];
				$this->db->query('INSERT INTO `' . DB_PREFIX . "ip2location_region` VALUES ('" . $row[0] . "', '*')");
			}

			$this->db->query('INSERT INTO `' . DB_PREFIX . "ip2location_region` VALUES ('" . $row[0] . "', '" . addslashes($row[1]) . "')");
		}

		// Backup current catalog.php
		copy(DIR_CONFIG . 'catalog.php', DIR_CONFIG . 'catalog.php.bak');

		if (is_writable(DIR_CONFIG . 'catalog.php')) {
			$content = file_get_contents(DIR_CONFIG . 'catalog.php');
			$content = preg_replace('/\'startup\/seo_url\'/', "'startup/seo_url',\r\n\t'startup/ip2location_redirector'", $content);
			file_put_contents(DIR_CONFIG . 'catalog.php', $content);
		}
	}

	public function uninstall()
	{
		$this->load->model('setting/setting');

		$tables = array(
			'ip2location_redirector',
			'ip2location_country',
			'ip2location_region',
		);

		foreach ($tables as $table) {
			$this->db->query('DROP TABLE IF EXISTS `' . DB_PREFIX . $table . '`');
		}

		$this->model_setting_setting->deleteSetting('ip2location');

		if (file_exists(DIR_CONFIG . 'catalog.php.bak')) {
			$content = file_get_contents(DIR_CONFIG . 'catalog.php.bak');
			file_put_contents(DIR_CONFIG . 'catalog.php', $content);
		}
	}

	public function index()
	{
		$this->load->model('setting/setting');
		$this->load->language('extension/module/ip2location_redirector');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->initPage();
		$this->initModuleStatus();
		$this->initRules();
		$this->initSettings();
		$this->initLookup();

		$this->response->setOutput($this->load->view('extension/module/ip2location_redirector', $this->data));
	}

	protected function checkPermission()
	{
		if (!$this->user->hasPermission('modify', 'extension/module/ip2location_redirector')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function initModuleStatus()
	{
		$this->data['module_ip2location_redirector_status'] = (isset($this->request->post['module_ip2location_redirector_status'])) ? $this->request->post['module_ip2location_redirector_status'] : $this->config->get('module_ip2location_redirector_status');

		if (isset($this->request->post['module_ip2location_redirector_status']) && $this->checkPermission()) {
			$this->model_setting_setting->editSetting('module_ip2location_redirector', $this->request->post);

			if ($this->request->post['module_ip2location_redirector_status'] == '1') {
				$this->data['success'] = $this->language->get('text_module_enabled');
			} else {
				$this->data['success'] = $this->language->get('text_module_disabled');
			}
		}
	}

	protected function initPage()
	{
		$this->document->addScript('view/javascript/ip2location-redirector/chosen.jquery.min.js');
		$this->document->addScript('view/javascript/ip2location-redirector/script.js');

		$this->document->addStyle('view/stylesheet/ip2location-redirector/chosen.min.css');
		$this->document->addStyle('https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.5.0/css/flag-icon.min.css');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true),
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/ip2location_redirector', 'user_token=' . $this->session->data['user_token'], true),
		);

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		$this->data['route'] = $this->request->get['route'];
		$this->data['action'] = $this->url->link($this->request->get['route'], array(
			'user_token' => $this->session->data['user_token'],
		));;
		$this->data['user_token'] = $this->session->data['user_token'];

		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		$this->data['codes'] = array(
			'301' => array('code' => 301, 'text' => $this->language->get('text_code_301')),
			'302' => array('code' => 302, 'text' => $this->language->get('text_code_302')),
			'404' => array('code' => 404, 'text' => $this->language->get('text_code_404')),
		);
	}

	protected function validateSettings()
	{
		if (!$this->checkPermission()) {
			return false;
		}

		if ($this->data['settings']['ip2location_lookup_method'] == '0' && !file_exists($this->data['settings']['ip2location_database_location'])) {
			$this->data['settings_error'] = $this->language->get('error_database_not_found');

			return false;
		} elseif ($this->data['settings']['ip2location_lookup_method'] == '1') {
			if (empty($this->data['settings']['ip2location_api_key'])) {
				$this->data['settings_error'] = $this->language->get('error_api_key_empty');

				return false;
			}
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_HEADER         => 0,
				CURLOPT_URL            => 'https://api.ip2location.com/v2/?check=1&key=' . $this->data['settings']['ip2location_api_key'],
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_TIMEOUT        => 10,
				CURLOPT_SSL_VERIFYPEER => 0,
			));

			$response = curl_exec($ch);
			curl_close($ch);

			$json = json_decode($response);

			if (!preg_match('/^\d+$/', $json->response)) {
				$this->data['settings_error'] = $this->language->get('error_api_key_invalid');

				return false;
			}
		}

		return true;
	}

	protected function validateRules() {
		if (empty($this->data['new_origins'])) {
			$this->data['rules_error'] = $this->language->get('error_empty_origins');
			return false;
		}

		if (empty($this->data['new_from'])) {
			$this->data['rules_error'] = $this->language->get('error_empty_from');
			return false;
		}

		if ($this->data['new_condition'] == '2' && @preg_match('/' . $this->data['new_from'] . '/', sha1(microtime())) === false) {
			$this->data['rules_error'] = $this->language->get('error_invalid_regular_expression');
			return false;
		}

		if (empty($this->data['new_to']) && $this->data['new_code'] != '404') {
			$this->data['rules_error'] = $this->language->get('error_empty_to');
			return false;
		}

		return true;
	}

	protected function initSettings()
	{
		$this->data['settings'] = array(
			'ip2location_lookup_method'     => 0,
			'ip2location_database_location' => '',
			'ip2location_api_key'           => '',
		);

		$this->data['ws_credit'] = '';

		$settings = $this->model_setting_setting->getSetting('ip2location');

		foreach ($settings as $key => $value) {
			$this->data['settings'][$key] = $value;
		}

		if (isset($this->request->post['method'])) {
			$this->data['settings']['ip2location_lookup_method'] = $this->request->post['method'];
		}

		if (isset($this->request->post['database'])) {
			$this->data['settings']['ip2location_database_location'] = $this->request->post['database'];
		}

		if (isset($this->request->post['apiKey'])) {
			$this->data['settings']['ip2location_api_key'] = $this->request->post['apiKey'];
		}

		if (isset($this->request->post['method'])) {
			$this->data['scripts'] = '
			$(\'html, body\').animate({
				scrollTop: $(\'#settings-section\').offset().top
			}, 0);';

			if ($this->validateSettings()) {
				$this->model_setting_setting->editSetting('ip2location', $this->data['settings']);
				$this->data['settings_success'] = $this->language->get('text_success_save_settings');
			}
		}

		if ($this->data['settings']['ip2location_lookup_method'] == 0 && file_exists($this->data['settings']['ip2location_database_location'])) {
			require_once DIR_SYSTEM . 'library/ip2location/class.IP2Location.php';
			$geolocation = new \IP2Location\Database($this->data['settings']['ip2location_database_location'], \IP2Location\Database::FILE_IO);
			$records = $geolocation->lookup('8.8.8.8', \IP2Location\Database::ALL);

			if ($records['countryCode'] != '-') {
				$this->databaseFound = true;
			}

			if ($records['regionName'] != '-' && !preg_match('/unavailable/', $records['regionName'])) {
				$this->isRegionSupported = true;
			}
		} elseif ($this->data['settings']['ip2location_lookup_method'] == 1 && $this->data['settings']['ip2location_api_key']) {
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_HEADER         => 0,
				CURLOPT_URL            => 'https://api.ip2location.com/v2/?check=1&key=' . $this->data['settings']['ip2location_api_key'],
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_TIMEOUT        => 10,
				CURLOPT_SSL_VERIFYPEER => 0,
			));

			$result = curl_exec($ch);
			curl_close($ch);

			$json = json_decode($result);

			if (preg_match('/^\d+$/', $json->response)) {
				$this->databaseFound = true;
				$this->data['ws_credit'] = number_format($json->response, 0, '', ',');

				if ($json->response > 3) {
					$this->isRegionSupported = true;
				}
			}
		}

		$this->data['lookup_enabled'] = $this->databaseFound;
	}

	protected function initRules()
	{
		$this->data['sort'] = (isset($this->request->get['sort'])) ? $this->request->get['sort'] : '';
		$this->data['order'] = (isset($this->request->get['order'])) ? $this->request->get['order'] : '';

		$this->data['link_sort_from'] = $this->getSortLink('from');
		$this->data['link_sort_to'] = $this->getSortLink('to');
		$this->data['link_sort_code'] = $this->getSortLink('code');
		$this->data['link_sort_status'] = $this->getSortLink('status');

		$this->data['origins'] = $this->getOrigins();
		$this->data['rules'] = array();

		// Add New Rule
		$this->data['new_origins'] = (isset($this->request->post['newOrigins'])) ? $this->request->post['newOrigins'] : array();
		$this->data['new_condition'] = (isset($this->request->post['newCondition'])) ? $this->request->post['newCondition'] : '1';
		$this->data['new_from'] = (isset($this->request->post['newFrom'])) ? $this->request->post['newFrom'] : '';
		$this->data['new_to'] = (isset($this->request->post['newTo'])) ? $this->request->post['newTo'] : '';
		$this->data['new_code'] = (isset($this->request->post['newCode'])) ? $this->request->post['newCode'] : '301';
		$this->data['new_status'] = (isset($this->request->post['newStatus'])) ? $this->request->post['newStatus'] : '1';

		if (isset($this->request->post['newRule'])) {
			$this->data['scripts'] = '
			$(\'html, body\').animate({
				scrollTop: $(\'#rules-section\').offset().top
			}, 0);';

			if ($this->validateRules()) {
				$origins = array();
				$countries = array();

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ip2location_country");

				foreach ($query->rows as $row) {
					$countries[$row['country_code']] = $row['country_name'];
				}

				foreach ($this->data['new_origins'] as $newOrigin) {
					list($countryCode, $regionName) = explode('-', $newOrigin, 2);

					$origins[] = array(
						'code'		=> $countryCode,
						'name'		=> (isset($countries[$countryCode])) ? $countries[$countryCode] : $this->language->get('text_all_countries'),
						'region'	=> $regionName,
					);
				}

				$this->db->query("INSERT INTO `" . DB_PREFIX . "ip2location_redirector` SET " . implode(', ', array(
					'`origins` = \'' . $this->db->escape(json_encode($origins)) . '\'',
					'`mode` = \'' . $this->db->escape($this->data['new_condition']) . '\'',
					'`from` = \'' .  $this->db->escape($this->data['new_from']) . '\'',
					'`to` = \'' . $this->db->escape(($this->data['new_code'] == '404') ? '' : $this->data['new_to']) . '\'',
					'`code` = \'' . (int)$this->data['new_code'] . '\'',
					'`status` = \'' . (int)$this->data['new_status'] . '\'',
				)));

				$this->data['new_origins'] = array();
				$this->data['new_condition'] = '1';
				$this->data['new_from'] = '';
				$this->data['new_to'] = '';
				$this->data['new_code'] = '301';
				$this->data['new_status'] = '1';

				$this->data['rules_success'] = $this->language->get('text_success_add');
			} else {
				$this->data['scripts'] .= '

				$(\'#add-rule-modal\').modal(\'show\');';
			}
		}

		// Edit Rule
		if (isset($this->request->post['ruleId'])) {
			$origins = array();
			$countries = array();

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ip2location_country");

			foreach ($query->rows as $row) {
				$countries[$row['country_code']] = $row['country_name'];
			}

			if (isset($this->request->post['origins']) && $this->request->post['origins'] !== 'null') {
				$editOrigins = explode(',', $this->request->post['origins']);

				if (count($editOrigins) > 0) {
					foreach ($editOrigins as $newOrigin) {
						list($countryCode, $regionName) = explode('-', $newOrigin, 2);

						$origins[] = array(
							'code'		=> $countryCode,
							'name'		=> (isset($countries[$countryCode])) ? $countries[$countryCode] : $this->language->get('text_all_countries'),
							'region'	=> $regionName,
						);
					}

					$this->db->query('UPDATE `' . DB_PREFIX . 'ip2location_redirector` SET ' . implode(', ', array(
						'`origins` = \'' . $this->db->escape(json_encode($origins)) . '\'',
						'`mode` = \'' . $this->db->escape($this->request->post['mode']) . '\'',
						'`from` = \'' .  $this->db->escape($this->request->post['from']) . '\'',
						'`to` = \'' . $this->db->escape(($this->request->post['code'] == '404') ? '' : $this->request->post['to']) . '\'',
						'`code` = \'' . (int)$this->request->post['code'] . '\'',
						'`status` = \'' . (int)$this->request->post['status'] . '\'',
					)) . ' WHERE `rule_id` = \'' . $this->db->escape($this->request->post['ruleId']) . '\'');

					$this->data['rules_success'] = $this->language->get('text_success_save_rule');
				}
			}
		}

		// Delete Rule
		if (isset($this->request->post['deleteId'])) {
			$this->data['scripts'] = '
			$(\'html, body\').animate({
				scrollTop: $(\'#rules-section\').offset().top
			}, 0);';

			$this->db->query('DELETE FROM `' . DB_PREFIX . "ip2location_redirector` WHERE `rule_id` = '" . (int) $this->request->post['deleteId'] . "' LIMIT 1");
			$this->data['rules_success'] = $this->language->get('text_success_delete_rule');
		}

		$rules = $this->getRules();

		if (count($rules) > 0) {
			foreach ($rules as $rule) {
				$origins = json_decode($rule['origins']);

				$showOrigin = '';
				$originCodes = array();
				foreach ($origins as $origin) {
					$showOrigin .= '<span class="flag-icon flag-icon-' . strtolower($origin->code) . '"></span> ' . $origin->name . (($origin->region != '*') ? (', ' . $origin->region) : '') . '<br />';

					$originCodes[] = $origin->code . '-' . $origin->region;
				}

				$this->data['rules'][] = array(
					'id'          => $rule['rule_id'],
					'origins'     => $originCodes,
					'show_origin' => $showOrigin,
					'condition'   => $rule['mode'],
					'from'        => $rule['from'],
					'show_from'   => $rule['from'],
					'to'          => $rule['to'],
					'show_to'     => $rule['to'],
					'code'        => $rule['code'],
					'status'      => (bool) $rule['status'],
				);
			}
		}
	}

	protected function initLookup()
	{
		$this->data['ipAddress'] = (isset($this->request->post['ipAddress'])) ? $this->request->post['ipAddress'] : $this->getIP();

		if ((isset($this->request->post['ipAddress']))) {
			$this->data['scripts'] = '
			$(\'html, body\').animate({
				scrollTop: $(\'#lookup-section\').offset().top
			}, 0);';

			if (filter_var($this->data['ipAddress'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
				$result = $this->getLocation($this->data['ipAddress']);

				if ($result) {
					$this->data['lookup_result'] = sprintf(
						$this->language->get('text_success_ip_lookup'),
						$this->data['ipAddress'],
						(($result['regionName'] != '-' && !preg_match('/unavailable/', $result['regionName'])) ? $result['regionName'] . ', ' : '') . $result['countryName'] . ' (' . $result['countryCode'] . ')'
					);
				} else {
					$this->data['lookup_error'] = $this->language->get('error_ip_address_invalid');
				}
			} else {
				$this->data['lookup_error'] = $this->language->get('error_ip_address_invalid');
			}
		}
	}

	protected function getSortLink($sort)
	{
		$order = (isset($this->request->get['order'])) ? $this->request->get['order'] : 'desc';
		$order = ($order == 'desc') ? 'asc' : 'desc';

		return $this->url->link($this->request->get['route'], array(
			'user_token' => $this->session->data['user_token'],
			'sort'       => $sort,
			'order'      => $order,
		));
	}

	protected function getOrigins()
	{
		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('ip2location');

		$data['*-*'] = array(
			'code'    => '*-*',
			'text'    => $this->language->get('text_all_countries'),
		);

		if (isset($settings['ip2location_database_location'])) {
			require_once DIR_SYSTEM . 'library/ip2location/class.IP2Location.php';
			$geolocation = new \IP2Location\Database($settings['ip2location_database_location'], \IP2Location\Database::FILE_IO);
			$records = $geolocation->lookup('8.8.8.8', \IP2Location\Database::ALL);

			$this->isRegionSupported = ($records['regionName'] != '-' && !preg_match('/unavailable/', $records['regionName']));
		}

		$query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'ip2location_country` c JOIN `' . DB_PREFIX . 'ip2location_region` r ON c.`country_code` = r.`country_code` ORDER BY c.`country_name`, r.`region_name`');

		foreach ($query->rows as $result) {
			if (!$this->isRegionSupported && $result['region_name'] != '*') {
				continue;
			}

			$data[$result['country_code'] . '-' . $result['region_name']] = array(
				'code'    => $result['country_code'] . '-' . $result['region_name'],
				'text'    => ($result['region_name'] == '*') ? $result['country_name'] : ($result['country_name'] . ' - ' . $result['region_name']),
			);
		}

		return $data;
	}

	protected function getRules($total = false)
	{
		$sort = (isset($this->request->get['sort'])) ? $this->request->get['sort'] : 'from';
		$sort = (!in_array($sort, array('from', 'to', 'code', 'status'))) ? 'from' : $sort;

		$order = (isset($this->request->get['order'])) ? $this->request->get['order'] : 'desc';
		$order = ($order == 'desc') ? 'asc' : 'desc';

		$sql = 'SELECT ' . (($total) ? 'COUNT(*) AS `total`' : '*') . ' FROM `' . DB_PREFIX . 'ip2location_redirector` ORDER BY `' . $sort . '`';

		if ($order == 'desc') {
			$sql .= ' DESC';
		}

		$results = $this->db->query($sql);

		if ($total) {
			return (int) $results->row['total'];
		}

		return $results->rows;
	}

	private function getLocation($ip)
	{
		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('ip2location');

		if ($settings['ip2location_lookup_method'] == '0') {
			require_once DIR_SYSTEM . 'library/ip2location/class.IP2Location.php';
			$geolocation = new \IP2Location\Database($settings['ip2location_database_location'], \IP2Location\Database::FILE_IO);
			$records = $geolocation->lookup($ip, \IP2Location\Database::ALL);

			if ($records) {
				return array(
					'countryCode' => $records['countryCode'],
					'countryName' => $records['countryName'],
					'regionName'  => $records['regionName'],
				);
			}
		} elseif ($settings['ip2location_api_key']) {
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_HEADER => 0,
				CURLOPT_URL    => 'https://api.ip2location.com/v2/?' . http_build_query(array(
					'key'     => $settings['ip2location_api_key'],
					'ip'      => $ip,
					'format'  => 'json',
					'package' => 'WS3',
				)),
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_TIMEOUT        => 10,
				CURLOPT_SSL_VERIFYPEER => 0,
			));

			$result = curl_exec($ch);
			curl_close($ch);

			if (($json = json_decode($result)) !== null) {
				return array(
					'countryCode' => $json->country_code,
					'countryName' => $json->country_name,
					'regionName'  => $json->region_name,
				);
			}
		}

		return false;
	}

	private function getIP()
	{
		// Get client IP
		$ip = $_SERVER['REMOTE_ADDR'];

		// Get forwarded IP
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$xip = trim(current(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));

			if (filter_var($xip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
				$ip = $xip;
			}
		}

		if (isset($_SERVER['DEV_MODE'])) {
			$ip = '175.144.151.253';
		}

		return $ip;
	}
}
