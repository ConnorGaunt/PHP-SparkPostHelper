<?php 

namespace SparkPostHelper;
use \Exception as Exception;
use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;

class SparkPostHelper extends PHPUnit_Framework_TestCase{
    // Setting up SparkPost
    private $API_KEY = NULL;            // API key generated in SparkPost
    private $sendingDomain = NULL;      // Sending domain set in SparkPost
    private $sendingEmail = "no-reply"; // prefix to email eg: no-reply@sendingdomain.com
    
    // Setting up email for sending
    private $emailSubject = "";         // Subject Line for email
    private $emailReplyTo = "";         // New reply to email
    private $emailFromName= "";         // Name that Appears in Email (From App/Company Name)
    private $emailRecipients = array(); // All Recipients to the email
    private $emailHTML = "";            // HTML for the email client to render
    private $backupEmailText = "";      // Backup text in-case Email doesn't support HTML


    // Function to check Parameter
    private function checkParamater($parameter, $errorParamFormat, $varType){
        
        // Creating function variables
        $returnArray = array(); // Array to return errors
        $paramPasseed = false;  // Assume passed parameter has already failed.

        // Run separate checks depending on the expected variable type.
        switch ($varType) {

            // If expecting a Sting
            case 'String':
                // Check the parameter has been supplied
                if(!isset($parameter)){
                    $returnArray['errorMessage'] = "{$errorParamFormat} must be supplied";
                    break; // if error has been found, break from checks and return
                }
                // Checking the parameter is a string
                if(!is_string($parameter)){
                    // if not string grab variable type supplied and pass to error message
                    $varType = gettype($parameter);;
                    $returnArray['errorMessage'] = "{$errorParamFormat} must be of type String. ({$varType}) was given";
                    break; // if error has been found, break from checks and return
                } 
                // Checking the subject line is not an empty string
                if(trim($parameter) === ""){
                    $returnArray['errorMessage'] = "{$errorParamFormat} can not be an empty string";
                    break; // if error has been found, break from checks and return
                }
                $paramPasseed = true;
                break;

            // If expecting an Array
            case 'Array':
                // Check parameter was actually passed.
                if(!isset($parameter)){
                    $returnArray['errorMessage'] = "{$errorParamFormat} are required";
                    break; // if error has been found, break from checks and return
                }
                // Check that the variable supplied was of type Array
                if(!is_array($parameter)){
                    // if not Array grabbing type form supplied variable for error string
                    $varType = gettype($parameter);
                    $returnArray['errorMessage'] = "{$errorParamFormat} must be of type Array. ({$varType}) was given";
                    break; // if error has been found, break from checks and return
                }
                // Checking the Array supplied was not empty
                if(empty($parameter)){
                    $returnArray['errorMessage'] = "{$errorParamFormat} can not be empty. At least one recipient must be given";
                    break; // if error has been found, break from checks and return
                }
                $paramPasseed = true;
                break;

            case 'Email':

                // Check email was actually passed.
                if(!isset($parameter)){
                    $returnArray['errorMessage'] = "{$errorParamFormat} is required";
                    break; // if error has been found, break from checks and return
                }
                // Check that the variable supplied was of type Sting
                if(!is_string($parameter)){
                    // if not String grabbing type form supplied variable for error string
                    $varType = gettype($parameter);
                    $returnArray['errorMessage'] = "{$errorParamFormat} must be of type String. ({$varType}) was given";
                    break; // if error has been found, break from checks and return
                }
                // Checking the Email supplied was a valid email address
                if(!(bool)filter_var($parameter, FILTER_VALIDATE_EMAIL)){
                    $returnArray['errorMessage'] = "{$errorParamFormat} must be a valid email address";
                    break; // if error has been found, break from checks and return
                }
                $paramPasseed = true;
                break;
        }

        // Set the returning error to the opposite of passedParam as to read correctly
        // Example if passedParam == true then error = true
        $returnArray['error'] = !$paramPasseed;
        // send back error result;
        return $returnArray;

    }


