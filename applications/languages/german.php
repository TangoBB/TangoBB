<?php

  /*
   * Iko Language Entity.
   * Language: German.
   */

  $LANG = array(
    //Main Forum
    'bb' => array(
      'forum' => 'Forum',
      'members' => 'Mitglieder',
      'search' => 'Suchen',
      'edit_post_in' => 'Edit post in:',
      'new_thread_in' => 'New thread in:',
      'new_reply_in' => 'New reply in:',
      'new_report' => 'Melden',
      'new_thread_breadcrumb' => 'Neuer Thread',
      'edit_post_breadcrumb' => 'Post bearbeiten',
      'new_reply_breadcrumb' => 'Neue Antwort',
      'already_watched_thread' => 'Du beobachtest diesen Thread bereits.',
      'already_unwatched_thread' => 'Du beobachtest diesen Thread bereits nicht.',
      'watch_thread' => 'Du beobachtest nun diesen Thread.',
      'unwatch_thread' => 'Du beobachtest diesen Thread nicht mehr.',
      'error_watching' => 'Fehler beim Beobachten des Threads',
      'error_unwatching' => 'Fehler beim Nicht-Beobachten des Threads.',                        // Überarbeiten
      'form' => array(
        'edit_post' => 'Bearbeiten',
        'report' => 'Melden',
        'report_reason' => 'Grund'
      ),
      'conversations' => array(
        'my_conversations' => 'Meine Konversationen', // added by N8boy
        'page_conversations' => 'Konversationen',
        'page_new' => 'Beginne eine Konversation',
        'page_reply' => 'Antworte der Konversation:',
        'starter' => 'Begonnen von',
        'reciever' => 'gesendet an',
        'by' => 'By:', // not needed anymore | N8boy
        'for' => 'For:', // not needed anymore | N8boy
        'form_to'=> 'Empfänger <small>Getrennt mit ","</small>',
        'form_send' => 'Senden',
        'form_title' => 'Titel',
        'form_reply' => 'Antworten',
        'no_conversations' => 'Du hast bisher noch keine Konversationen.',
        'user_not_exist' => 'Der Benutzer <strong>%username%</strong> existiert nicht!',
        'message_sent' => 'Deine Nachricht an <strong>%username%</strong> wurde versendet!',
        'error_sending' => 'Fehler beim Senden der Nachricht an <strong>%username%</strong>.',
        'error_sending_alt' => 'Fehler beim Senden der Nachricht.'
      ),
      'members' => array(
        'home' => 'Mitglieder',
        'activate_account' => 'Konto aktivieren',
        'account_activated' => 'Dein Konto wurde erfolgreich aktiviert! <a href="' . SITE_URL . '/members.php/cmd/signin">Log dich jetzt ein.</a>',
        'birthday' => 'Geburtstag',
        'error_activating' => 'Fehler beim aktivieren des Kontos. Bitte probiere es später erneut.',
        'forgot_password' => 'Passwort vergessen',
        'reset_password' => 'Passwort zurücksetzen',
        'error_request_password_reset' => 'Passwort-Rücksetz-Anfrage fehlgeschlagen.',
        'form_reset_password' => 'Passwort zurücksetzen',
        'form_email' => 'E-Mail',
        'form_password' => 'Passwort',
        'form_confirm_password' => 'Passwort bestätigen',
        'form_username' => 'Benutzername',
        'form_register' => 'Registrieren',
        'password_reset_link_sent' => 'Ein Link zum Zurücksetzen Ihres Passwortes wurde versendet.',
        'error_password_reset_token_used' => 'Das Token wurde bereits verwendet. Bitte fordern Sie eine neue Passwort-Rücksetzung an.',
        'error_password_reset_token_expired' => 'Das Tolen ist ausgelaufen. Bitte fordern Sie eine neue Passwort-Rücksetzung an.',
        'error_password_reset_token_unknown' => 'Unbekanntes Token. Bitte benutzen Sie den Link in der E-Mail.',
        'error_password_reset_token_missing' => 'Fehlender Token. Bitte benutzen Sie den Link in der E-Mail.',
        'error_password_reset' => 'Ändern des Passwortes ist fehlgeschlagen.',
        'password_reset_successful' => 'Dein Passwort wurde zurückgesetzt.',
        'register' => 'Registrieren',
        'password_different' => 'Das Passwort ist unterschiedlich!',
        'username_taken' => 'Benutzername ist bereits vergeben!',
        'error_register' => 'Fehler bei der Registrierung. Bitte probieren Sie es später erneut.',
        'register_successful' => 'Erfolgreich Registriert. Sie werden automatisch angemeldet...',
        'register_successful_email' => 'Erfolgreich Registriert. Eine Verifizierung wurde an Ihre  E-Mail Adresse gesendet.',
        'register_message' => 'Mit dem Klicken auf "Registrieren", akzeptieren sie die <a href="' . SITE_URL . '/members.php/cmd/rules">Foren Regeln</a>.',
        'register_disabled' => 'Die Registrierung ist zur Zeit deaktiviert.',
        'log_in' => 'Anmelden',
        'invalid_login' => 'Ungültiges Passwort und/oder Benutzername.',
        'login_success' => 'Erfolgreich angemeldet! Klicken Sie <a href="' . SITE_URL . '">hier</a>, falls Sie nicht automatisch weitergeleitet werden.',
        'email_not_activated' => 'Deine E-Mail wurde bisher nicht aktiviert.',
        'banned' => 'Sie sin zur Zeit gesperrt. Kontaktieren Sie das Team für weitere Details.<br />Entsperr Datum: <b>%unban_date%</b><br />Sperr Grund: <b>%ban_reason%</b>',
        'rules' => 'Foren Regeln',
        'rules_message' => 'Alle Benutzer müssen die Foren Regeln akzeptieren.<br />%rules%<br />Beim Verstoßen gegen die Regeln, kann Ihre Nachricht gelöscht werden oder es führt im Extremfall zu einer permanenten Sperrung.',
        'profile_of' => 'Profil von',
        'posted_thread' => 'Hat einen neuen Thread erstellt: <a href="%url%">%title%</a> <small>(%date%)</small><hr size="1" />',
        'replied_to' => 'Hat einem Thread geantwortet: <a href="%url%">%title%</a> <small>(%date%)</small><hr size="1" />'
      ),
      'profile' => array(
        'avatar' => 'Avatar',
        'change_avatar' => 'Avatar ändern <small>Maximal 500x500 Pixel</small>',
        'use_gravatar' => 'Benutze Gravatar',
        'form_save' => 'Änderungen speichern',
        'error_adding_gravatar' => 'Fehler beim Benutzen von Gravatar. Bitte später erneut probieren.',
        'successful_adding_gravatar' => 'Gravatar erfolgreich gespeichert!',
        'error_upload_avatar' => 'Fehler beim Hochladen des Avatars. Bitte später erneut probieren.',
        'about_you' => 'Über mich',
        'successful_upload_avatar' => 'Avatar erfolgreich gespeichert!',
        'password' => 'Passwort',
        'current_password' => 'Aktuelles Passwort',
        'new_password' => 'Neues Passwort',
        'error_updaing_password' => 'Fehler beim Ändern des Passwortes.',
        'signature' => 'Signatur',
        'timezone' => 'Zeitzone',
        'location' => 'Wohnort',
        'error_updating_signature' => 'Fehler beim Ändern der Signatur. Bitte später erneut probieren.',
        'personal_details' => 'Persönliche Details',
        'confirm_password' => 'Passwort bestätigen',
        'change_theme' => 'Design ändern',
        'theme_set' => 'Design wurde geändert.',
        'theme_error' => 'Fhler beim Ändern des Designs.',
        'theme_not_exist' => 'Design existiert nicht.'
      )
    ),
    //Error Pages
    'error_pages' => array(
      '404' => array(
        'header' => '404',
        'message' => 'Entschuldigung, die von Ihnen gesuchte Resource konnte nicht gefunden werden.'
      )
    ),
    //Global Form Variables
    'global_form_process' => array(
      'all_fields_required' => 'Alle Felder werden benötigt.',
      'enter_search_query' => 'Bitte geben Sie eine Suchanfrage ein!',
      'error_updating_post' => 'Fehler beim Ändern Ihrer Nachricht. Bitte später erneut probieren.',
      'error_creating_thread' => 'Fehler beim Erstellen des Threads. Bitte später erneut probieren.',
      'error_replying_thread' => 'Fehler beim Antworten. Bitte später erneut probieren.',
      'error_submitting_report' => 'Fehler beim Übermittel der Meldung.  Bitte später erneut probieren.',
      'thread_create_success' => 'Thread wurde erfolgeich erstellt. Sie werden weitergeleitet...',
      'report_create_success' => 'Ihre Meldung wurde efolgreich übermittelt!',
      'search_no_result' => 'Keine Ergebnisse.',
      'different_message_previous' => 'Bitte schreiben Sie eine Nachricht, die sich von der vorherigen unterscheidet.',
      'email_not_exist' => 'Die E-Mail Adresse exisitiert nicht.',
      'email_used' => 'E-Mail wird bereits verwendet, nutzen Sie bitte eine andere.',
      'invalid_email' => 'E-Mail Adresse ist ungültig!',
      'invalid_file_format' => 'Ungültiges Dateiformat!',
      'img_dimension_limit' => 'Bilgröße ist zu groß!',
      'save_success' => 'Gespeichert!',
      'error_saving' => 'Fehler beim Speichern. Bitte später erneut probieren.',
      'invalid_password' => 'Das aktuelle Passwort ist ungültig!',
      'captcha_incorrect' => 'Ungültiges captcha!'
    ),
    //Email Variables.
    'email' => array(
      'forgot_password' => array(
        'subject' => 'Passwort zurücksetzen', 
        'content' => '<p>Du hast kürzlich auf der Seite %site_name% eine Passwort-Rücksetzung angefordert..</p><p>Um das Passwort zurückzusetzen benutze bitte folgenden Link: %token_url%</p>'
      ),
      'register' => array(
        'subject' => 'Konto Aktivierung',
        'content' => '<p>Du hast dich erfolgreich bei %site_name% registriert.</p><p>Klicke <a href="%activate_url%">hier</a> um dein Konto zu Aktivieren.</p>'
      ),
      'notify' => array(
        'more_info' => '<br />Klicke <a href="%url%">hier</a> um mehr herrauszufinden..'
      )
    ),
    //Moderator Panel
    'mod' => array(
      'ban' => array(
        'ban' => 'Benutzer sperren',
        'ban_success' => 'Benutzer wurde gesperrt. <a href="%url%">Zurück zum Benutzerprofil</a>.',
        'ban_error' => 'Fehler beim Sperren des Benutzers.',
        'already_banned' => 'Benutzer ist bereits gesperrt.',
        'unban' => 'Benutzer entsperren',
        'unban_success' => 'Benutzer wurde entsperrt. <a href="%url%">Zurück zum Benutzerprofil</a>.',
        'unban_error' => 'Fehler beim Entsperren des Benutzers.',
        'already_unbanned' => 'Benutzer ist bereits entsperrt.',
      ),
      'close' => array(
        'close' => 'Thread schließen',
        'close_success' => 'Thread wurde geschlossen. <a href="%url%">Zurück zum Thread</a>.',
        'close_error' => 'Fehler beim Schließen des Threads.',
        'already_closed' => 'Thread ist bereits geschlossen.',
        'open' => 'Thread öffnen',
        'open_success' => 'Thread wurde geöffnet. <a href="%url%">Zurück zum Thread</a>.',
        'open_error' => 'Fehler beim Öffnen des Threads.',
        'already_opened' => 'Thread ist bereits offen.'
      ),
      'stick' => array(
        'stick' => 'Thread anpinnen',
        'stick_success' => 'Thread wurde angepinnt. <a href="%url%">Zurück zum Thread</a>.',
        'stick_error' => 'Fehler beim Anpinnen des Threads.',
        'already_stuck' => 'Thread ist bereits angepinnt.',
        'unstick' => 'Thread abpinnen',
        'unstick_success' => 'Thread wurde abgepinnt. <a href="%url%">Zurück zum Thread</a>.',
        'unstick_error' => 'Fehler beim Abpinnen des Threads.',
        'already_unstuck' => 'Thread ist bereits abgepinnt.'
      ),
      'reports' => array(
        'reports' => 'Meldungen',
        'thread' => 'Thread:',
        'user' => 'Benutzer:',
        'reason' => 'Grund:',
        'reported_time' => 'Zeitpunkt der Meldung:'
      ),
      'delete' => array(
        'delete' => 'Nachricht löschen',
        'thread_deleted' => 'Thread wurde gelöscht.', // Thread or post??
        'error_deleting' => 'Fehler beim Löschen der Nachricht.',
        'post_deleted' => 'Nachricht wurde gelöscht.'
      ),
      'move' => array(
        'move' => 'Thread bewegen',
        'thread_moved' => 'Thread wurde bewegt. <a href="%url%">Zurück zum Thread</a>.',
        'error_moving' => 'Fehler beim Bewegen des Threads.'
      ),
      'del_report' => array(
        'delete' => 'Meldung löschen',
        'report_deleted' => 'Meldung wurde gelöscht. <a href="%url%">Zurück</a>.',
        'error_deleting' => 'Fehler beim Löschen der Meldung. <a href="%url%">Zurück</a>.'
      )
    ),
    'notification' => array(
      'mention' => '%username% hat dich in einer Nachricht erwähnt!',
      'reply' => '%username% hat auf den Thread <strong>%thread_title%</strong> geantwortet!',
      'quoted' => '%username% hat dich zitiert in <strong>%thread_title%</strong>!'
    ),
    'flat' => array(
      'merge_post' => '----------'
    ),
    'time' => array(
      'hours_ago' => 'vor %time% Stunden.',
      'minutes_ago' => 'vor %time% Minuten.',
      'just_now' => 'Gerade eben.'
    ),
    // ISO 3166-1 Country codes
    'location' => array( 
      '--' => 'Nichts ausgewählt',
      'AD' => 'Andorra',
      'AE' => 'United Arab Emirates',
      'AF' => 'Afghanistan',
      'AG' => 'Antigua and Barbuda',
      'AI' => 'Anguilla',
      'AL' => 'Albania',
      'AM' => 'Armenia',
      'AO' => 'Angola',
      'AQ' => 'Antarctica',
      'AR' => 'Argentina',
      'AS' => 'American Samoa',
      'AT' => 'Österreich', // Done
      'AU' => 'Australien', // Done
      'AW' => 'Aruba',
      'AX' => 'Aland Islands',
      'AZ' => 'Azerbaijan',
      'BA' => 'Bosnia and Herzegovina',
      'BB' => 'Barbados',
      'BD' => 'Bangladesh',
      'BE' => 'Belgium',
      'BF' => 'Burkina Faso',
      'BG' => 'Bulgaria',
      'BH' => 'Bahrain',
      'BI' => 'Burundi',
      'BJ' => 'Benin',
      'BL' => 'Saint Barthélemy',
      'BM' => 'Bermuda',
      'BN' => 'Brunei Darussalam',
      'BO' => 'Bolivia',
      'BQ' => 'Bonaire',
      'BR' => 'Brazilien', // Done
      'BS' => 'Bahamas',
      'BT' => 'Bhutan',
      'BV' => 'Bouvet Island',
      'BW' => 'Botswana',
      'BY' => 'Belarus',
      'BZ' => 'Belize',
      'CA' => 'Canada',
      'CC' => 'Cocos Islands',
      'CD' => 'Congo (the Democratic Republic)',
      'CF' => 'Central African Republic',
      'CG' => 'Congo',
      'CH' => 'Schweiz', // Done
      'CI' => 'Cote d\'Ivoire',
      'CK' => 'Cook Islands',
      'CL' => 'Chile',
      'CM' => 'Cameroon',
      'CN' => 'China',
      'CO' => 'Colombia',
      'CR' => 'Costa Rica',
      'CU' => 'Cuba',
      'CV' => 'Cabo Verde',
      'CW' => 'Curacao',
      'CX' => 'Christmas Island',
      'CY' => 'Cyprus',
      'CZ' => 'Czech Republic',
      'DE' => 'Deutschland', // Done
      'DJ' => 'Djibouti',
      'DK' => 'Dänemark', // Done
      'DM' => 'Dominica',
      'DO' => 'Dominican Republic',
      'DZ' => 'Algeria',
      'EC' => 'Ecuador',
      'EE' => 'Estonia',
      'EG' => 'Ägypten', // Done
      'EH' => 'Western Sahara',
      'ER' => 'Eritrea',
      'ES' => 'Spanien', // Done
      'ET' => 'Ethiopia',
      'FI' => 'Finnland',  // Done
      'FJ' => 'Fiji',
      'FK' => 'Falkland Islands',
      'FM' => 'Micronesia',
      'FO' => 'Faroe Islands',
      'FR' => 'Frankreich', // Done
      'GA' => 'Gabon',
      'GB' => 'Großbritannien', // Done       
      'GD' => 'Grenada',
      'GE' => 'Georgia',
      'GF' => 'French Guiana',
      'GG' => 'Guernsey',
      'GH' => 'Ghana',
      'GI' => 'Gibraltar',
      'GL' => 'Grönland', // Done
      'GM' => 'Gambia',
      'GN' => 'Guinea',
      'GP' => 'Guadeloupe',
      'GQ' => 'Equatorial Guinea',
      'GR' => 'Griechenland', // Done
      'GS' => 'South Georgia and the South Sandwich Islands',
      'GT' => 'Guatemala',
      'GU' => 'Guam',
      'GW' => 'Guinea-Bissau',
      'GY' => 'Guyana',
      'HK' => 'Hong Kong', // Done
      'HM' => 'Heard Island and McDonald Islands',
      'HN' => 'Honduras',
      'HR' => 'Croatia',
      'HT' => 'Haiti',
      'HU' => 'Hungary',
      'ID' => 'Indonesia',
      'IE' => 'Ireland',
      'IL' => 'Israel',
      'IM' => 'Isle of Man', // Done
      'IN' => 'Indien', // Done
      'IO' => 'British Indian Ocean Territory',
      'IQ' => 'Irak', // Done
      'IR' => 'Iran', // Done
      'IS' => 'Island', // Done
      'IT' => 'Italien', // Done
      'JE' => 'Jersey',
      'JM' => 'Jamaica',
      'JO' => 'Jordan',
      'JP' => 'Japan',
      'KE' => 'Kenya',
      'KG' => 'Kyrgyzstan',
      'KH' => 'Cambodia',
      'KI' => 'Kiribati',
      'KM' => 'Comoros',
      'KN' => 'Saint Kitts and Nevis',
      'KP' => 'The Democratic People\'s Republic of Korea',
      'KR' => 'The Republic of Korea',
      'KW' => 'Kuwait',
      'KY' => 'Cayman Islands',
      'KZ' => 'Kazakhstan',
      'LA' => 'Lao People\'s Democratic Republic',
      'LB' => 'Lebanon',
      'LC' => 'Saint Lucia',
      'LI' => 'Liechtenstein',
      'LK' => 'Sri Lanka',
      'LR' => 'Liberia',
      'LS' => 'Lesotho',
      'LT' => 'Lithuania',
      'LU' => 'Luxembourg',
      'LV' => 'Latvia',
      'LY' => 'Libya',
      'MA' => 'Morocco',
      'MC' => 'Monaco',
      'MD' => 'Moldova',
      'ME' => 'Montenegro',
      'MF' => 'Saint Martin',
      'MG' => 'Madagascar',
      'MH' => 'Marshall Islands',
      'MK' => 'Macedonia',
      'ML' => 'Mali',
      'MM' => 'Myanmar',
      'MN' => 'Mongolia',
      'MO' => 'Macao',
      'MP' => 'Northern Mariana Islands',
      'MQ' => 'Martinique',
      'MR' => 'Mauritania',
      'MS' => 'Montserrat',
      'MT' => 'Malta',
      'MU' => 'Mauritius',
      'MV' => 'Maldives',
      'MW' => 'Malawi',
      'MX' => 'Mexico',
      'MY' => 'Malaysia',
      'MZ' => 'Mozambique',
      'NA' => 'Namibia',
      'NC' => 'New Caledonia',
      'NE' => 'Niger',
      'NF' => 'Norfolk Islands',
      'NG' => 'Nigeria',
      'NI' => 'Nicaragua',
      'NL' => 'Netherlands',
      'NO' => 'Norwegen', // Done
      'NP' => 'Nepal',
      'NR' => 'Nauru',
      'NU' => 'Niue',
      'NZ' => 'Neuseeland', // Done
      'OM' => 'Oman',
      'PA' => 'Panama',
      'PE' => 'Peru',
      'PF' => 'French Polynesia',
      'PG' => 'Papua New Guinea',
      'PH' => 'Philippines',
      'PK' => 'Pakistan',
      'PL' => 'Poland',
      'PM' => 'Saint Pierre and Miquelon',
      'PN' => 'Pitcairn',
      'PR' => 'Puerto Rico',
      'PS' => 'Palestine',
      'PT' => 'Portugal', // Done
      'PW' => 'Palau',
      'PY' => 'Paraguay',
      'QA' => 'Qatar',
      'RE' => 'Réunion',
      'RO' => 'Romania',
      'RS' => 'Serbien', // Done
      'RU' => 'Russland', // Done
      'RW' => 'Rwanda',
      'SA' => 'Saudi Arabia',
      'SB' => 'Solomon Islands',
      'SC' => 'Seychelles',
      'SD' => 'Sudan',
      'SE' => 'Sweden',
      'SG' => 'Singapore',
      'SH' => 'Saint Helena',
      'SI' => 'Slovenia',
      'SJ' => 'Svalbard and Jan Mayen',
      'SK' => 'Slovakia',
      'SL' => 'Sierra Leone',
      'SM' => 'San Marino',
      'SN' => 'Senegal',
      'SO' => 'Somalia',
      'SR' => 'Suriname',
      'SS' => 'South Sudan',
      'ST' => 'Sao Tome and Pricipe',
      'SV' => 'El Salvador',
      'SX' => 'Sint Maarten',
      'SY' => 'Syrian Arab Republic',
      'SZ' => 'Swaziland',
      'TC' => 'Turks and Caicos Islands',
      'TD' => 'Chad',
      'TF' => 'French Southern Terrotories',
      'TG' => 'Togo',
      'TH' => 'Thailand',
      'TJ' => 'Tajikistan',
      'TK' => 'Tokelau',
      'TL' => 'Timor-Leste',
      'TM' => 'Turkmenistan',
      'TN' => 'Tunisia',
      'TO' => 'Tonga',
      'TR' => 'Türkei', // Done
      'TT' => 'Trinidad and Tobago',
      'TV' => 'Tuvalu',
      'TW' => 'Taiwan',
      'TZ' => 'Tanzania',
      'UA' => 'Ukraine', // Done
      'UG' => 'Uganda',
      'UM' => 'United States Minor Outlying Islands',
      'US' => 'Vereinigte Staaten von Amerika', // Done
      'UY' => 'Uruguay',
      'UZ' => 'Uzbekistan',
      'VA' => 'Holy See',
      'VC' => 'Venezuela',
      'VG' => 'Virgin Islands (GB)',
      'VI' => 'Virgin Islands (US)',
      'VN' => 'Viet Nam',
      'VU' => 'Vanatu',
      'WF' => 'Wallis and Futuna',
      'WS' => 'Samoa',
      'YE' => 'Yemen',
      'YT' => 'Mayotte',
      'ZA' => 'South Africa',
      'ZM' => 'Zambia',
      'ZW' => 'Zimbabwe'
      )
  );

?>