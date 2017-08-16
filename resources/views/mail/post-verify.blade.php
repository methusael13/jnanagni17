<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Ubuntu+Condensed" rel="stylesheet">

        <style type="text/css">
            body { margin: 0 auto; color: #1b1b1b; }
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
                <td colspan="2" style='font-family: "Source Sans Pro"; padding: 2em 0; text-align: left;'>
                    Hello <b>{{ $first_name . ' ' . $last_name }}</b>, your account is activated, you should now be able to register for events. <i>Jnanagni 2017</i>, the techno-cultural festival of <b>Gurukul Kangri Vishwavidyalaya</b>, is almost knocking at the door. Visit <a href="https://jnanagni17.in">Jnanagni</a> now and register for the events and get a chance to win your prize money.<br><br>
                    The inauguration for the fest will begin on the 23<sup>rd</sup> of March, 2017 at 9:00 AM, at <b>Gurukul Kangri Vishwavidyalaya, Haridwar</b>.<br><br>
                    Brochure: <a href={{ $brochure }} target="_blank">Download Jnanagni Brochure</a>.<br>
                    Schedule: <a href={{ $schedule }} target="_blank">Download Jnanagni Schedule</a>.<br>
                    Do review the schedule for your events.<br><br>
                    Regards,<br>
                    &nbsp&nbsp&nbsp&nbsp<i>Jnanagni Team</i>
                </td>
            </tr>
        </table>
    </body>
</html>