    // Take API, and Sending Domain
    function __construct($API_KEY, $sendingDomain){
        try{
            // Checking parameter API key
            $validAPI_KEY = $this->checkParamater($API_KEY, "API key" , "String");
            // Checking if API key was passed with an error
            if($validAPI_KEY['error']){
                // If error was found, throw error with passed message
                throw new Exception($validAPI_KEY['errorMessage']);
            }

            // Checking parameter sendingDomain
            $validSendingDomain = $this->checkParamater($sendingDomain, "Sending domain" , "String");
            // If sending domain has an error
            if($validSendingDomain['error']){
                // Throw error, with error message
                throw new Exception($validSendingDomain['errorMessage']);
            }

            // If no errors where found, assign to private variables
            $this->API_KEY = $API_KEY;
            $this->sendingDomain = $sendingDomain;

        } catch (Exception $error){
            // If any errors are found with the API key, throw a fatal error with stack trace.
            trigger_error($error->__toString(), E_USER_ERROR);
        }
    }


    // A function to set the recipients of the email
    public function recipients($recipientsArray){
        try{
            // Checking parameter recipientsArray
            $validRecipientsArray = $this->checkParamater($recipientsArray, "Recipients" , "Array");
            // If recipients has an error
            if($validRecipientsArray['error']){
                // Throw error, with error message
                throw new Exception($validRecipientsArray['errorMessage']);
            }

            // Defining PRIVATE empty array for adding recipients too
            $recipients = array();  

            // Loop through given array and apply to private array
            foreach ($recipientsArray as $key => $recipient) {
                // Check if the recipient supplied was a valid email
                if((bool)filter_var($recipient, FILTER_VALIDATE_EMAIL)){
                     // Defining an empty array for this particular recipient info.
                    $recipientArray = array();
                    // Check see if this recipient was given a name
                    if(is_string($key)){
                        // Add name to recipient data.
                        $recipientArray['name'] = $key;
                    }
                    // Add validated email address to given recipient data
                    $recipientArray['address'] = $recipient;
                    // Add particular recipient to the over all recipients
                    array_push($recipients, $recipientArray);
                }
            }

            // Apply all recipients to the private variable ready for sending.
            $this->emailRecipients = $recipients;


        } catch (Exception $error){
            // If any errors are found with the recipients, throw a fatal error with stack trace.
            trigger_error($error->__toString(), E_USER_ERROR);
        }
    }

    // Sets subject line of email
    public function subject($subjectLine){
        try{
            // Checking parameter subjectLine
            $validSubjectLine = $this->checkParamater($subjectLine, "Subject line" , "String");
            // If recipients has an error
            if($validSubjectLine['error']){
                // Throw error, with error message
                throw new Exception($validSubjectLine['errorMessage']);
            }
            // If no errors found assign subject line
            $this->emailSubject = $subjectLine;

        } catch (Exception $error){
            // If any errors are found with the subject, throw a fatal error with stack trace.
            trigger_error($error->__toString(), E_USER_ERROR);
        }
    }

    public function html($html){
        try{
            // Checking parameter html
            $validHTML = $this->checkParamater($html, "HTML" , "String");
            // If recipients has an error
            if($validHTML['error']){
                // Throw error, with error message
                throw new Exception($validHTML['errorMessage']);
            }
            // if no errors are found assign html
            $this->emailHTML = $html;

        } catch (Exception $error){
            // If any errors are found with the HTML, throw a fatal error with stack trace.
            trigger_error($error->__toString(), E_USER_ERROR);
        }
    }

    public function text($text){
        try{
            // Checking parameter text
            $validText = $this->checkParamater($text, "Text" , "String");
            // If recipients has an error
            if($validText['error']){
                // Throw error, with error message
                throw new Exception($validText['errorMessage']);
            }
            // if no errors are found assign text
            $this->backupEmailText = $text;

        } catch (Exception $error){
            // If any errors are found with the HTML, throw a fatal error with stack trace.
            trigger_error($error->__toString(), E_USER_ERROR);
        }
    }

