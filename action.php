<?php
  function check_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  $message ="";
  $sent = false;
  $message = check_input($_POST["contact_message"]);
  $request = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LcY-8wZAAAAAIwa_zq163QNrn_rP8ZBg_c7kAyg&response=".$_POST["token"]);
  $request = json_decode($request);
  if($request->success == true && $request->score >= 0.5){
        $sent = mail("fberger@felixberger.de","Website-Kontakt", $message, "From: Webmaster <fberger@felixberger.de>");
        if($sent){
          ?>
          <html lang="de">
            <head>
              <title>Kontakt</title>
              <meta name="viewport" content="width=device-width, inital-scale=1.0">
              <meta charset="utf-8">
              <meta name="author" content="Felix Berger">
              <meta name="msapplication-TileColor" content="#ffffff">
              <meta name="msapplication-config" content="/favicon/browserconfig.xml">
              <meta name="theme-color" content="#ffffff">
              <meta http-equiv="refresh" content="5; URL=https://felixberger.de/index.html"
              <link rel="stylesheet" href="style.css" type="text/css">
              <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
              <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
              <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
              <link rel="manifest" href="/favicon/site.webmanifest">
              <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#757575">
              <link rel="shortcut icon" href="/favicon/favicon.ico">
              <link rel="stylesheet" href="style.css" type="text/css">
              <link href="https://fonts.googleapis.com/css?family=Cardo:400,700|Oswald" rel="stylesheet">
            </head>
            <body>
              <main>
          <div class="contact_parent">
             <div class="contact_child">
                <h1 class="contact">Danke. Deine Nachricht wurde erhalten.</h1>
                <h2 id="contact2">Hast du Kontaktinformationen angegeben, erh&auml;lst du in K&uuml;rze eine Antwort.</h2>
             </div>
          </div>
        </main>
      </body>
      </html>
  <?php }
} else {
?>
<html lang="de">
<head>
  <title>Kontakt</title>
  <meta name="viewport" content="width=device-width, inital-scale=1.0">
  <meta charset="utf-8">
  <meta name="author" content="Felix Berger">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-config" content="/favicon/browserconfig.xml">
  <meta name="theme-color" content="#ffffff">
  <meta http-equiv="refresh" content="5; URL=https://felixberger.de/index.html"
  <link rel="stylesheet" href="style.css" type="text/css">
  <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
  <link rel="manifest" href="/favicon/site.webmanifest">
  <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#757575">
  <link rel="shortcut icon" href="/favicon/favicon.ico">
  <link rel="stylesheet" href="style.css" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Cardo:400,700|Oswald" rel="stylesheet">
</head>
<body>
  <main>
<div class="contact_parent">
 <div class="contact_child">
    <h1 class="contact">Da lief was schief.</h1>
    <h2 id="contact2">Versuche es später noch einmal.</h2>
 </div>
</div>
</main>
</body>
</html>
<?php } ?>
