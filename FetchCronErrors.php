<?PHP
/**
 * Limitations:
    commands are assumed to not have '#' or ';' or '2>' or '&1' in them
    2>&1 not supported - as these files will have info/debug messages as well.

 * Expectation:
    Script will return error messages generated and saved in cron jobs' error files, along with file name in a string. This string should be mailed and worked on priority.
 */

function fetch_file_errors($line) {
    if ( strpos( $line, '2>' ) === false || strpos( $line, '&1' ) !== false ) {  // Filtering jobs, where there is no error redirection.
        return '';                                                               // Ignoring jobs, where errors are redirected to info file.
    }
    list( $tmp, $file) = explode('2>', $line);
    $cat = `cat $file 2> /dev/null`;
    if ( strlen($cat) > 0 ) {
        return "<b>File $file \thas Following Error(s):</b>\n\n$cat\n\n";
    }
    return '';
}

$x = `crontab -l | grep -v '^\s*#' | grep -v '^\s*$'`;
//$x = `cat crontab_office | grep -v '^\s*#' | grep -v '^\s*$'`;

$lines = explode("\n", trim($x));
$message = '';

foreach ( $lines as $line ) {
    $pos = strpos($line, '#');      // Strip comment part of the line.
    if( $pos !== false ) {
        $line = substr($line, 0, $pos);
    }
    if ( strpos($line, ';') ) {
        $splits = explode(';', $line);
        foreach ( $splits as $line ) {
            $message .= fetch_file_errors($line);
        }
    } else {
        $message .= fetch_file_errors($line);
    }
}

echo "Subject: Cron Errors\n\n" . $message;
