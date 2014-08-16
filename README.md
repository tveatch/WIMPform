WIMP FORM

Because my personal dev stack is Windows Server 2012, IIS, MySQL and PHP

PHP, PDO:MySQL, HTML, CSS, JS

Register New Users - Password Hashing/Salting
Session Handeling
Form submission, editing, deleting, printing to pdf

This framework provides a basic building block for a complete forms-based web application. It allows the creation of data to be stored in a MySQL database, retrieval of that information for viewing/editing/deleting, and finally printing of data to pdf with the help of the MPDF library. I built a much larger and more complex application over a three month period after finding the bits and pieces all over the web. With all that I found and learned I have decided to share the basic but functional gist of that project (repurposed and dumbed down). There is no one stop shop that will give you all of the features implemented in this codebase. I do not claim complete authorship as many of the key chunks were pulled from various free tutorials/blogs shared by generous developers… if you happen upon this and recognize your code I thank you greatly and have attempted to extend the sharing by providing this work. Use at your own risk, learn something, stick to the code.

1. You’ll need to set up a database in MySQL called: myDreams
2. Import the myDreams.sql file in the _assets folder to establish the basic tables
3. Register a new user: newUser.php
4. Only registered users can log into the backend to view and create new dream entries
5. Change super level of user to ‘super’ to allow logged in user to edit/delete/print dreams and to view/edit/delete users information
