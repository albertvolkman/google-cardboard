<?php
require_once 'vendor/autoload.php';

define('GMAIL_USER', 'user@gmail.com');
define('GMAIL_PASS', 'password');
define('CARDBOARD_URL', 'https://store.google.com/product/61378afccd1eb7d5');

if (cardboard_is_available()) {
  $email_send_status = cardboard_send_notification();
  if ($email_send_status) {
    print "Email sent.\n";
  }
  else {
    print "Error sending email.\n";
  }
}

/**
 * Checks Cardboard availability status.
 *
 * @return bool
 *   Returns true if Cardboard is available.
 */
function cardboard_is_available() {
  $dom = new DOMDocument();
  $html = @$dom->loadHTMLFile(CARDBOARD_URL);
  $classname = 'button-text';
  $finder = new DomXPath($dom);
  $nodes = $finder->query("//*[contains(@class, '$classname')]");

  return ('Not available' != $nodes->item(1)->textContent);
}

/**
 * Sends notification email.
 *
 * @return bool
 *   Success or failure of email send.
 */
function cardboard_send_notification() {
  $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
    ->setUsername(GMAIL_USER)
    ->setPassword(GMAIL_PASS);
  
  $mailer = Swift_Mailer::newInstance($transport);
  
  $message = Swift_Message::newInstance('STAR WARS CARDBOARD')
    ->setFrom(array(GMAIL_USER => GMAIL_USER))
    ->setTo(array(GMAIL_USER))
    ->setBody('<p><a href="' . CARDBOARD_URL . '">Get it while it\'s hot.</a>', 'text/html');

  return $mailer->send($message);
}
