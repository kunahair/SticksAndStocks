<?php

/**
 * Created by: Josh Gerlach.
 * Authors: Josh Gerlach
 */

namespace App\Http\Controllers;

use App\Mail\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mail;

class ContactController extends Controller
{

    /**
     * POST request for when a Client submits a Contact Us form
     * Validates input, mails to selected email
     *
     * @param Request $request - POST request data from contact us form
     * @return mixed - Contact View with message of success or failure
     */
    public function email(Request $request) {

        try {
            // Validate the request data
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
                'comments' => 'required'
            ]);

        }catch (ValidationException $validationException)
        {
            //If any validation occurred, send message back to contact page
            return view('contact')->with('message', ["type" => 'error', "message" => 'Field missing from input, please try again']);
        }

        //Get all inputs from user
        $name = $request->input('name');
        $email = $request->input('email');
        $comments = $request->input('comments');

        //Wrap inputs into array to be used by Mailer
        $content = [
          'name' => $name,
            'email' => $email,
            'comments' => $comments
        ];

        var_dump($content);

        try
        {
            //Send email to the selected admin email
            Mail::to('s3453952@student.rmit.edu.au')->send(new ContactForm($content));
        }catch (\Exception $exception)
        {
            //If any validation occurred, send message back to contact page
            return view('contact')->with('message', ["type" => 'error', "message" => 'Unable to send message']);
        }


        //Return view wit success message
        return view('contact')->with('message',
            ["type" => 'success', 'message' => "Thanks for contacting us, will be in touch soon"]);
    }

    /**
     * Return contact page view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view() {
        return view('contact');
    }

}