$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'shout';
try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
if($_POST['name']) {
    $name         = $_POST['name'];
    $message    = $_POST['message'];
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO shoutbox (date_time, name, message)
            VALUES (NOW(), :name, :message)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    if ($stmt->execute()) {
        populate_shoutbox();
    }
}
}
catch(PDOException $e) {
    echo $e->getMessage();
}
if($_POST['refresh']) {
    populate_shoutbox();
}

function populate_shoutbox() {
    global $dbh;
    $sql = "select * from shoutbox order by date_time desc limit 10";
    echo '<ul>';
    foreach ($dbh->query($sql) as $row) {
        echo '<li>';
        echo '<span class="date">'.date("d.m.Y H:i", strtotime($row['date_time'])).'</span>';
        echo '<span class="name">'.$row['name'].'</span>';
        echo '<span class="message">'.$row['message'].'</span>';
        echo '</li>';
    }
    echo '</ul>';
}
