<?php
    include 'db_conn.php';

    $query = $_POST['query'];

    mysqli_query($conn, $query);

    mysqli_close($conn);
