<!--/**
* Created by PhpStorm.
* User: Vitaliy ZALYOTIN <mr.zalyotin@gmail.com>
* Site: javelin.mk.ua
* Date: 18.02.2017
* Time: 15:54
*/-->
<!DOCTYPE html>
<html>
<head>
    <title>Client</title>
    <link rel="stylesheet" href="css/reset.css" type="text/css"/>
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">

    <script src="js/jquery-3.1.1.min.js"> </script>
    <script src="js/jquery-ui-1.8.16.custom.min.js"></script>

    <style>
        body {
            padding-top: 40px;
        }
        #main {
            margin-top: 80px ;
            text-align: center;
        }
    </style>
</head>
<table style="margin-left: auto; margin-right: auto;">
    <tbody>
    <tr>
        <td style="background-color: #ffbe1c; text-align: center;">
            <div class="topbar">
                <div class="fill"><span style="color: #ff0000;"><a class="brand" href="index.php">Login or register</a></span></div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div id="main" class="container"><form class="form-stacked" action="login.php" method="post">
                    <div class="row">
                        <div class="span5 offset5"><label for="login_username">Username:</label>
                            <input id="login_username" name="login_username" type="text" placeholder="username" /> <br /><br /> <label for="login_password">Password:</label>
                            <input id="login_password" name="login_password" type="password" placeholder="password" /></div>
                    </div>
                    <br /><br />
                    <div class="actions"><button class="btn primary large" name="login_submit" type="submit">Login or register</button></div>
                </form></div>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>


<?php




?>