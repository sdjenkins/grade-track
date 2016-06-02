# grade-track

This an example program from a course I took at OSU. It demonstrates use of PHP, HTML, JavaScript, jQuery, and CSS that I learned while studying Computer Science at OSU. 

The application allows a user to create an account to track information about their grades in courses. The user is able to enter information (course number, course credits, credits earned, letter grade, term, and year). This information is stored in a MySQL database. The application will calculate their GPA from their currently entered information, and it displays a pie chart of their current distribution of grades. The user can also modify or remove a course after it has been entered. 

If the user is logged out (there's no user session) and tries to access the data pages, then they'll be redirected to the login page. The login page has server side username/password validation handled with AJAX. The create account page has username and password validation handled with JavaScript.
