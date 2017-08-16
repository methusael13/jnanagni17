<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Ubuntu+Condensed" rel="stylesheet">

        <style type="text/css">
            body {
                margin: 0 auto; color: #1b1b1b;
            }

            .button-area { padding: 2em; text-align: center; }
            .button-area > a {
                text-decoration: none;
                padding: 0.5em 2em;
                border: 2px solid #1b1b1b;
                background-color: #1b1b1b; color: #e4e4e4;
                font-family: 'Ubuntu Condensed';
                font-size: 1em; letter-spacing: 0.2em;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left; padding: 0.5em;">
                    <img src="http://jnanagni17.in/res/images/gkv-logo.png" width="100" height="90" />
                </td>
                <td style="text-align: right; padding: 0.5em;">
                    <img src="http://jnanagni17.in/res/images/jnanagni-logo-s.png" width="100" height="100" />
                </td>
            </tr>
            <tr>
                <td colspan="2" style='font-family: "Source Sans Pro"; padding: 2em 0; text-align: center;'>
                    Thank you for registering for Jnanagni 2017, the annual technical
                    festival of Faculty of Engineering and Technology, Gurukul Kangri
                    Vishwavidyalaya, Haridwar.<br/><br/>
                    Click on the button below to confirm your email and finalize your registration.
                    For security reasons, the link will remain valid for 24 hrs only.
                </td>
            </tr>
            <tr>
                <td colspan="2" class="button-area">
                    <?php $host = 'http://jnanagni17.in/register/verify'; ?>
                    <a href={{ implode('/', [ $host, $hash, $token ]) }} target="_blank">Verify</a>
                </td>
            </tr>
        </table>
    </body>
</html>
