<?php
/**
 * Created by PhpStorm.
 * User: mavericks
 * Date: 2/20/18
 * Time: 10:26 AM
 */

namespace App\Helper;


use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Log;

class AppMailer
{
    protected $mailer;
    protected $fromAddress = 'Site Email';
    protected $fromName = 'Site Name';
    protected $to;
    protected $subject;
    protected $view;
    protected $data = [];
    protected $admin = [
        "hayroyalconsult@gmail.com",
        "Okesulaiman1@gmail.com",
        "info@cryptotradingmatrix.com",
        "support@cryptotradingmatrix.com",
        "admin@cryptotradingmatrix.com"];

//    private function Logger()
//    {
//        return new Logger();
//    }
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function activateUser($user)
    {
        $this->to = $user->email;
        $this->subject = "Activate Your Crypto Trading Matrix Account";
        $this->view = 'User.Email.activate';
        $this->data = compact('user');
        return $this->deliver();
    }

    public function sendRawMail($emails, $msg, $sub){
        $this->to = $emails;
        $this->subject = $sub;
        //$this->view = 'Email.all';
        $this->data = compact('msg');

        Log::info('Success ',[$emails]);
        return $this->raw($msg);
    }

    public function sendMail($emails, $msg, $sub)
    {

        $this->to = $emails;
        $this->subject = $sub;
        $this->view = 'Email.all';
        $this->data = compact('msg');
        Log::info('Success ',[$emails]);
        return $this->deliver();
    }

    public function resetPassword($email, $token, $name){
        $this->to = $email;
        $this->subject = 'Reset Your Password';
        $this->view = 'Email.reset';
        $this->data = compact('token','name');
        return $this->deliver();
    }

    public function visitor($name, $email, $sub, $msg){
        $this->to = 'support@cryptotradingmatrix.com';
        $this->subject = $sub;
        $this->view = 'Email.visitor';
        $this->data = compact('name','email','msg');

        return $this->deliver();
    }

    public function deliver()
    {
        try{
            $this->mailer->send($this->view, $this->data, function($message) {
                $message->from($this->fromAddress, $this->fromName)
                    ->to($this->to)->subject($this->subject);
            });

            Log::info('Mail Sent');
            return true;
        }
        catch (\Exception $ex)
        {
            //dd($ex);
//            $this->Logger()->LogError('An Error Occured When Trying to Send Mail',$ex,['to' => $this->to
//                , 'subj' => $this->subject,'data' => $this->data]);
            $this->sendError();
            return false;

        }
    }

    public function raw($msg)
    {
        try{
            $this->mailer->raw($msg, function($message) {
                $message->from($this->fromAddress, $this->fromName)
                    ->to($this->to)->subject($this->subject);
            });

            Log::info('Mail Sent');
            return true;
        }
        catch (\Exception $ex)
        {
            //dd($ex);
//            $this->Logger()->LogError('An Error Occured When Trying to Send Mail',$ex,['to' => $this->to
//                , 'subj' => $this->subject,'data' => $this->data]);
            $this->sendError();
            return false;

        }
    }

    public function sendError($error = null)
    {
        $this->to = $this->admin;
        $this->subject = "Error";
        $this->view = 'Email.notification';
        $msg = "An Error Occurred With ID - $error";
        $this->data = compact('msg');
        Log::info('emailll',$this->admin);

        return $this->deliver();
    }
}