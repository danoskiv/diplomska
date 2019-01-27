#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Sat Nov 10 20:23:27 2018

@author: warlord
"""
string = 'mysql+pymysql://root:@localhost:3306/podatoci_vozduh'

#import matplotlib.pyplot as plt
#import mysql.connector
import pandas as pd
import sqlalchemy
import numpy as np
#import random

# =============================================================================

# =============================================================================
#query2 = "SELECT * FROM podatoci_kumanovo";
#query = "SELECT vrednosti_skopje.vrednost, podatoci_uhmr.pritisok, podatoci_uhmr.temp, podatoci_uhmr.vlaznost, podatoci_uhmr.brzina, podatoci_uhmr.pravec, podatoci_uhmr.dozd FROM vrednosti_skopje INNER JOIN stanici ON vrednosti_skopje.sid = stanici.id INNER JOIN podatoci_uhmr ON podatoci_uhmr.stanica_id = stanici.najbliska_stanica WHERE vrednosti_skopje.sid = 38 AND vrednosti_skopje.pid = 1 AND vrednosti_skopje.datum = podatoci_uhmr.datum ORDER BY vrednosti_skopje.id"
query = "SELECT value FROM SensorData WHERE parameterId = 2 AND stationId = 3"
engine = sqlalchemy.create_engine('mysql+pymysql://root:gologaze@localhost:3306/SkopjePulseData')
dataset1 = pd.read_sql_query(query, engine)

query2 = "SELECT value FROM SensorData WHERE parameterId = 3 AND stationId = 3"
dataset2 = pd.read_sql_query(query2, engine)

query3 = "SELECT value FROM SensorData WHERE parameterId = 4 AND stationId = 3"
dataset3 = pd.read_sql_query(query3, engine)

query4 = "SELECT value FROM SensorData WHERE parameterId = 9 AND stationId = 3"
dataset4 = pd.read_sql_query(query4, engine)

result = [dataset1, dataset2, dataset3, dataset4]

dataset = pd.concat(result, axis = 1)

#dataset = dataset.drop('pritisok', axis=1)
#dataset = dataset.dropna(subset=['temp', 'vlaznost', 'dozd','pravec'])
#dataset2 = pd.read_sql_query(query2, engine)
#for i in range(4406):
#    dataset.iloc[i, 5] = pravec(dataset.iloc[i, 5])
#dataset = dataset.dropna()
y = dataset.iloc[:, 0].values
X = dataset.iloc[:, 1:].values

#Encoding categorical data


#from sklearn.preprocessing import LabelEncoder, OneHotEncoder
#labelencoder_X = LabelEncoder()
#X = labelencoder_X.fit_transform(X)
#X = X.reshape(-1, 1)
#onehotencoder = OneHotEncoder(categorical_features = [0])
#X = onehotencoder.fit_transform(X).toarray()
# =============================================================================
# labelencoder_y = LabelEncoder()
# y = labelencoder_y.fit_transform(y)
# =============================================================================

y = y.reshape(-1, 1)
from sklearn.preprocessing import Imputer
imputer = Imputer(missing_values='NaN', strategy='mean', axis=0)
imputer = imputer.fit(y[:])
y[:] = imputer.transform(y[:])



from sklearn.preprocessing import Imputer
imputer = Imputer(missing_values='NaN', strategy='mean', axis=0)
imputer = imputer.fit(X[:, 0:3])
X[:, 0:3] = imputer.transform(X[:, 0:3])

# =============================================================================
# from sklearn.preprocessing import Imputer
# imputer = Imputer(missing_values='NaN', strategy='most_frequent', axis=0)
# imputer = imputer.fit(X[:, 5:6])
# X[:, 5:6] = imputer.transform(X[:, 5:6])
#
# from sklearn.preprocessing import Imputer
# imputer = Imputer(missing_values=-1, strategy='most_frequent', axis=0)
# imputer = imputer.fit(X[:, 4:5])
# X[:, 4:5] = imputer.transform(X[:, 4:5])
# =============================================================================

from sklearn.model_selection import train_test_split
X_train, X_test, y_train, y_test = train_test_split(X[:, 0:], y, test_size = 0.2, random_state = 0)

from sklearn.preprocessing import StandardScaler
sc_X = StandardScaler()
X_train = sc_X.fit_transform(X_train)
X_test = sc_X.transform(X_test)
sc_y = StandardScaler()
y_train = sc_y.fit_transform(y_train)
y_test = sc_y.transform(y_test)

from sklearn.linear_model import LinearRegression
regressor = LinearRegression()
regressor.fit(X_train, y_train)

y_pred = regressor.predict(X_test)

#X = X.reshape(-1, 3)
#
#import statsmodels.formula.api as sm
#X = np.append(arr = np.ones((29187)).astype(int), values = X, axis = 1)
#X_opt = X[:, [0, 1, 2]]
#regressor_OLS = sm.OLS(endog = y, exog = X_opt).fit()
#regressor_OLS.summary()
#
#from sklearn.cross_validation import train_test_split
#X_train, X_test, y_train, y_test = train_test_split(X_opt, y, test_size = 0.1, random_state = 0)
#
#from sklearn.linear_model import LinearRegression
#regressor = LinearRegression()
#regressor.fit(X_train, y_train)
#
#y_pred = regressor.predict(X_test)
#cur.execute(query);
# =============================================================================
# y = {}
# x1 = {}
# x2 = {}
# x3 = {}
# x4 = {}
# x5 = {}
# x6 = {}
# i=0
# for row in cur.fetchall():
#     y[i] = row[0]
#     x1[i] = row[1]
#     x2[i] = row[2]
#     x3[i] = row[3]
#     x4[i] = row[4]
#     x5[i] = row[5]
#     x6[i] = row[6]
#     i = i+1
#
# print(vrednost)
# db.close()
# =============================================================================
#X = np.array(X)
#X.reshape(-1, 3)
#
#random_vrednosti = random.sample(range(1, 23349), 10)

#plt.scatter(X_train[random_vrednosti, 2], y_train[random_vrednosti], color='red')
#plt.scatter(X_train[random_vrednosti, 0], y_train[random_vrednosti], color='blue')
#plt.scatter(X_train[random_vrednosti, 1], y_train[random_vrednosti], color='black')
#plt.title("Zavisnost")
#plt.xlabel("Vlaznost")
#plt.ylabel("Zagadenost")
#plt.show(block=False)
#plt.savefig('test_plot.png')
print("Hello World!")
