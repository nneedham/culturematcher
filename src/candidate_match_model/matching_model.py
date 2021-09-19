import json
import boto3
import pandas as pd
import numpy as np


s3 = boto3.client('s3')

def lambda_handler(event, context):
    import warnings
    warnings.filterwarnings("ignore")

    #Get the database
    def get_df():
        bucket = 'culturematcherbucket'

        key = 'cc_cleaned_2.json'

        response = s3.get_object(Bucket=bucket, Key=key)

        content = response['Body']

        jsonDict = json.loads(content.read())

        jsonObject = json.dumps(jsonDict)

        df = pd.read_json(jsonObject)

        #clean up database
        df = df[['Company Name',
                'innovator percent',
                'paragon percent',
                'trendsetter percent',
                'citizen percent',
                'athlete percent',
                'tinkerer percent',
                'steward percent',
                ]]
        df['euclidean'] = ''
        df['match_score'] = ''

        return df
    df = get_df()

    #import scores
    #Parse out query string params

    innovator_score = float(event['queryStringParameters']['innovator_score'])
    paragon_score = float(event['queryStringParameters']['paragon_score'])
    trendsetter_score = float(event['queryStringParameters']['trendsetter_score'])
    citizen_score = float(event['queryStringParameters']['citizen_score'])
    athlete_score = float(event['queryStringParameters']['athlete_score'])
    tinkerer_score = float(event['queryStringParameters']['tinkerer_score'])
    steward_score = float(event['queryStringParameters']['steward_score'])


    X = np.array([[innovator_score,
               paragon_score,
               trendsetter_score,
               citizen_score,
               athlete_score,
               tinkerer_score,
               steward_score]])

    df_scores = pd.DataFrame(X, columns=['innovator_score',
                                         'paragon_score',
                                         'trendsetter_score',
                                         'citizen_score',
                                         'athlete_score',
                                         'tinkerer_score',
                                         'steward_score'])

    #Euclidean model
    def euclidean_distance(x, y):
        return np.sqrt(np.sum((x - y) ** 2))

    for u in range(0,len(df)):
        a = np.array(df.iloc[u][1:8])
        b = np.array(df_scores.iloc[0])
        df.iat[u,8] = euclidean_distance(a,b)

    # #calculate percent matching score
    euc_max = max(df['euclidean'])
    euc_min = min(df['euclidean'])

    for u in range(0,len(df)):
        df.iat[u,9] = (((df.iat[u,8]-euc_max)/(euc_max - euc_min))*-1)*100

    df = df.sort_values(by=['match_score'], ascending=False)

    # #Prepare and convert to JSON to return
    df_return = df[['Company Name','match_score']]
    json_return = df_return.to_json(orient="records")


    return json_return
