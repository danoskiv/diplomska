#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Sat Jan 12 21:26:36 2019

@author: warlord
"""

import pandas as pd
import sqlalchemy
import numpy as np
import json
import sys
import datetime
import os
import pickle

date = datetime.datetime.now()
jsonObject = sys.argv[1]
PHPValues = json.loads(jsonObject)
station = PHPValues["station"]
parameter = PHPValues["parameter"]
pressure = float(PHPValues["pressure"])
humidity = float(PHPValues["humidity"])
windSpeed = float(PHPValues["windSpeed"])
windDir = float(PHPValues["windDir"])
clouds = float(PHPValues["clouds"])
temperature = float(PHPValues["temperature"])
directory = "/root/web/diplomska/public/weatherDir/station_" + station + "param_" + parameter + "date_" + str(date.year) + str(date.month) + str(date.day) + "model.pkl"

X_test = np.array([pressure, temperature, humidity, windSpeed, windDir, clouds])
X_test = X_test.reshape(1, -1)


if(os.path.exists(directory)):
	file_name = open(directory, 'rb')
	regressor = pickle.load(file_name)

	y_pred = regressor.predict(X_test)
	y_pred = y_pred.tolist()
	print(json.dumps(y_pred))



else:
	engine = sqlalchemy.create_engine('mysql+pymysql://root:gologaze@localhost:3306/SkopjePulseData')
	pmQuery = "select value, date_stamp from SensorData where parameterId = " + parameter + " and stationId = " + station + " and date_stamp > '2018-11-20%%' and date_stamp like '%%:4_:%%'"
	pmDataset = pd.read_sql_query(pmQuery, engine)


	wd = pd.DataFrame()
	for i in range(len(pmDataset.index)):
	    dateStamp = pmDataset.iloc[i, 1]
	    d,t = dateStamp.split("T")
	    h,m,s = t.split(":")
	    weatherQuery = "select * from SkopjeWeatherAPIData where date_stamp like '" + d + "T" + h + ":4_:%%'"
	    r = pd.read_sql_query(weatherQuery, engine)
	    wd = pd.concat([wd, r], sort=False)

	y = pmDataset.iloc[0:len(wd.index), 0].values

	X = wd.iloc[:, 1:-1].values

	from sklearn.preprocessing import Imputer
	imputer = Imputer(missing_values='NaN', strategy='mean', axis=0)
	imputer = imputer.fit(X[:, :])
	X[:, :] = imputer.transform(X[:, :])

	from sklearn.linear_model import LinearRegression
	regressor = LinearRegression()
	regressor.fit(X, y)

	file_name = open(directory, 'wb')
	pickle.dump(regressor, file_name)

	y_pred = regressor.predict(X_test)
	y_pred = y_pred.tolist()
	print(json.dumps(y_pred))


