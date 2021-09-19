<h1>
  Culture Matcher - Candidate / Company Matching Model
</h1>
<a href="http://culturematcher.org">link to the live version of Culture Matcher website</a>
<h3>
  Overview
</h3>
<p>
  This portion of the Culture Matcher website takes in the cleaned data scraped from glassdoor and the job seeker's cultural test. The cleaned data is stored in an AWS s3 bucket and the test information in a mysql database as part of Culture Matcher's LAMP stack. The candidate's test information is transmitted in a json.
</p>
<p>
  After receiving the data, the model identifies the euclidean distance between the companies and the candidates to create a cultural ranking score. This matching model is hosted on AWS lambda as API in order to access S3.  
</p>
<h3>
  Concepts and Technologies Used
</h3>
<p>
  AWS S3, AWS Lambda, Python, Pandas, Euclidean Distance
</p>
