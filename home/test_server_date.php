<?php

$commandLines =<<<'EOD'
time()
date('Y-m-d', strtotime('1970-01-01') )
date('Y-m-d', strtotime('2013/1/2') )
date('Y-m-d', strtotime('3/4/2013') )
date('Y-m-d', strtotime('3/4/13') )
'--------------------------------------------------'
ini_get('date.timezone')
date('Y-m-d (w) H:i:s', time() )
date('Y-m-d', 0 )
date('Y-m-d', strtotime('') )
date('Y-m-d', strtotime('99/99/99') )
'--------------------------------------------------'
ini_set('date.timezone','America/Los_Angeles')
ini_get('date.timezone')
date('Y-m-d (w) H:i:s', time() )
date('Y-m-d', strtotime('1970-01-01') )
date('Y-m-d', 0 )
date('Y-m-d', strtotime('') )
date('Y-m-d', strtotime('99/99/99') )
EOD;

?>

<style type="text/css">
    td { padding:5px; }
</style>

<table>
<?php
    foreach( explode("\n",$commandLines) as $key) {
        if ( !$key ) {
            continue;
        }
        echo "<tr><td>{$key}</td><td> = </td><td>";
        eval('echo( '. $key .' );');
        echo "</td></tr>";
    }
?>
</table>