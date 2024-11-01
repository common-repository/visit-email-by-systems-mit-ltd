<?php
$email = get_option('admin_email');
$name = get_bloginfo('name');
?>
<body style="background-color: #F2F2F2; margin: 0;">
  <div style="padding: 2rem;">
    <h1 style="text-align: left; font-size: 4rem; color: #333;">Welcome to Visit Email Plugin</h1>
    <p style="font-size: 26px; color: #666; text-align: justify;">Stay connected with the world with our state-of-the-art email system!</p>
    <hr>
    <p style="font-size: 21px; color: #444; text-align: justify; margin: 2rem 0;">
      Do you often wonder who's visiting your website and what they're looking at? With Visit Email, you'll never have to guess again. Our powerful plugin sends you email notifications every time someone visits your site, providing you with valuable insights into their behavior. The email includes critical information such as the visitor's IP address, device type, location, time of visit, and URL visited. With this data, you'll be able to understand your audience better and tailor your content to their preferences. And if you're worried about privacy, don't be - we use the IP Geolocation API to retrieve location information, ensuring that all the data is secure.<br><br>
      Don't let valuable visitor data slip through your fingers. Set up your SMTP (if not already set up) today and start making informed decisions about your website's progress, content, and design with Visit Email by <a href="https://systemsmit.com">Systems Mit Ltd</a>.<br><br>
      This email will be used to receive all your notifications: <strong><?php echo $email;?></strong>
    </p>
  </div>
</body>
