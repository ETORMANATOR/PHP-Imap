<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" ></script>
	<title>TMG Auto Email Sender</title>
    <style>
        h1,h2,h3,label,th,td,p{
            color:white;
        }
        html {
            height: 100%;
            }

            body {
            background-image: radial-gradient(circle farthest-corner at center, #3C4B57 0%, #1C262B 100%);
            }

            .loader {
            position: fixed;
            top: calc(50% - 32px);
            left: calc(50% - 32px);
            width: 64px;
            height: 64px;
            border-radius: 50%;
            perspective: 800px;
            }

            .inner {
            position: absolute;
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            border-radius: 50%;  
            }

            .inner.one {
            left: 0%;
            top: 0%;
            animation: rotate-one 1s linear infinite;
            border-bottom: 3px solid #EFEFFA;
            }

            .inner.two {
            right: 0%;
            top: 0%;
            animation: rotate-two 1s linear infinite;
            border-right: 3px solid #EFEFFA;
            }

            .inner.three {
            right: 0%;
            bottom: 0%;
            animation: rotate-three 1s linear infinite;
            border-top: 3px solid #EFEFFA;
            }

            @keyframes rotate-one {
            0% {
                transform: rotateX(35deg) rotateY(-45deg) rotateZ(0deg);
            }
            100% {
                transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg);
            }
            }

            @keyframes rotate-two {
            0% {
                transform: rotateX(50deg) rotateY(10deg) rotateZ(0deg);
            }
            100% {
                transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg);
            }
            }

            @keyframes rotate-three {
            0% {
                transform: rotateX(35deg) rotateY(55deg) rotateZ(0deg);
            }
            100% {
                transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg);
            }
            }
            #loading-status-text{
                margin-top: 100%;
                color: #C15FD0;
                width: 500px;
            }
    </style>
</head>
<body>
<div class="loader d-none" style="z-index:999999;">
  <div class="inner one"></div>
  <div class="inner two"></div>
  <div class="inner three"></div>
  <h3 id="loading-status-text"></h3>
