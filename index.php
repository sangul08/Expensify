<?php
    $url = 'https://www.expensify.com/api';
    session_name("expensify_user");
    session_start();
    if(isset($_POST['logout'])){
        unset($_SESSION);
        session_destroy();
        header("Location:index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expensify Take-Home Challenge</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
</head>
<body>
    <div id=<?php echo !isset($_SESSION["authToken"]) ? "loginContent" : "logoutContent" ?>>
        <?php
            if(!isset($_SESSION["authToken"])){
                echo '<h1>Hello Guest</h1>';
                echo '<form method="post" id="login-form" action="">'
                        . '<p>Partner Name:</p><input type="text" name="partnerName" placeholder="Enter your Partner Name" value="applicant"/>'
                        . '<p>Password:</p><input type="password" name="partnerPassword" placeholder="Enter password" value="d7c3119c6cdab02d68d9" />'
                        . '<p>Partner User ID:</p><input type="text" name="partnerUserID" placeholder="Enter your partner user ID" value="expensifytest@mailinator.com" />'
                        . '<p>Partner User Secret:</p><input type="password" name="partnerUserSecret" placeholder="Enter your partner user secret" value="hire_me" />'
                        . '<input type="submit" id="loginBtn"  name="login" value="LOGIN" />'
                        . '</form>';
            }else {
                echo "<p>".(empty($_SESSION['email']) ? 'Guest' : $_SESSION['email']). "</p>";
                echo ' <form method="post" id="logout-form" action="">'
                        . '<input type="submit" name="logout" value="Logout" id="logoutBtn" />'
                     .'</form>';
            }
            echo "</div>";
            if(isset($_SESSION["authToken"])) {
                 $ch = curl_init();
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                 curl_setopt($ch, CURLOPT_URL, $url . '?command=Get&authToken=' . $_SESSION['authToken'] . '&returnValueList=transactionList');
                 $response = curl_exec($ch);
                 if ($response === false)
                     $response = curl_error($ch);
                 else {
                     echo '<div id="transactionTable">'
                         . '<h3>Transactions:</h3>'
                         . '<table>'
                         . '<thead>'
                         . '<tr>'
                         . '<th>Transaction Date</th>'
                         . '<th>Merchant</th>'
                         . '<th>Amount</th>'
                         . '</tr>'
                         . '</thead>';
                     $obj = json_decode($response, true);
                     if(array_key_exists('transactionList', $obj)){
                         $transactions = $obj['transactionList'];
                         if (count($transactions) > 0) {
                             foreach ($transactions as $transaction) {
                                 echo "<tr>"
                                     . "<td>{$transaction["created"]}</td>"
                                     . "<td>" . substr($transaction["merchant"], 0, 15) . "</td>"
                                     . "<td>{$transaction["amount"]}</td>"
                                     . "</tr>";
                             }
                         }
                     } else {
                         echo "<tr><td>No transactions found!</td></tr>";
                     }
                     echo '</tbody></table></div>';
                 }
                 echo '<div id="transactionForm">'
                            .'<h3>Add New Transaction: </h3>'
                            .'<form method="post" id="createTransaction-form" action="">'
                                .'<div><p>Enter the transaction date</p><input type="text" name="merchant" placeholder="YYYY-MM-DD" value=""/></div>'
                                .'<div><p>Enter the merchant name</p><input type="text" name="merchant" placeholder="" value=""/></div>'
                                .'<div><p>Enter the transaction amount</p><input type="text" name="amount" placeholder="" value="" /></div>'
                                .'<input type="submit" id="transactionBtn" />'
                            .'</form>'
                     .'</div>';
             }
             ?>

    <!-- Javascript Files, we've included JQuery here, feel free to use at your discretion. Add whatever else you may need here too. -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="script.js"></script>

</body>
</html>
