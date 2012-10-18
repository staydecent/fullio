# Fullio

*A run down of how this code came to be*
Acting on a whim, I created Fullio on Google App Engine (Python). It had built in email handling and the platform is a joy. Oops, the email service had a size limit for Email Attachments. Cool, let's port it to Flask (python). Nope, I was a noob, and was running Windows.
Okay, fine, let's just do it in PHP. *To save time* (Yes, really) I created a new routing/request handler in PHP to mimic the style of App Engine's webapp. Oh and MongoDB looks like an easy port from App Engine's datastore. Oh, WebFaction doesn't support MongoDB out-of-box? That's fine, I'll compile my own Apache, PHP and MongoDB environment (remember, I was a noob and on Windows). What? MongoDB crashes all the time, and I need a daemon to keep it up? Oh, and I'm now like a year behind schedule? Cool, so let's port it to SQL.

Hai!

## Libraries/Services Used

- [Requests](https://github.com/rmccue/Requests) (for Mailgun requests)
- [h2o-php](https://github.com/speedmax/h2o-php)
- [Idiorm & Paris](http://j4mie.github.com/idiormandparis/)
- [Mailgun](http://documentation.mailgun.net/index.html)
- [Mud](https://github.com/staydecent/Mud) (webapp PHP port thingy)
- [AWS](https://github.com/amazonwebservices/aws-sdk-for-php) *library not included in repo!*

## Getting this shit to run

Good luck, jackass. Just kidding. But, I haven't touched this in something like a year. 
- Make sure you add the AWS-SDK For PHP library to the systems folder. 
- Edit the config/ files.
- Make sure you have a DB to connect to.
- Make sure you have a Mailgun/AWS S3 account.
- Give it a go, this is basically the code at fullioapp.com

## Credits

Branding and design by the mysterious [Mark Stuckert](http://www.thisissolitaire.com/).

## License (MIT)

Copyright (c) 2012 Adrian Unger

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.