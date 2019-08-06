This login system was built as one of my first projects using PHP.
The database wont work as I've redacted my login details for phpmyadmin.
The code has been tested and is working fine.
I would like to update this at some point so the errors when registering redirect the user back to the register page 
and display the errors there, rather than having the errors on a seperate page and having to go back to edit the registration form.
I have now edited registerpage.php and registerprocess.php to throw the errors back to the register page and display them there. For this i used the GET method. I have thought about directing to registerpage.php after form submit, dealing with all the errors there and only redirecting to registerprocess.php if there are no errors but I prefer this way as it keeps the files seperate and allows for easier code reading.
