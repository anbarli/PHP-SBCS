PHP-SBCS
========

<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
<a href="http://getbootstrap.com"><img src="https://img.shields.io/badge/bootstrap-3.3.7-blue.svg" /></a>
<a href="http://jquery.com"><img src="https://img.shields.io/badge/jquery-3.1.0-blue.svg" /></a>
<a href="https://codeclimate.com/github/ganbarli/PHP-SBCS"><img src="https://codeclimate.com/github/ganbarli/PHP-SBCS/badges/gpa.svg" /></a>
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/4c5ed8aa57ac487a9d8b60f1d5b0d580)](https://www.codacy.com/app/ganbarli/PHP-SBCS)


PHP Session Based Cart System is pretty simple and fast way for listing small amount of products.

This script doesn't include any payment method or payment page. This script lists manually added products, you can add that products to your shopping cart, remove them, change quantity via sessions.

Also this script is a good exercise to understand PHP session arrays.

You can easily add - remove items from shopping cart, all data stored in sessions and system not uses a database.

In source code, you can add - remove products manually, if you want you can add product id to code yourself. You have to know basic HTML for this. If you wish, you can code for database integration.

If you want to add same product to shopping cart more then once, system automatically update quantity of existing one.

When you submit "**Give Order**" button, form elements and order detail passed via form. You can code payment options here or you can change the forms action to another script page. If you wish, you can send form elements and order detail via email, or you can took them into a database table (do not forget to clean the variables, for example you can use mysql_real_escape_string).

For currency change, open root/index.php with text/HTML editor. You can change **$currency symbol**.

Table of contents
========

- <a href="https://github.com/ganbarli/PHP-SBCS#change-log">Change Log</a>
- <a href="https://github.com/ganbarli/PHP-SBCS#whats-included">What's included</a>
- <a href="https://github.com/ganbarli/PHP-SBCS#bugs-and-feature-requests">Bugs and feature requests</a>
- <a href="https://github.com/ganbarli/PHP-SBCS#browser-support">Browser Support</a>
- <a href="https://github.com/ganbarli/PHP-SBCS#license">License</a>
- <a href="https://github.com/ganbarli/PHP-SBCS#image-credits">Image Credits</a>
- <a href="https://github.com/ganbarli/PHP-SBCS#demo">Demo</a>

Change Log
========

For the most recent change log, please follow here. I try to add detailed release notes to each new release. **Please remember to STAR this project** and FOLLOW me to keep you update with this project.

######PHP-SBCS v1.6 / 28.09.2016

- PHP reporting set to E_ALL for easy debugging
- PHP short open tags converted to <?php

######PHP-SBCS v1.5 / 06.09.2016

- Jquery updated to version 3.1.0
- Footer changed
- Modal bug fixed, now works with Jquery 3
- GitHub page link added
- Product listing with thumbnails example added

######PHP-SBCS v1.4 / 04.09.2016

- All system is now in one file
- Bootstrap updated to version 3.3.7
- Jquery updated to version 2.2.4
- Bootstrap & Jquery files removed, CDN included
- Modal page opens when cart is updated
- Navbar fixed changed to navbar default
- Offcanvas removed

######PHP-SBCS v1.3 / 26.11.2015

- For calculating total values with decimals, we made a minor update.

######PHP-SBCS v1.2 / 31.10.2015

- Item remove from basket function is completely changed. Now when you remove an item from basket, system changes its quantity to 0. And we don't list 0 quantity items in basket.

######PHP-SBCS v1.1 / 01.10.2013

- Initial Commit

What's included
========

Within the download you'll find only one file : index.php

Bugs and feature requests
========

Have a bug or a feature request? <a href="https://github.com/ganbarli/PHP-SBCS/issues/new">Please open a new issue.</a>

Browser Support
========

- IE 9+
- Firefox (latest)
- Chrome (latest)
- Safari (latest)
- Opera (latest)

License
========

PHP-SBCS is an open source project licensed under <a href="http://opensource.org/licenses/MIT" target="blank">MIT</a>.

Credits
========

- <a href="https://github.com/twbs/bootstrap">Bootstrap</a> HTML, CSS, and JavaScript framework
- <a href="https://github.com/jquery/jquery">Jquery</a> JavaScript Library
- <a href="PLACEHOLD.IT">Placehold.it</a> Image placeholder service

Demo
========

<a href="http://www.anbarli.org/PHP-SBCS/">http://www.anbarli.org/PHP-SBCS/</a>
