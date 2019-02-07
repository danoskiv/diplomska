#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Thu Dec 27 21:36:58 2018

@author: warlord
"""

import matplotlib.pyplot as plt
import pandas as pd
import sqlalchemy
import math
import numpy as np
import json
import sys
import os

jsonObject = sys.argv[1]
PHPValues = json.loads(jsonObject)
station = PHPValues["station"]
dateSelected = PHPValues["dateSelected"]
hourSelected = PHPValues["hourSelected"]
parameter = PHPValues["parameter"]

hourMinus = str(int(hourSelected) - 1)
hourPlus = str(int(hourSelected) + 1)

query = "SELECT value FROM SensorData WHERE parameterId = " + parameter + " AND stationId = " + station + " and date_stamp LIKE \'" + dateSelected + "T" + hourMinus + "%%\'"
engine = sqlalchemy.create_engine('mysql+pymysql://root:@localhost:3306/SkopjePulseData')
dataset1 = pd.read_sql_query(query, engine)

query2 = "SELECT value FROM SensorData WHERE parameterId = " + parameter + " AND stationId = " + station + " and date_stamp LIKE \'" + dateSelected + "T" + hourSelected + "%%\'"
dataset2 = pd.read_sql_query(query2, engine)

query3 = "SELECT value FROM SensorData WHERE parameterId = " + parameter + " AND stationId = " + station + " and date_stamp LIKE \'" + dateSelected + "T" + hourPlus + "%%\'"
dataset3 = pd.read_sql_query(query3, engine)

y = pd.DataFrame(columns=['value'])
X = pd.concat([dataset1, dataset2, dataset3], ignore_index=True)

for index, row in X.iterrows():
    if(row['value'] >= 50):
        y.loc[index] = '1'
    else:
        y.loc[index] = '0'

from sklearn.model_selection import train_test_split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size = 0.2, random_state = 0)
        
from sklearn.neighbors import KNeighborsClassifier
classifier = KNeighborsClassifier()
classifier.fit(X_train, y_train)

y_pred = classifier.predict(X_test)
y_pred = y_pred.tolist()
print(json.dumps(y_pred))
