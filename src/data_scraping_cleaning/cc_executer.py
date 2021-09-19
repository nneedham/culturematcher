# -*- coding: utf-8 -*-
"""
Created on Tue May 19 15:22:11 2020

@author: needh
"""


import cm_scraper as gs # imports tool we built in cm_scraper to grab company data from GlassDoor
import pandas as pd

#pull in a list of fortune 500 companies
df_company = pd.read_csv("fortune_500.csv")
#create scraper data frame
n_companies = 500 #number of companies to look at
n_jobs = 15 #number of jobs to pull off glassdoor per company

#initialize variable to track current company number
v=0

print(v, '{}'.format(df_company['Title'][v]))
df = gs.get_jobs(df_company['Title'][v], n_jobs, False, .1)

v = v + 1
while v < n_companies:
    try:
        print(v, '{}'.format(df_company['Title'][v]))
        df = df.append(gs.get_jobs(df_company['Title'][v], n_jobs, False, .1))
        v = v + 1
        #print out new company information to df
        df.to_csv(r'scraped_df.csv', index = False)
    except:
        print('SKIPPED', v, '{}'.format(df_company['Title'][v]))
        v=v+1
        continue

#print out company df for later use
df.to_csv(r'scraped_df.csv', index = False)
