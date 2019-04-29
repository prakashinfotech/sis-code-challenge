# SIS Code Challenge

====================================
Project Description
-------------------
Suppose SpaceiShare has acquired a new company. That company , unfortuately, chose to track their employees' monthly expense using a pipe-separated (PSV) file instead
of using a database. You are required to make a web application which will import a user selected PSV file into a relational DB (as an administrator user) and then continue to track 
employee monthly expenses through submission web-forms going-forward.

What your web-based application must do:
    - Your app must accept (via a form) a pipe-separated file with the following columns: date, category, employee name, employee address, expense description, 
        pre-tax amount and tax amount.
        
    - You can make the following assumptions:
        i. Columns will always be in that order.
        ii. There will always be data in each column.
        iii. There will always be a header line.
    
    - Users must be able to login (or register and then login) and continue their monthly expense reporting using the web application (not through file upload)
    - User must be able to review past imported expense submissions as well as any new entries (think of how your expense entry system might work and how to make it 
        easy for the employee to report their expenses).
    - You web application should work well on Desktop, Tablet or Mobile devices (responsive web design).

An example input file named data_sample.psv is included in this gitlab project.

    Your app must parse the given file, and store the information in a relational database.
    After upload, your application should display a table of the total expenses amount per-month represented by the uploaded file (presumably from an admin-type account).
    Other users logging-in should only be able to see their own expenses and report to their own account.

Your application should be easy to set up (webapp, database, configurations, etc.), and should run easily on any webserver running PHP interpreter. 
It should not require any non open-source software.

There are many ways that this application could be built; we ask that you build it in a way that showcases one of your strengths - as we are currently developing 
on a PHP MVC framework/MYSQL, we'd ask that your package contain the same (CI, Laravel, Symphony, etc. - basically, drag, drop, configure, initialization scripts
maybe, then boot).

Documentation:
-------------
Please add a README.md file to your project root to provide:

    Detailed instructions on how to build/run your application and, 
    A paragraph or two about what you are particularly proud of in your implementation, and why.

Evaluation:
-----------
Evaluation of your submission will be based on the following criteria.

    Are all required functions enabled/functioning?
    Did you include build/deploy instructions and your note of what you did well?
    Are any models/entities/components easily identifiable to the reviewer?
    Are the design decisions you made sound/warranted in your models/entities? Why (i.e. were they explained?)
    Is there clear separate of concerns in your application? Why or why not?
    Does the solution use appropriate datatypes for the problem as described?

====================================