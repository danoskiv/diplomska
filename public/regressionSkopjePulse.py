#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Sun Dec 16 12:21:06 2018

@author: warlord
"""

import pandas as pd
import sqlalchemy
import numpy as np
import json
import sys
import os
import pickle
from sklearn.preprocessing import Imputer
from sklearn.linear_model import LinearRegression
import datetime

date = datetime.datetime.now()
jsonObject = sys.argv[1]
PHPValues = json.loads(jsonObject)
station = PHPValues["station"]
temperature = float(PHPValues["temperature"])
noise = float(PHPValues["noise"])
humidity = float(PHPValues["humidity"])
parameter = PHPValues["parameter"]
X_test = np.array([temperature, noise, humidity])
X_test = X_test.reshape(1, -1)
directory = "/root/web/diplomska/public/modelsDir/station_" + station + "_param_" + parameter + "date_" + str(date.year) + str(date.month) + str(date.day) + "model.pkl"


if(os.path.exists(directory)):
	file_name = open(directory, 'rb')
	regressor = pickle.load(file_name)

	y_pred = regressor.predict(X_test)
	y_pred = y_pred.tolist()
	print(json.dumps(y_pred))


else:
	engine = sqlalchemy.create_engine('mysql+pymysql://root:@localhost:3306/SkopjePulseData')

	parameterQuery = "SELECT value FROM SensorData WHERE parameterId = " + parameter + " AND stationId = " + station
	pmDataset = pd.read_sql_query(parameterQuery, engine)

	temperatureQuery = "SELECT value FROM SensorData WHERE parameterId = 4 AND stationId = " + station
	noiseQuery = "SELECT value FROM SensorData WHERE parameterId = 3 AND stationId = " + station
	humidityQuery = "SELECT value FROM SensorData WHERE parameterId = 9 AND stationId = " + station

	temperatureDataset = pd.read_sql_query(temperatureQuery, engine)
	noiseDataset = pd.read_sql_query(noiseQuery, engine)
	humidityDataset = pd.read_sql_query(humidityQuery, engine)

	result = [pmDataset, temperatureDataset, noiseDataset, humidityDataset]
	dataset = pd.concat(result, axis = 1)
	dataset.columns = ['pm', 'temperature', 'noise', 'humidity']
	#dataset = dataset.dropna(subset=['temperature', 'humidity'])

	y = dataset.iloc[:, 0].values
	X = dataset.iloc[:, 1:].values
	y = y.reshape(-1, 1)

	imputer = Imputer(missing_values='NaN', strategy='mean', axis=0)
	imputer = imputer.fit(y[:])
	y[:] = imputer.transform(y[:])

	imputer = Imputer(missing_values='NaN', strategy='mean', axis=0)
	imputer = imputer.fit(X[:, 0:3])
	X[:, 0:3] = imputer.transform(X[:, 0:3])

	regressor = LinearRegression()
	regressor.fit(X, y)
	file_name = open(directory, 'wb')
	pickle.dump(regressor, file_name)

	y_pred = regressor.predict(X_test)
	y_pred = y_pred.tolist()
	print(json.dumps(y_pred))
