<h1>
  Culture Matcher - Displaying Company Matches
</h1>
<a href="http://culturematcher.org">Link to the live version of Culture Matcher website</a>
<h3>
  Overview
</h3>
<p>
  This portion of the Culture Matcher website shows the ranking of company matches for the job candidate. First, it checks to see if the candidate has taken the test and then if they have already accessed the API to build their match list. If the candidate's list has not been built yet, the application uses a curl function to create a mysql database and populates the page with the matches. Otherwise, the application will populate the page using the mysql database.
</p>
<p>
  The candidate has the option to open the company tiles to learn more about them and to bookmark and unbookmark them.  
</p>
<h3>
  Concepts and Technologies Used
</h3>
<p>
  PHP, PDO, Curl, mysql
</p>
