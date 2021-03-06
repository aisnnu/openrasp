--TEST--
hook PDO::prepare
--SKIPIF--
<?php
$plugin = <<<EOF
plugin.register('sql', params => {
    assert(params.query == 'SELECT a FROM b WHERE c < :c')
    assert(params.server == 'mysql')
    return block
})
EOF;
include(__DIR__.'/../skipif.inc');
if (!extension_loaded("mysqli")) die("Skipped: mysqli extension required.");
if (!extension_loaded("pdo")) die("Skipped: pdo extension required.");
@$con = mysqli_connect('127.0.0.1', 'root');
if (mysqli_connect_errno()) die("Skipped: can not connect to MySQL " . mysqli_connect_error());
mysqli_close($con);
?>
--INI--
openrasp.root_dir=/tmp/openrasp
openrasp.enforce_policy=Off
--FILE--
<?php
$con = new PDO('mysql:host=127.0.0.1;port=3306', 'root');
$con->prepare('SELECT a FROM b WHERE c < :c')
?>
--EXPECTREGEX--
<\/script><script>location.href="http[s]?:\/\/.*?request_id=[0-9a-f]{32}"<\/script>