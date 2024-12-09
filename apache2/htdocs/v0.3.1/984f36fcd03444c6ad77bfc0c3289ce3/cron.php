<?php
/**
 * @author: Edilson D. Mucanze
 * @email: edilsonhmberto@gmail.com
 * @contacto: +258 84 821 3574
 * @date: Dezembro de 2019
 * @Projecto: Sistema de Monitoria de Projecto
 * @Base: MiniCrafted APi
 */

/** Connect Sistem backEnd*/

include_once('../system/config.php');
require_once('../system/BD.class.php');
require_once('MailNotify.php');
include_once('template.php');
require '../system/api/vendor/autoload.php';

//DateTime para Portugues.
date_default_timezone_set('Africa/Maputo');
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

$db = BD::conn();
header('Content-Type:' . 'application/json');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mailNotif = new MailNotify($db);

$to = 'edilsonhmberto@gmail.com';

$date = time();
$dataIssues = '';
$issues = array();
foreach ($mailNotif->notifySMP() as $key => $issue) {

   $datediff = strtotime($issue->due_date) - $date;
   $datediff = round($datediff / (60 * 60 * 24));

   $issue->datediff = $datediff;

   if($datediff <= 20 && $datediff >= -5){
      $isNotified = $mailNotif->isNotified("issue_id = ?", [$issue->id]);

      if($isNotified){
         $issue->isNotified = 'Notified';
      }else{
         $issue->isNotified = 'ToNotify';
      }
      $issues[$issue->email_to][] = $issue;
   }
}

print_r($issues);
return;

$mail_to = array();
$tasks = '';
foreach ($issues as $key => $issue) {
   foreach ($issues[$key] as $userIssues) {
      $name = $userIssues->name;
      $email_to = $userIssues->name;

      $tasks .= "<tr>
                  <td align='left'>
                     <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                        <tbody>
                        <tr>
                           <hr>
                           <li style='list-style: none'>#Tarefa: <span style='font-weight: 600;'>" . $userIssues->subject . "</span></li>
                           <li style='list-style: none'>#Atribuída a si no dia: <b>" . utf8_encode(strftime("%d %B %Y", strtotime($userIssues->created_on))) . "</b> - #A terminar: <b>" . utf8_encode(strftime("%d %B %Y", strtotime($userIssues->due_date))) . "</b></li>
                           <li style='list-style: none; margin-bottom: 8px'>#Atribuída por: <b>" . $userIssues->author_name . "</b></li>
                        </tr>
                        <tr><td> <a href='http://18.219.77.220/issues/$userIssues->id' target='_blank'>Ir para Actividade</a> </td></tr>
                        </tbody>
                     </table>
                  </td>
                  </tr>";
   }
   $task_num = sizeof($issues[$key]);
   $message = template($name, $tasks, $task_num);

   echo $message;
   // sendMain($message, 'edilsonhmberto@gmail.com', $name);

   $tasks = '';
}

return;

function sendMain($message, string $email_to, $name){

   if($email_to == null){
      return "Error, Email is null";
   }
   // Instantiation and passing `true` enables exceptions
   $mail = new PHPMailer(true);

   try {
      //Server settings
      $mail->SMTPDebug = 2;                                       // Enable verbose debug output
      $mail->isSMTP();                                            // Set mailer to use SMTP
      $mail->Host       = 'mail.progresso.co.mz';  // Specify main and backup SMTP servers
      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail->Username   = 'sistemademonitoria@progresso.co.mz';                     // SMTP username
      $mail->Password   = 'sdm2018';                               // SMTP password
      $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
      $mail->Port       = 587;                                    // TCP port to connect to

      $mail->SMTPOptions = array(
         'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
         )
      );

      //Recipients
      $mail->setFrom('sistemademonitoria@progresso.co.mz', 'Associação Progresso');
      $mail->addAddress($email_to, $name);     // Add a recipient // Name is optional
      $mail->addReplyTo('sistemademonitoria@progresso.co.mz', 'Associação Progresso');
      //  $mail->addCC('cc@example.com');
      //  $mail->addBCC('bcc@example.com');

      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Notificação - SMP '.date('M-m-Y H:s');
      $mail->Body    = $message;
      //  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      $mail->CharSet = 'UTF-8';
       $mail->send();
      echo 'Message has been sent to '.$name.'<br>';
   } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
   }
}



