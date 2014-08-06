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
      'error_unwatching' => 'Fehler beim Nicht-Beobachten des Threads.',                        // &Uuml;berarbeiten
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
        'form_to'=> 'Empf&auml;nger <small>Getrennt mit ","</small>',
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
        'error_activating' => 'Fehler beim aktivieren des Kontos. Bitte probiere es sp&auml;ter erneut.',
        'forgot_password' => 'Passwort vergessen',
        'reset_password' => 'Passwort zur&uuml;cksetzen',
        'error_request_password_reset' => 'Passwort-R&uuml;cksetz-Anfrage fehlgeschlagen.',
        'form_reset_password' => 'Passwort zur&uuml;cksetzen',
        'form_email' => 'E-Mail',
        'form_password' => 'Passwort',
        'form_confirm_password' => 'Passwort best&auml;tigen',
        'form_username' => 'Benutzername',
        'form_register' => 'Registrieren',
        'password_reset_link_sent' => 'Ein Link zum Zur&uuml;cksetzen Ihres Passwortes wurde versendet.',
        'error_password_reset_token_used' => 'Das Token wurde bereits verwendet. Bitte fordern Sie eine neue Passwort-R&uuml;cksetzung an.',
        'error_password_reset_token_expired' => 'Das Tolen ist ausgelaufen. Bitte fordern Sie eine neue Passwort-R&uuml;cksetzung an.',
        'error_password_reset_token_unknown' => 'Unbekanntes Token. Bitte benutzen Sie den Link in der E-Mail.',
        'error_password_reset_token_missing' => 'Fehlender Token. Bitte benutzen Sie den Link in der E-Mail.',
        'error_password_reset' => '&Auml;ndern des Passwortes ist fehlgeschlagen.',
        'password_reset_successful' => 'Dein Passwort wurde zur&uuml;ckgesetzt.',
        'register' => 'Registrieren',
        'password_different' => 'Das Passwort ist unterschiedlich!',
        'username_taken' => 'Benutzername ist bereits vergeben!',
        'error_register' => 'Fehler bei der Registrierung. Bitte probieren Sie es sp&auml;ter erneut.',
        'register_successful' => 'Erfolgreich Registriert. Sie werden automatisch angemeldet...',
        'register_successful_email' => 'Erfolgreich Registriert. Eine Verifizierung wurde an Ihre  E-Mail Adresse gesendet.',
        'register_message' => 'Mit dem Klicken auf "Registrieren", akzeptieren sie die <a href="' . SITE_URL . '/members.php/cmd/rules">Foren Regeln</a>.',
        'register_disabled' => 'Die Registrierung ist zur Zeit deaktiviert.',
        'log_in' => 'Anmelden',
        'invalid_login' => 'Ung&uuml;ltiges Passwort und/oder Benutzername.',
        'login_success' => 'Erfolgreich angemeldet! Klicken Sie <a href="' . SITE_URL . '">hier</a>, falls Sie nicht automatisch weitergeleitet werden.',
        'email_not_activated' => 'Deine E-Mail wurde bisher nicht aktiviert.',
        'banned' => 'Sie sin zur Zeit gesperrt. Kontaktieren Sie das Team f&uuml;r weitere Details.<br />Entsperr Datum: <b>%unban_date%</b><br />Sperr Grund: <b>%ban_reason%</b>',
        'rules' => 'Foren Regeln',
        'rules_message' => 'Alle Benutzer m&uuml;ssen die Foren Regeln akzeptieren.<br />%rules%<br />Beim Verstoßen gegen die Regeln, kann Ihre Nachricht gel&ouml;scht werden oder es f&uuml;hrt im Extremfall zu einer permanenten Sperrung.',
        'profile_of' => 'Profil von',
        'posted_thread' => 'Hat einen neuen Thread erstellt: <a href="%url%">%title%</a> <small>(%date%)</small><hr size="1" />',
        'replied_to' => 'Hat einem Thread geantwortet: <a href="%url%">%title%</a> <small>(%date%)</small><hr size="1" />'
      ),
      'profile' => array(
        'profile' => 'Profil',
        'avatar' => 'Avatar',
        'change_avatar' => 'Avatar &auml;ndern <small>Maximal 500x500 Pixel</small>',
        'use_gravatar' => 'Benutze Gravatar',
        'form_save' => '&Auml;nderungen speichern',
        'error_adding_gravatar' => 'Fehler beim Benutzen von Gravatar. Bitte sp&auml;ter erneut probieren.',
        'successful_adding_gravatar' => 'Gravatar erfolgreich gespeichert!',
        'error_upload_avatar' => 'Fehler beim Hochladen des Avatars. Bitte sp&auml;ter erneut probieren.',
        'about_you' => '&Uuml;ber mich',
        'successful_upload_avatar' => 'Avatar erfolgreich gespeichert!',
        'password' => 'Passwort',
        'current_password' => 'Aktuelles Passwort',
        'new_password' => 'Neues Passwort',
        'error_updaing_password' => 'Fehler beim &Auml;ndern des Passwortes.',
        'signature' => 'Signatur',
        'timezone' => 'Zeitzone',
        'location' => 'Wohnort',
        'error_updating_signature' => 'Fehler beim &Auml;ndern der Signatur. Bitte sp&auml;ter erneut probieren.',
        'personal_details' => 'Pers&ouml;nliche Details',
        'confirm_password' => 'Passwort best&auml;tigen',
        'change_theme' => 'Design &auml;ndern',
        'theme_set' => 'Design wurde ge&auml;ndert.',
        'theme_error' => 'Fehler beim &Auml;ndern des Designs.',
        'theme_not_exist' => 'Design existiert nicht.',
        'gender' => 'Geschlecht',
        'female' => 'Weiblich',
        'male' => 'M&aumlnnlich',
        'not_telling' => 'Egal'
      )
    ),
    //Error Pages
    'error_pages' => array(
      '404' => array(
        'header' => '404',
        'message' => 'Entschuldigung, die von Ihnen gesuchte Resource konnte nicht gefunden werden.'
      )
    ),
    'errors' => array(
      'thread_tracker_insert' => 'Fehler beim Erstellen des Thread Trackers.',
      'thread_tracker_update' => 'Fehler beim Aktualisieren des Thread Trackers.'
    ),
    //Global Form Variables
    'global_form_process' => array(
      'all_fields_required' => 'Alle Felder werden ben&ouml;tigt.',
      'enter_search_query' => 'Bitte geben Sie eine Suchanfrage ein!',
      'error_updating_post' => 'Fehler beim &Auml;ndern Ihrer Nachricht. Bitte sp&auml;ter erneut probieren.',
      'error_creating_thread' => 'Fehler beim Erstellen des Threads. Bitte sp&auml;ter erneut probieren.',
      'error_replying_thread' => 'Fehler beim Antworten. Bitte sp&auml;ter erneut probieren.',
      'error_submitting_report' => 'Fehler beim &Uuml;bermittel der Meldung.  Bitte sp&auml;ter erneut probieren.',
      'thread_create_success' => 'Thread wurde erfolgeich erstellt. Sie werden weitergeleitet...',
      'report_create_success' => 'Ihre Meldung wurde efolgreich &uuml;bermittelt!',
      'search_no_result' => 'Keine Ergebnisse.',
      'different_message_previous' => 'Bitte schreiben Sie eine Nachricht, die sich von der vorherigen unterscheidet.',
      'email_not_exist' => 'Die E-Mail Adresse exisitiert nicht.',
      'email_used' => 'E-Mail wird bereits verwendet, nutzen Sie bitte eine andere.',
      'invalid_email' => 'E-Mail Adresse ist ung&uuml;ltig!',
      'invalid_file_format' => 'Ung&uuml;ltiges Dateiformat!',
      'img_dimension_limit' => 'Bilgr&ouml;ße ist zu groß!',
      'save_success' => 'Gespeichert!',
      'error_saving' => 'Fehler beim Speichern. Bitte sp&auml;ter erneut probieren.',
      'invalid_password' => 'Das aktuelle Passwort ist ung&uuml;ltig!',
      'captcha_incorrect' => 'Ung&uuml;ltiges captcha!'
    ),
    //Email Variables.
    'email' => array(
      'forgot_password' => array(
        'subject' => 'Passwort zur&uuml;cksetzen', 
        'content' => '<p>Du hast k&uuml;rzlich auf der Seite %site_name% eine Passwort-R&uuml;cksetzung angefordert..</p><p>Um das Passwort zur&uuml;ckzusetzen benutze bitte folgenden Link: %token_url%</p>'
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
        'ban_success' => 'Benutzer wurde gesperrt. <a href="%url%">Zur&uuml;ck zum Benutzerprofil</a>.',
        'ban_error' => 'Fehler beim Sperren des Benutzers.',
        'already_banned' => 'Benutzer ist bereits gesperrt.',
        'unban' => 'Benutzer entsperren',
        'unban_success' => 'Benutzer wurde entsperrt. <a href="%url%">Zur&uuml;ck zum Benutzerprofil</a>.',
        'unban_error' => 'Fehler beim Entsperren des Benutzers.',
        'already_unbanned' => 'Benutzer ist bereits entsperrt.',
      ),
      'close' => array(
        'close' => 'Thread schließen',
        'close_success' => 'Thread wurde geschlossen. <a href="%url%">Zur&uuml;ck zum Thread</a>.',
        'close_error' => 'Fehler beim Schließen des Threads.',
        'already_closed' => 'Thread ist bereits geschlossen.',
        'open' => 'Thread &ouml;ffnen',
        'open_success' => 'Thread wurde ge&ouml;ffnet. <a href="%url%">Zur&uuml;ck zum Thread</a>.',
        'open_error' => 'Fehler beim &Ouml;ffnen des Threads.',
        'already_opened' => 'Thread ist bereits offen.'
      ),
      'stick' => array(
        'stick' => 'Thread anpinnen',
        'stick_success' => 'Thread wurde angepinnt. <a href="%url%">Zur&uuml;ck zum Thread</a>.',
        'stick_error' => 'Fehler beim Anpinnen des Threads.',
        'already_stuck' => 'Thread ist bereits angepinnt.',
        'unstick' => 'Thread abpinnen',
        'unstick_success' => 'Thread wurde abgepinnt. <a href="%url%">Zur&uuml;ck zum Thread</a>.',
        'unstick_error' => 'Fehler beim Abpinnen des Threads.',
        'already_unstuck' => 'Thread ist bereits abgepinnt.'
      ),
      'reports' => array(
        'reports' => 'Meldungen',
        'thread' => 'Thread:',
        'user' => 'Benutzer:',
        'reason' => 'Grund:',
        'reported_time' => 'Zeitpunkt:',
        'no_reports' => 'Im Moment gibt es keine Meldungen.'
      ),
      'delete' => array(
        'delete' => 'Nachricht l&ouml;schen',
        'thread_deleted' => 'Thread wurde gel&ouml;scht.', // Thread or post??
        'error_deleting' => 'Fehler beim L&ouml;schen der Nachricht.',
        'post_deleted' => 'Nachricht wurde gel&ouml;scht.'
      ),
      'move' => array(
        'move' => 'Thread verschieben',
        'thread_moved' => 'Thread wurde verschoben. <a href="%url%">Zur&uuml;ck zum Thread</a>.',
        'error_moving' => 'Fehler beim Verschieben des Threads.'
      ),
      'del_report' => array(
        'delete' => 'Meldung l&ouml;schen',
        'report_deleted' => 'Meldung wurde gel&ouml;scht. <a href="%url%">Zur&uuml;ck</a>.',
        'error_deleting' => 'Fehler beim L&ouml;schen der Meldung. <a href="%url%">Zur&uuml;ck</a>.'
      )
    ),
    'notification' => array(
      'mention' => '%username% hat dich in einer Nachricht erw&auml;hnt!',
      'reply' => '%username% hat auf den Thread <strong>%thread_title%</strong> geantwortet!',
      'quoted' => '%username% hat dich zitiert in <strong>%thread_title%</strong>!'
    ),
    'flat' => array(
      'merge_post' => '----------'
    ),
    'time' => array(
      'hours_ago' => 'vor %time% Stunden',
      'minutes_ago' => 'vor %time% Minuten',
      'just_now' => 'Gerade eben'
    ),
    // ISO 3166-1 Country codes
    'location' => array( 
      '--' => 'Nichts ausgew&auml;hlt',
      'AD' => 'Andorra', // Done
      'AE' => 'Vereinigte Arabische Emirate', // Done
      'AF' => 'Afghanistan',
      'AG' => 'Antigua und Barbuda', // Done
      'AI' => 'Anguilla', // Done
      'AL' => 'Albanien', // Done
      'AM' => 'Armenien', // Done
      'AO' => 'Angola', // Done
      'AQ' => 'Antarktika', // Done
      'AR' => 'Argentinien', // Done
      'AS' => 'Amerikanisch-Samoa', // Done
      'AT' => '&Ouml;sterreich', // Done
      'AU' => 'Australien', // Done
      'AW' => 'Aruba', // Done
      'AX' => 'Aland', // Done
      'AZ' => 'Aserbaidschan', // Done
      'BA' => 'Bosnien und Herzegowina', // Done
      'BB' => 'Barbados', // Done
      'BD' => 'Bangladesch', // Done
      'BE' => 'Belgien', // Done
      'BF' => 'Burkina Faso', // Done
      'BG' => 'Bulgarien', // Done
      'BH' => 'Bahrain', // Done
      'BI' => 'Burundi', // Done
      'BJ' => 'Benin', // Done
      'BL' => 'Saint-Barthélemy', // Done
      'BM' => 'Bermuda', // Done
      'BN' => 'Brunei Darussalam', // Done
      'BO' => 'Bolivien', // Done
      'BQ' => 'Bonaire', // Done
      'BR' => 'Brazilien', // Done
      'BS' => 'Bahamas', // Done
      'BT' => 'Bhutan', // Done
      'BV' => 'Bouvetinsel', // Done
      'BW' => 'Botswana', // Done
      'BY' => 'Belarus', // Done
      'BZ' => 'Belize', // Done
      'CA' => 'Kanada', // Done
      'CC' => 'Kokosinseln', // Done
      'CD' => 'Kongo (Demokratische Republik)', // Done
      'CF' => 'Zentralafrikanische Republik', // Done
      'CG' => 'Republik Kongo', // Done
      'CH' => 'Schweiz', // Done
      'CI' => 'Cote d\'Ivoire', // Done
      'CK' => 'Cookinseln', // Done
      'CL' => 'Chile', // Done
      'CM' => 'Kamerun', // Done
      'CN' => 'China', // Done
      'CO' => 'Kolombien', // Done
      'CR' => 'Costa Rica', // Done
      'CU' => 'Kuba', // Done
      'CV' => 'Kap Verde', // Done
      'CW' => 'Curacao', // Done
      'CX' => 'Weihnachtsinsel', // Done
      'CY' => 'Zypern', // Done
      'CZ' => 'Tschechische Republik', // Done
      'DE' => 'Deutschland', // Done
      'DJ' => 'Dschibuti', // Done
      'DK' => 'D&auml;nemark', // Done
      'DM' => 'Dominica', // Done
      'DO' => 'Dominikanische Republik', // Done
      'DZ' => 'Algerien', // Done
      'EC' => 'Ecuador', // Done
      'EE' => 'Estland', // Done
      'EG' => '&Auml;gypten', // Done
      'EH' => 'Westsahara', // Done
      'ER' => 'Eritrea', // Done
      'ES' => 'Spanien', // Done
      'ET' => '&Auml;thiopien', // Done
      'FI' => 'Finnland',  // Done
      'FJ' => 'Fidschi', // Done
      'FK' => 'Falklandinseln', // Done
      'FM' => 'Mikronesien', // Done
      'FO' => 'F&auml;r&ouml;er', // Done
      'FR' => 'Frankreich', // Done
      'GA' => 'Gabun', // Done
      'GB' => 'Großbritannien', // Done       
      'GD' => 'Grenada', // Done
      'GE' => 'Georgien', // Done
      'GF' => 'Franz&ouml;sisch-Guayana', // Done
      'GG' => 'Guernsey', // Done
      'GH' => 'Ghana', // Done
      'GI' => 'Gibraltar', // Done
      'GL' => 'Gr&ouml;nland', // Done
      'GM' => 'Gambia', // Done
      'GN' => 'Guinea', // Done
      'GP' => 'Guadeloupe', // Done
      'GQ' => '&Auml;quatorialguinea', // Done
      'GR' => 'Griechenland', // Done
      'GS' => 'S&uuml;d Georgien und die S&uuml;dlichen Sandwichinseln', // Done
      'GT' => 'Guatemala', // Done
      'GU' => 'Guam',
      'GW' => 'Guinea-Bissau', // Done
      'GY' => 'Guyana', // Done
      'HK' => 'Hongkong', // Done
      'HM' => 'Heard und McDonaldinseln', // Done
      'HN' => 'Honduras', // Done
      'HR' => 'Kroatien', // Done
      'HT' => 'Haiti', // Done
      'HU' => 'Ungarn', // Done
      'ID' => 'Indonesien', // Done
      'IE' => 'Irland', // Done
      'IL' => 'Israel', // Done
      'IM' => 'Isle of Man', // Done
      'IN' => 'Indien', // Done
      'IO' => 'Britisches Territorium im Indischen Ozean', // Done
      'IQ' => 'Irak', // Done
      'IR' => 'Iran', // Done
      'IS' => 'Island', // Done
      'IT' => 'Italien', // Done
      'JE' => 'Jersey', // Done
      'JM' => 'Jamaika', // Done
      'JO' => 'Jordanien', // Done
      'JP' => 'Japan', // Done
      'KE' => 'Kenia', // Done
      'KG' => 'Kirgisistan', // Done
      'KH' => 'Kambodscha', // Done
      'KI' => 'Kiribati', // Done
      'KM' => 'Komoren', // Done
      'KN' => 'St. Kitts und Nevis', // Done
      'KP' => 'Nordkorea', // Done
      'KR' => 'S&uuml;dkorea', // Done
      'KW' => 'Kuwait', // Done
      'KY' => 'Caimaninseln', // Done
      'KZ' => 'Kasachstan', // Done
      'LA' => 'Laos', // Done
      'LB' => 'Libanon', // Done
      'LC' => 'St. Lucia', // Done
      'LI' => 'Liechtenstein', // Done
      'LK' => 'Sri Lanka', // Done
      'LR' => 'Liberia', // Done
      'LS' => 'Lesotho', // Done
      'LT' => 'Litauen', // Done
      'LU' => 'Luxemburg', // Done
      'LV' => 'Lettland', // Done
      'LY' => 'Libyen', // Done
      'MA' => 'Marokko', // Done
      'MC' => 'Monaco', // Done
      'MD' => 'Moldawien', // Done
      'ME' => 'Montenegro', // Done
      'MF' => 'Saint-Martin', // Done
      'MG' => 'Madagaskar', // Done
      'MH' => 'Marshallinseln', // Done
      'MK' => 'Mazedonien', // Done
      'ML' => 'Mali', // Done
      'MM' => 'Myanmar', // Done
      'MN' => 'Mongolei', // Done
      'MO' => 'Macao', // Done
      'MP' => 'N&ouml;rdliche Marianen', // Done
      'MQ' => 'Martinique', // Done
      'MR' => 'Mauritanien', // Done
      'MS' => 'Montserrat', // Done
      'MT' => 'Malta', // Done
      'MU' => 'Mauritius', // Done
      'MV' => 'Malediven', // Done
      'MW' => 'Malawi', // Done
      'MX' => 'Mexiko', // Done
      'MY' => 'Malaysia', // Done
      'MZ' => 'Mosambik', // Done
      'NA' => 'Namibia', // Done
      'NC' => 'Neukaledonien', // Done
      'NE' => 'Niger', // Done
      'NF' => 'Norfolkinsel', // Done
      'NG' => 'Nigeria', // Done
      'NI' => 'Nicaragua', // Done
      'NL' => 'Niederlande', // Done
      'NO' => 'Norwegen', // Done
      'NP' => 'Nepal', // Done
      'NR' => 'Nauru', // Done
      'NU' => 'Niue', // Done
      'NZ' => 'Neuseeland', // Done
      'OM' => 'Oman', // Done
      'PA' => 'Panama', // Done
      'PE' => 'Peru', // Done
      'PF' => 'Franz&ouml;sisch-Polynesien', // Done
      'PG' => 'Papua-Neuguinea', // Done
      'PH' => 'Philippinen', // Done
      'PK' => 'Pakistan', // Done
      'PL' => 'Polen', // Done
      'PM' => 'Saint-Pierre und Miquelon', // Done
      'PN' => 'Pitcairninseln', // Done
      'PR' => 'Puerto Rico', // Done
      'PS' => 'Pal&auml;stina', // Done
      'PT' => 'Portugal', // Done
      'PW' => 'Palau', // Done
      'PY' => 'Paraguay', // Done
      'QA' => 'Katar', // Done
      'RE' => 'Réunion', // Done
      'RO' => 'Rom&auml;nien', // Done
      'RS' => 'Serbien', // Done
      'RU' => 'Russland', // Done
      'RW' => 'Ruanda', // Done
      'SA' => 'Saudi Arabien', // Done
      'SB' => 'Solomonen', // Done
      'SC' => 'Seychellen', // Done
      'SD' => 'Sudan', // Done
      'SE' => 'Schweden', // Done
      'SG' => 'Singapur', // Done
      'SH' => 'St. Helena', // Done
      'SI' => 'Slowenien', // Done
      'SJ' => 'Svalbard und Jan Mayen', // Done
      'SK' => 'Slowakei', // Done
      'SL' => 'Sierra Leone', // Done
      'SM' => 'San Marino', // Done
      'SN' => 'Senegal', // Done
      'SO' => 'Somalia', // Done
      'SR' => 'Suriname', // Done
      'SS' => 'S&uuml;dsudan', // Done
      'ST' => 'Sao Tome und Pricipe', // Done
      'SV' => 'El Salvador', // Done
      'SX' => 'Sint Maarten', // Done
      'SY' => 'Syrien', // Done
      'SZ' => 'Swasiland', // Done
      'TC' => 'Turks- und Caicosinseln', // Done
      'TD' => 'Tschad',// Done
      'TF' => 'Franz&ouml;sische S&uuml;d- und antarktisgebiete', // Done
      'TG' => 'Togo', // Done
      'TH' => 'Thailand', // Done
      'TJ' => 'Tadschikistan', // Done
      'TK' => 'Tokelau', // Done
      'TL' => 'Osttimor', // Done
      'TM' => 'Turkmenistan', // Done
      'TN' => 'Tunisien', // Done
      'TO' => 'Tonga', // Done
      'TR' => 'T&uuml;rkei', // Done
      'TT' => 'Trinidad und Tobago', // Done
      'TV' => 'Tuvalu', // Done
      'TW' => 'Taiwan', // Done
      'TZ' => 'Tansania', // Done
      'UA' => 'Ukraine', // Done
      'UG' => 'Uganda', // Done
      'UM' => 'United States Minor Outlying Islands', // Done
      'US' => 'Vereinigte Staaten von Amerika', // Done
      'UY' => 'Uruguay', // Done
      'UZ' => 'Usbekistan', // Done
      'VA' => 'Vatikanstadt', // Done
      'VC' => 'Venezuela', // Done
      'VG' => 'Britische Jungferninseln', // Done
      'VI' => 'Amerkikanische Jungferninseln', // Done
      'VN' => 'Vietnam', // Done
      'VU' => 'Vanuatu', // Done 
      'WF' => 'Wallis und Futuna', // Done
      'WS' => 'Samoa', // Done
      'YE' => 'Jemen', // Done
      'YT' => 'Mayotte', // Done
      'ZA' => 'S&uuml;dafrica',
      'ZM' => 'Sambia', // Done
      'ZW' => 'Simbabwe' // Done
      ),
    'date' => array(
      'month_1' => 'Januar',
      'month_2' => 'Februar',
      'month_3' => 'M&auml;rz',
      'month_4' => 'April',
      'month_5' => 'Mai',
      'month_6' => 'Juni',
      'month_7' => 'Juli',
      'month_8' => 'August',
      'month_9' => 'September',
      'month_10' => 'Oktober',
      'month_11' => 'November',
      'month_12' => 'Dezember',
      'day_1' => 'Montag',
      'day_2' => 'Dienstag',
      'day_3' => 'Mittwoch',
      'day_4' => 'Donnerstag',
      'day_5' => 'Freitag',
      'day_6' => 'Samstag',
      'day_7' => 'Sonntag'
      )
  );

?>