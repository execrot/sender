<?php

declare(strict_types=1);

namespace App\Module\Cli\Controller;

use Exception;
use Throwable;
use PHPMailer\PHPMailer\PHPMailer;
use Light\Core\Worker;
use Light\Model\Exception\CallUndefinedMethod;
use Light\Model\Exception\ConfigWasNotProvided;
use Light\Model\Exception\DriverClassDoesNotExists;
use Light\Model\Exception\DriverClassDoesNotExtendsFromDriverAbstract;

class Message extends Worker
{
  /**
   * @return void
   * @throws CallUndefinedMethod
   * @throws ConfigWasNotProvided
   * @throws DriverClassDoesNotExists
   * @throws DriverClassDoesNotExtendsFromDriverAbstract
   */
  private function queue(): void
  {
    $messages = \App\Model\Message::all(['status' => \App\Model\Message::STATUS_NEW], [], 10);
    foreach ($messages as $message) {
      $message->log = '';
      $message->save();
    }

    foreach ($messages as $message) {
      try {
        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 1;
        $mail->Debugoutput = function (string $debugOutput) use ($message) {
          $message->log .= "\n" . $debugOutput;
          $message->save();
        };

        $mail->isSMTP();
        $mail->CharSet = PHPMailer::CHARSET_UTF8;

        $mail->SMTPAuth = true;
        $mail->SMTPSecure = $message->settings->data['protocol'];
        $mail->Host = $message->settings->data['server'];
        $mail->Port = $message->settings->data['port'];

        $mail->Username = $message->settings->data['username'];
        $mail->Password = $message->settings->data['password'];

        foreach (($message->content['attachments'] ?? []) as $attachment) {
          $mail->addStringAttachment(
            base64_decode($attachment['content']),
            $attachment['name']
          );
        }

        $mail->setFrom(
          $message->settings->data['fromAddress'],
          $message->settings->data['fromName']
        );

        foreach ($message->recipients as $recipient) {
          $mail->addAddress(
            $recipient['address'],
            $recipient['name']
          );
        }

        if ($message->template) {
          $mail->Subject = str_replace(
            array_map(function ($key) {
              return '{{' . $key . '}}';
            }, array_keys($message->args)),
            array_values($message->args),
            $message->template->subject
          );
          $mail->Body = str_replace(
            array_map(function ($key) {
              return '{{' . $key . '}}';
            }, array_keys($message->args)),
            array_values($message->args),
            $message->template->content
          );
          $mail->AltBody = strip_tags($mail->Body);

        } else {
          $mail->Subject = $message->content['subject'];
          $mail->Body = $message->content['body'];
          $mail->AltBody = strip_tags($message->content['body']);
        }

        if (!$mail->send()) {
          throw new Exception("Email message not sent");
        }

        $message->status = \App\Model\Message::STATUS_SENT;
        $message->save();

      } catch (Throwable $e) {
        $message->status = \App\Model\Message::STATUS_FAILED;
        $message->log .= "Throwable: \n\n" . var_export($e, true);
        $message->save();
      }
    }
  }
}