</div>
<?php $increNum = 1; ?>
    <div class="container" style="margin-top:2%;width: 90% !important;
    max-width: 100% !important;">
            <center>
                <h2><b>Email Bulk Checker</b></h2>
            </center>
            <div style="border: 1px solid #3479B7;margin-bottom:50px;" id="smtp-Setup">
                <div style="padding:10px;">
                    <div class="form-group row">
                            <h3 class="col-12 text-center">SMTP SETUP</h3>
                            <label class="col-sm-2 col-form-label">SMTP Server</label>
                            <div class="col-sm-10">
                                <select id="stmpserverselection" class="custom-select custom-select-lg" style="border: 1px solid #ccc;font-size:1vw;color: #555;" >
                                    <option value="Emptyvalue"selected>Select smtp server</option>
                                    <option value="Gmail">Gmail (G-suite)</option>
                                    <option value="Ops">Internal Email(SMTP)</option>
                                </select>
                            </div>
                    </div>
                    <div class="form-group row">
                            <label for="SenderE" class="col-sm-2 col-form-label">Email Sender</label>
                            <div class="col-sm-10">
                            <input type="email" class="form-control" id="SenderE" placeholder="Email Sender" required>
                            </div>
                    </div>
                    <div class="form-group row">
                        <label for="SenderP" class="col-sm-2 col-form-label">Email Password</label>
                        <div class="col-sm-10">
                        <input type="password" class="form-control" id="SenderP" placeholder="Email Password" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-primary" id="Credential-Checker">Check Email Credential</button>
                        </div>
                    </div>
                </div>
            </div>

            <div style="border: 1px solid #6EAC47;margin-bottom:50px;" class="d-none" id="email-Setup">
                <div style="padding:10px;">
                    <div class="form-group row">
                    <h3 class="col-12 text-center">EMAIL SETUP</h3>
                        <label for="SubjectT" class="col-sm-2 col-form-label">Subject</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="SubjectT" placeholder="Subject Title" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="emailmessage" class="col-sm-2 col-form-label">Message</label>
                        <div class="col-sm-10">
                        <textarea style="color:black;" class="form-control" name="emailmessage" id="emailmessage" rows="6" ></textarea>
                        
                        <p style='background-color:white;color:black;'> Note: $DataFname = First Name | $DataLname = Last Name | $DataEmail = Email Address | $DataCode = Draw Code"</p>
                        </div> 
                    </div>
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <?php $increTol = 0; foreach($allinfo as $eachdata){?>
                        <?php $increTol ++; } ?>
                            <h1>Total Receiver Email: <b id="totalEmail"><?=$increTol ?></b></h1>
                        
                            <input type="submit" class="btn btn-primary " style="font-size:1vw" name="submit" value="SEND NOW" id="sendNowbtn">
                            <?php if($this->session->flashdata('stmpSetup'))  { ?>
                                <br>
                                <br>
                                <p class="text-danger"><?=$this->session->flashdata('stmpSetup')?></p>
                            <?php  } ?>
                        </div>
                    </div>
                </div>
            </div>
        <div style="border: 1px solid #ED7C31;margin-bottom:50px;" class="" id="email-Status">
            <div style="padding:10px;">
            <h3 class="col-12 text-center">SENDING STATUS</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Email</th>
                        <th scope="col">Code</th>
                        <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $increNumClass = 1; foreach($allinfo as $eachdata){
                            ?>
                        <tr class="eachTR">
                            <th scope="row"><?= $increNumClass; ?></th>
                            <td class="sendingFname<?= $increNumClass; ?>"><?=  $eachdata[0]; ?></td>
                            <td class="sendingLname<?= $increNumClass; ?>"><?=  $eachdata[1]; ?></td>
                            <td class="sendingEmail<?= $increNumClass; ?>"><?=  $eachdata[2]; ?></td>
                            <td class="sendingCode<?= $increNumClass; ?>"><?=  $eachdata[3]; ?></td>
                            <td class="StatusClass sendingstatus<?= $increNumClass; ?>"><b>Stanby ...</b> </td>
                            
                        </tr>
                        <?php $increNumClass ++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

</body>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?= base_url() ?>plugins/tinymce/tinymce.min.js"></script>
<script src="<?= base_url() ?>plugins/js/tinymce_editor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
<script>
   var SMTPServer,EmialSender,EmailPassword;
   var SubjectTitle, EmailMessage;
   var finalMessage, finalSubjectTitle;
   
   $( document ).ready(function() {
    $("#sendNowbtn").on("click", function() { 
        $("#email-Setup").addClass("d-none");
        $(".loader").removeClass("d-none");
        var select_imap_server = $("#stmpserverselection").val();
        alertify.dismissAll();
        var completeinput = true;
        SubjectTitle = $("#SubjectT").val();
        EmailMessage = tinymce.get('emailmessage').getContent();

        if (SubjectTitle.trim() === "" || SubjectTitle.length < 1) {
            alertify.error('Please check your subject.');
            completeinput = false;
        }
        if(EmailMessage.trim() === "" || EmailMessage.length < 2){
            alertify.error('Please check your message.');
            completeinput = false;
        }
        if(completeinput){
            if(select_imap_server == "Ops" || select_imap_server == "Gmail"){
                $("#loading-status-text").removeAttr('style');
                $("#loading-status-text").attr("style","margin-left:-50%;");
                $("#loading-status-text").text("Sending...");

                var sendCount = 1;
                $( ".eachTR" ).each(function( index ) {
                            var postionIng = index + 1;
                            finalMessage = EmailMessage.replaceAll("$DataFname", $(".sendingFname"+ postionIng+"").text()).replaceAll("$DataLname", $(".sendingLname"+ postionIng+"").text()).replaceAll("$DataEmail", $(".sendingEmail"+ postionIng+"").text()).replaceAll("$DataCode", $(".sendingCode"+ postionIng+"").text()).replaceAll("<p>&nbsp;</p>", "<p style='padding-top:4px;padding-bottom:4px;'></p>");
                            finalSubjectTitle = SubjectTitle.replaceAll("$DataFname", $(".sendingFname"+ postionIng+"").text()).replaceAll("$DataLname", $(".sendingLname"+ postionIng+"").text()).replaceAll("$DataEmail", $(".sendingEmail"+ postionIng+"").text()).replaceAll("$DataCode", $(".sendingCode"+ postionIng+"").text());
                            $(".sendingstatus"+ postionIng+"").attr("style", "color:#FE981F !important");
                            $(".sendingstatus"+ postionIng+"").text("Sending ...");
                           
                            $.ajax({
                                url: select_imap_server,
                                type: "POST",
                                data: {
                                    emailReceiver: $(".sendingEmail"+ postionIng+"").text(),
                                    emailSubject: finalSubjectTitle,
                                    emailHtmlMessage: finalMessage,
                                    senderEmail: EmialSender,
                                    senderPassword: EmailPassword,
                                },
                                cache: false,
                                success: function(Result) {
                                    sendCount ++;
                                    console.log("Rturn Val: "+Result+ " "+ $(".sendingFname"+ postionIng+"").text());
                                    if(Result == "Send"){
                                        
                                        processdd = true;
                                        $(".sendingstatus"+ postionIng+"").removeAttr("style");
                                        $(".sendingstatus"+ postionIng+"").attr("style", "color:green !important");
                                        $(".sendingstatus"+ postionIng+"").text("SEND");
                                        if(sendCount == $('.eachTR').length || $('.eachTR').length == 1){
                                            $(".loader").addClass("d-none");
                                            swal({
                                            title: "Complete!",
                                            text: "Sending "+$("#totalEmail").text()+" Emails Complete!",
                                            icon: "success",
                                            button: "Ok!",
                                            closeOnEsc: false,
                                            closeOnClickOutside: false
                                            }).then((willOk) => {
                                            if (willOk) {
                                                
                                                $("#loading-status-text").text("Analyzing the bounce email...");
                                                $("#loading-status-text").removeAttr('style');
                                                $("#loading-status-text").attr("style","margin-left:-250%;");
                                                $(".loader").removeClass("d-none");
                                                setTimeout(checkbounce(select_imap_server,EmialSender,EmailPassword), 20000);
                                                
                                            } 
                                        });
                                        }
                                        
                                    }else if(Result == "Error"){
                                        $(".sendingstatus"+ postionIng+"").removeAttr("style");
                                        $(".sendingstatus"+ postionIng+"").attr("style", "color:red !important");
                                        $(".sendingstatus"+ postionIng+"").text("ERROR");
                                        if(sendCount == $('.eachTR').length || $('.eachTR').length == 1){
                                            $(".loader").addClass("d-none");
                                            swal({
                                            title: "Complete!",
                                            text: "Sending "+$("#totalEmail").text()+" Emails Complete!",
                                            icon: "success",
                                            button: "Ok!",
                                            closeOnEsc: false,
                                            closeOnClickOutside: false
                                            }).then((willOk) => {
                                            if (willOk) {
                                                
                                                $("#loading-status-text").text("Analyzing the bounce email...");
                                                $("#loading-status-text").removeAttr('style');
                                                $("#loading-status-text").attr("style","margin-left:-250%;");
                                                $(".loader").removeClass("d-none");
                                                setTimeout(checkbounce(select_imap_server,EmialSender,EmailPassword), 20000);
                                            } 
                                            
                                        });
                                        

                                        }
                                    }
                                    else{
                                        swal({
                                        title: "Server Error!",
                                        text: "Contact webmaster @thinklogicmediagroup.com!",
                                        icon: "error",
                                        button: "Ok!",
                                        closeOnEsc: false,
                                        closeOnClickOutside: false
                                    });
                                    }
                                }
                            });

                        
                });
            }
                
            

        }
    });
    $("#Credential-Checker").on( "click", function() {
        alertify.dismissAll();
        SMTPServer = $("#stmpserverselection").val();
        EmialSender = $('#SenderE').val();
        EmailPassword = $('#SenderP').val();
        var validatesmtp = true;
        if(SMTPServer == "Emptyvalue"){
            alertify.error('Please select an smtp server.');
            validatesmtp = false;
        }
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var emailValid =  re.test(String(EmialSender).toLowerCase());

        if (EmialSender.trim() === "" || EmialSender.length < 3 || !emailValid) {
            alertify.error('Please check your email.');
            validatesmtp = false;
        }
        if(EmailPassword.trim() === "" || EmailPassword.length < 3){
            alertify.error('Please check your password.');
            validatesmtp = false;
        }
       
        if(validatesmtp){
            $("#loading-status-text").removeAttr('style');
            $("#loading-status-text").attr("style","margin-left:-150%;");
            $("#loading-status-text").text("Connecting to Server...");
            $("#stmpserverselection").attr("disabled", "disabled");
            $("#SenderE").attr("disabled", "disabled");
            $("#SenderP").attr("disabled", "disabled");
            $("#Credential-Checker").attr("disabled", "disabled");
            $(".loader").removeClass("d-none");
            $.ajax({
                url: "emailCreditialsCheck",
                type: "POST",
                data: {
                    smtpserver: SMTPServer,
                    smatpemail: EmialSender,
                    smatppassword: EmailPassword,
                },
                cache: false,
                success: function(Result) {
                    console.log(Result);
                    if(Result == 'Valid') {
                        $(".loader").addClass("d-none");
                        swal({
                            title: "Valid account!",
                            icon: "success",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                            })
                            .then((willOk) => {
                            if (willOk) {
                                
                                $("#smtp-Setup").addClass("d-none");
                                $("#email-Setup").removeClass("d-none");
                            } 
                        });
                    }else if(Result == "Invalid SMTP Server") {
                        $("#Credential-Checker").removeAttr("disabled");
                        $("#stmpserverselection").removeAttr("disabled");
                        $("#SenderE").removeAttr("disabled");
                        $("#SenderP").removeAttr("disabled");
                        $(".loader").addClass("d-none");
                        swal({
                            title: "Error!",
                            text: "Invalid SMTP Server!",
                            icon: "error",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });
                    }else if(Result == "Invalid account") {
                        $(".loader").addClass("d-none");
                        $("#Credential-Checker").removeAttr("disabled");
                        $("#stmpserverselection").removeAttr("disabled");
                        $("#SenderE").removeAttr("disabled");
                        $("#SenderP").removeAttr("disabled");
                        swal({
                            title: "Error!",
                            text: "Invalid account!",
                            icon: "error",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                        });
                    }
                    
                }

            })
        }
        
    });
    function checkbounce(imap_server,server_email_imap,server_password_imap) {
        
        var analyzeCount = 1;
        $( ".eachTR" ).each(function( index ) {
            var scanbounceindex = index + 1;
            $.ajax({
                url: 'scanbounce',
                type: "POST",
                data: {
                    gmail_or_ops: imap_server,
                    server_password_imap: server_password_imap,
                    server_email_imap: server_email_imap,
                    email_scan: $(".sendingEmail"+scanbounceindex+"").text(),
                    email_subject : finalSubjectTitle
                },
                cache: false,
                success: function(Result) {
                    console.log("Result code: "+Result+" Email: "+$(".sendingEmail"+scanbounceindex+"").text());
                    //console.log("Analyzing: "+$(".sendingEmail"+scanbounceindex+"").text()+" Result: "+Result);
                    analyzeCount ++;
                    
                    if(Result == '200'){
                        if(analyzeCount == $('.eachTR').length || $('.eachTR').length == 1){
                            $(".loader").addClass("d-none");
                            swal({
                            title: "Complete!",
                            text: "Analyzing "+$("#totalEmail").text()+" Emails Complete!",
                            icon: "success",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                            });
                                
                        }

                    }
                    else if(Result == '550'){
                        $(".sendingstatus"+ scanbounceindex+"").removeAttr("style");
                        $(".sendingstatus"+ scanbounceindex+"").attr("style", "color:#93312F !important");
                        $(".sendingstatus"+ scanbounceindex+"").text("Bounce");
                        if(analyzeCount == $('.eachTR').length || $('.eachTR').length == 1){
                            $(".loader").addClass("d-none");
                            swal({
                            title: "Complete!",
                            text: "Analyzing "+$("#totalEmail").text()+" Emails Complete!",
                            icon: "success",
                            button: "Ok!",
                            closeOnEsc: false,
                            closeOnClickOutside: false
                            });
                                
                        }
                    }
                }
            });

        });
    }
    });

    

    
   
</script>
</html>
