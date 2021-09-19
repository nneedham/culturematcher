# -*- coding: utf-8 -*-
"""
Created on Tue May 19 14:58:30 2020

@author: needh
"""

## The following code is based on arapfaik/scraping-glassdoor-selenium

from selenium.common.exceptions import NoSuchElementException, ElementClickInterceptedException
from selenium import webdriver
import time
import pandas as pd


def get_jobs(keyword, num_jobs, verbose, tm):

    '''Gathers jobs as a dataframe, scraped from Glassdoor'''

    #Initializing the webdriver
    options = webdriver.ChromeOptions()

    #Uncomment the line below if you'd like to scrape without a new Chrome window every time.
    options.add_argument('headless')

    #Change the path to where chromedriver is in your home folder.
    driver = webdriver.Chrome(executable_path="C:/Users/needh/Google Drive/Work/Data Science/tools/chromedriver.exe", options=options)
    driver.set_window_size(1120, 1000)

    url = 'https://www.glassdoor.com/Job/jobs.htm?suggestCount=0&suggestChosen=false&clickSource=searchBtn&typedKeyword='+keyword+'&sc.keyword='+keyword+'&locT=&locId=&jobType='
    driver.get(url)
    jobs = []

    while len(jobs) < num_jobs:  #If true, should be still looking for new jobs.

        #Let the page load. Change this number based on your internet speed.
        #Or, wait until the webpage is loaded, instead of hardcoding it.
        time.sleep(2)


        #Test for the "Sign Up" prompt and get rid of it.
        try:
            driver.find_element_by_class_name("selected").click()
        except ElementClickInterceptedException:
            pass

        time.sleep(tm)



        try:
            driver.find_element_by_css_selector('[alt="Close"]').click() #clicking to the X.
        except NoSuchElementException:
            print('x out failed')
            pass

        time.sleep(tm)

        #Go through each job in this page
        job_buttons = driver.find_elements_by_class_name("jl")  #jl for Job Listing. These are the buttons we're going to click.
        for job_button in job_buttons:

            print("Progress: {}".format("" + str(len(jobs)) + "/" + str(num_jobs)))
            if len(jobs) >= num_jobs:
                break

            job_button.click()
            time.sleep(tm)
            collected_successfully = False

            while not collected_successfully:
                try:
                    company_name = driver.find_element_by_xpath('.//div[@class="employerName"]').text
                    location = driver.find_element_by_xpath('.//div[@class="location"]').text
                    job_title = driver.find_element_by_xpath('.//div[contains(@class, "title")]').text
                    job_description = driver.find_element_by_xpath('.//div[@class="jobDescriptionContent desc"]').text
                    collected_successfully = True
                except:
                    time.sleep(tm)

            try:
                salary_estimate = driver.find_element_by_xpath('.//span[@class="gray small salary"]').text
            except NoSuchElementException:
                salary_estimate = -1

            try:
                rating = driver.find_element_by_xpath('.//span[@class="rating"]').text
            except NoSuchElementException:
                rating = -1

            #Printing for debugging
            if verbose:
                print("Job Title: {}".format(job_title))
                print("Salary Estimate: {}".format(salary_estimate))
                print("Job Description: {}".format(job_description[:500]))
                print("Rating: {}".format(rating))
                print("Company Name: {}".format(company_name))
                print("Location: {}".format(location))

            #Going to the Company tab...
            #clicking on this:
            #<div class="tab" data-tab-type="overview"><span>Company</span></div>
            try:
                driver.find_element_by_xpath('.//div[@class="tab" and @data-tab-type="overview"]').click()

                #Company HQ
                try:
                    headquarters = driver.find_element_by_xpath('.//div[@class="infoEntity"]//label[text()="Headquarters"]//following-sibling::*').text
                except NoSuchElementException:
                    headquarters = -1

                #size of company
                try:
                    size = driver.find_element_by_xpath('.//div[@class="infoEntity"]//label[text()="Size"]//following-sibling::*').text
                except NoSuchElementException:
                    size = -1

                #Company Founded Year
                try:
                    founded = driver.find_element_by_xpath('.//div[@class="infoEntity"]//label[text()="Founded"]//following-sibling::*').text
                except NoSuchElementException:
                    founded = -1

                #Type of Company
                try:
                    type_of_ownership = driver.find_element_by_xpath('.//div[@class="infoEntity"]//label[text()="Type"]//following-sibling::*').text
                except NoSuchElementException:
                    type_of_ownership = -1

                #Company's Industry
                try:
                    industry = driver.find_element_by_xpath('.//div[@class="infoEntity"]//label[text()="Industry"]//following-sibling::*').text
                except NoSuchElementException:
                    industry = -1

                #Company's Sector
                try:
                    sector = driver.find_element_by_xpath('.//div[@class="infoEntity"]//label[text()="Sector"]//following-sibling::*').text
                except NoSuchElementException:
                    sector = -1

                #company's revenue
                try:
                    revenue = driver.find_element_by_xpath('.//div[@class="infoEntity"]//label[text()="Revenue"]//following-sibling::*').text
                except NoSuchElementException:
                    revenue = -1

                #company's competitors
                try:
                    competitors = driver.find_element_by_xpath('.//div[@class="infoEntity"]//label[text()="Competitors"]//following-sibling::*').text
                except NoSuchElementException:
                    competitors = -1

            except NoSuchElementException:  #Rarely, some job postings do not have the "Company" tab.
                headquarters = -1
                size = -1
                founded = -1
                type_of_ownership = -1
                industry = -1
                sector = -1
                revenue = -1
                competitors = -1

            if verbose:
                print("Headquarters: {}".format(headquarters))
                print("Size: {}".format(size))
                print("Founded: {}".format(founded))
                print("Type of Ownership: {}".format(type_of_ownership))
                print("Industry: {}".format(industry))
                print("Sector: {}".format(sector))
                print("Revenue: {}".format(revenue))
                print("Competitors: {}".format(competitors))
                print("@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")

            #Import Logic Words
            civ_txt = open("civic.txt")
            civ_read = civ_txt.read()
            civ_read = civ_read.rsplit()
            fam_txt = open("family.txt")
            fam_read = fam_txt.read()
            fam_read = fam_read.rsplit()
            ind_txt = open("industrial.txt")
            ind_read = ind_txt.read()
            ind_read = ind_read.rsplit()
            ins_txt = open("inspired.txt")
            ins_read = ins_txt.read()
            ins_read = ins_read.rsplit()
            mar_txt = open("market.txt")
            mar_read = mar_txt.read()
            mar_read = mar_read.rsplit()
            pop_txt = open("popular.txt")
            pop_read = pop_txt.read()
            pop_read = pop_read.rsplit()
            sus_txt = open("sustainable.txt")
            sus_read = sus_txt.read()
            sus_read = sus_read.rsplit()

            #Create bins for counting words
            word_count = 0
            civ_count = 0
            fam_count = 0
            ind_count = 0
            ins_count = 0
            mar_count = 0
            pop_count = 0
            sus_count = 0

            #Import file
            test_txt = str(job_description)
            test_txt = test_txt.lower()
            all_words = test_txt.rsplit()
            word_count = len(all_words)

            #Count all of the words
            for wd in civ_read:
                count = test_txt.count(wd)
                civ_count = civ_count + count
            for wd in fam_read:
                count = test_txt.count(wd)
                fam_count = fam_count + count
            for wd in ind_read:
                count = test_txt.count(wd)
                ind_count = ind_count + count
            for wd in ins_read:
                count = test_txt.count(wd)
                ins_count = ins_count + count
            for wd in mar_read:
                count = test_txt.count(wd)
                mar_count = mar_count + count
            for wd in pop_read:
                count = test_txt.count(wd)
                pop_count = pop_count + count
            for wd in sus_read:
                count = test_txt.count(wd)
                sus_count = sus_count + count

            jobs.append({"Job Title" : job_title,
            "Salary Estimate" : salary_estimate,
            "Job Description" : job_description,
            "Rating" : rating,
            "Company Name" : company_name,
            "Location" : location,
            "Headquarters" : headquarters,
            "Size" : size,
            "Founded" : founded,
            "Type of ownership" : type_of_ownership,
            "Industry" : industry,
            "Sector" : sector,
            "Revenue" : revenue,
            "Competitors" : competitors,
            "Word count" : word_count,
            "civic": civ_count,
            "family": fam_count,
            "industrial": ind_count,
            "inspired": ins_count,
            "market": mar_count,
            "popular": pop_count,
            "sustainable": sus_count,
            })
            #add job to jobs

        #Clicking on the "next page" button
        try:
            driver.find_element_by_xpath('.//li[@class="next"]//a').click()
        except NoSuchElementException:
            print("Scraping terminated before reaching target number of jobs. Needed {}, got {}.".format(num_jobs, len(jobs)))
            break

    return pd.DataFrame(jobs)  #This line converts the dictionary object into a pandas DataFrame.