    public function replyTo($replyEmail){
        try{
            // Checking parameter replyEmail
            $validReplyEmail = $this->checkParamater($replyEmail, "Reply-To email" , "Email");
            // If email has an error
            if($validReplyEmail['error']){
                // Throw error, with error message
                throw new Exception($validReplyEmail['errorMessage']);
            }
            // if no errors are found assign new reply email
            $this->emailReplyTo = $replyEmail;

        } catch (Exception $error){
            // If any errors are found with the HTML, throw a fatal error with stack trace.
            trigger_error($error->__toString(), E_USER_ERROR);
        }
    }

    public function fromName($fromName){
        try{
            // Checking parameter text
            $validFromName = $this->checkParamater($fromName, "From name" , "String");
            // If from name has an error
            if($validFromName['error']){
                // Throw error, with error message
                throw new Exception($validFromName['errorMessage']);
            }
            // if no errors are found assign new reply email
            $this->emailFromName = $fromName;

        } catch (Exception $error){
            // If any errors are found with the HTML, throw a fatal error with stack trace.
            trigger_error($error->__toString(), E_USER_ERROR);
        }
    }

    // Function to send the email.
    public function send(){
        try{
            // Checking required fields are not empty/default
            // Checking Recipients is not default, if so throw error with relevant function.
            if(empty($this->emailRecipients)){
                throw new Exception("At least one recipient is required to send an email using SparkPost. Use ->recipients() ");
            }
            // Checking subject line is not default, if so throw error with relevant function.
            if($this->emailSubject === ""){
                throw new Exception("A Subject line is required to send an email using SparkPost. Use ->subject() ");
            }
            // Checking html is not default, if so throw error with relevant function.
            if($this->emailHTML === ""){
                throw new Exception("Email can not be empty. HTML is required to send an email using SparkPost. Use ->html() ");
            }
            // Checking backup text is not default, if so throw error with relevant function.
            if($this->backupEmailText === ""){
                throw new Exception("Backup text is required to send an email using SparkPost for clients who don't use HTML. Use ->text() ");
            }

            //Composing the sending email address
            $sendingEmailAddress = $this->sendingEmail."@".$this->sendingDomain;


            // Defining email structure
            $emailArray = array(
                "recipients" => $this->emailRecipients,
                "content" => array(
                    "from" => array(
                        "email" => $sendingEmailAddress,
                    ),
                    "subject" => $this->emailSubject,
                    "html" => $this->emailHTML,
                    "text" => $this->backupEmailText,
                ),
            );

            // If a reply email was set add it to the email structure
            if($this->emailReplyTo !== ""){
                $emailArray['content']['reply_to'] = $this->emailReplyTo;
            }
            // If an from name was set add it to the email structure
            if($this->emailFromName !== ""){
                $emailArray['content']['from']['name'] = $this->emailFromName;
            }
            
            // // Start to send email --------------

            // Begin cURL with SparkPost API endpoint for sending emails.
            $cURL = curl_init("https://api.sparkpost.com/api/v1/transmissions");
            curl_setopt_array($cURL, array(
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: ".$this->API_KEY,
                ),
                CURLOPT_POST => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POSTFIELDS => json_encode($emailArray),
            ));
            // Returning the information from the SparkPost API
            $promise = curl_exec($cURL);
            // Closing the connection between SparkPost
            curl_close($cURL);

            // Resetting all the variables so user can send another email to different users after this send.
            $this->emailSubject = "";
            $this->emailReplyTo = "";
            $this->emailFromName= "";
            $this->emailRecipients = array();
            $this->emailHTML = "";
            $this->backupEmailText = "";

            return json_decode($promise,true);


        } catch (Exception $error){
            // If any errors have been found, display them while throwing a fatal error;
            trigger_error($error->getMessage(), E_USER_ERROR);
        }

    }
}
