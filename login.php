<?php

session_start();

include "db.php";

$message = "";

if (isset($_GET["register"])) {

    if ($_GET["register"] == "success") {
        $message = "สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ";
    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $sql = $conn->prepare(
        "SELECT id, username, password
         FROM users
         WHERE username = ?"
    );

    $sql->bind_param("s", $username);

    $sql->execute();

    $result = $sql->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: home.php");
            exit();

        } else {

            $message = "รหัสผ่านไม่ถูกต้อง";

        }

    } else {

        $message = "ไม่พบชื่อผู้ใช้นี้";

    }
}

?>

<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>เข้าสู่ระบบ</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .container {
            width: 400px;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #667eea;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #5568d8;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }

        .error {
            color: red;
        }

        .link {
            text-align: center;
            margin-top: 20px;
        }

        .link a {
            color: #667eea;
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>เข้าสู่ระบบ</h1>

    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo $message; ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>ชื่อผู้ใช้</label>

        <input
            type="text"
            name="username"
            placeholder="กรอกชื่อผู้ใช้"
            required
        >

        <label>รหัสผ่าน</label>

        <input
            type="password"
            name="password"
            placeholder="กรอกรหัสผ่าน"
            required
        >

        <button type="submit">
            เข้าสู่ระบบ
        </button>

    </form>

    <div class="link">

        ยังไม่มีบัญชี?

        <a href="register.php">
            สมัครสมาชิก
        </a>

    </div>

</div>

</body>
</html>