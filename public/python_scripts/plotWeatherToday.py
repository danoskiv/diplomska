#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Sat Dec 22 19:09:02 2018

@author: warlord
"""    
def checkCounterValue(i):
    if(i == 0):
        return 'Pressure'
    elif(i == 1):
        return 'Temperature'
    elif(i == 2):
        return 'Humidity'
    elif(i == 3):
        return 'WindSpeed'
    elif(i == 4):
        return 'WindDirection'
    else:
        return 'Clouds'

def getDataPM10(times, yesterday, parameter, station, pmparam):
    d = pd.DataFrame()
    n = pd.DataFrame()
    for t in times:
        h,m,s = t.split(":")
        pmQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + pmparam + " AND date_stamp LIKE \'"+ yesterday + "T" + h + ":" + "4%%\' LIMIT 1"
        noiseQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + parameter + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "4%%\' LIMIT 1"
        noiseDataset = pd.read_sql_query(noiseQuery, engine)
        pmDataset = pd.read_sql_query(pmQuery, engine)
        if(pmDataset.empty or noiseDataset.empty):
            noiseQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + parameter + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "5%%\' LIMIT 1"
            pmQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + pmparam + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "5%%\' LIMIT 1"
            pmDataset = pd.read_sql_query(pmQuery, engine)
            noiseDataset = pd.read_sql_query(noiseQuery, engine)
            if(pmDataset.empty or noiseDataset.empty):
                noiseQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + parameter + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "3%%\' LIMIT 1"
                pmQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + pmparam + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "3%%\' LIMIT 1"
                pmDataset = pd.read_sql_query(pmQuery, engine)
                noiseDataset = pd.read_sql_query(noiseQuery, engine)
                if(pmDataset.empty or noiseDataset.empty):
                    noiseQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + parameter + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "2%%\' LIMIT 1"
                    pmQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + pmparam + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "2%%\' LIMIT 1"
                    pmDataset = pd.read_sql_query(pmQuery, engine)
                    noiseDataset = pd.read_sql_query(noiseQuery, engine)
                    if(pmDataset.empty or noiseDataset.empty):
                        noiseQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + parameter + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "1%%\' LIMIT 1"
                        pmQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + pmparam + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "1%%\' LIMIT 1"
                        pmDataset = pd.read_sql_query(pmQuery, engine)
                        noiseDataset = pd.read_sql_query(noiseQuery, engine)
                        if(pmDataset.empty or noiseDataset.empty):
                            noiseQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + parameter + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "0%%\' LIMIT 1"
                            pmQuery = "SELECT value FROM SensorData WHERE stationId = " + station + " AND parameterId = " + pmparam + " AND date_stamp LIKE \'" + yesterday + "T" + h + ":" + "0%%\' LIMIT 1"
                            pmDataset = pd.read_sql_query(pmQuery, engine)
                            noiseDataset = pd.read_sql_query(noiseQuery, engine)
                            if(pmDataset.empty or noiseDataset.empty):
                                pmDataset = pmDataset.append({'value':None}, ignore_index=True)
                                noiseDataset = noiseDataset.append({'value':None}, ignore_index=True)
        d = pd.concat([d, pmDataset])
        n = pd.concat([n, noiseDataset])
        
    return d, n

import matplotlib.pyplot as plt
import pandas as pd
import sqlalchemy
import numpy as np
#import json
#import sys
from datetime import datetime, timedelta
import tkinter as tk

publicDir = "/root/web/diplomska/public/plots/"

root = tk.Tk()

width_px = root.winfo_screenwidth()
height_px = root.winfo_screenheight() 
width_mm = root.winfo_screenmmwidth()
height_mm = root.winfo_screenmmheight() 
# 2.54 cm = in
width_in = width_mm / 25.4
height_in = height_mm / 25.4
#width_dpi = width_px/width_in
#height_dpi = height_px/height_in 

yesterday = (datetime.now() - timedelta(1)).strftime('%Y-%m-%d')
engine = sqlalchemy.create_engine('mysql+pymysql://root:gologaze@localhost:3306/SkopjePulseData')

weatherQuery = "SELECT * FROM SkopjeWeatherAPIData WHERE date_stamp LIKE \'" + yesterday + "%%\'"
weatherDataset = pd.read_sql_query(weatherQuery, engine)
stationsQuery = "SELECT * FROM Stations WHERE status = 'ACTIVE' AND type != '0'"
stationsDataset = pd.read_sql_query(stationsQuery, engine)
stations = stationsDataset.iloc[:, 0].values

pressure = weatherDataset.iloc[:, 1].values
pressure = np.array(pressure)
temperature = weatherDataset.iloc[:, 2].values
humidity = weatherDataset.iloc[:, 3].values
windSpeed = weatherDataset.iloc[:, 4].values
windDir = weatherDataset.iloc[:, 5].values
clouds = weatherDataset.iloc[:, 6].values
dateStamps = weatherDataset.iloc[:, 7].values

dates = []
times = []
parameters = [3, 4, 9]
for stamp in dateStamps:
    dates.append(stamp.split("T")[0])
    times.append(stamp.split("T")[1])

d = pd.DataFrame()
n = pd.DataFrame()
pmParams = ['1', '2']
fig = plt.figure(figsize=(7,5), dpi=300)

for j in stations:
    for i in parameters:
        for k in pmParams:
            d, n = getDataPM10(times, yesterday, str(i), str(j), str(k))
            pmValues = d.iloc[:, 0].values
            parameterValues = n.iloc[:, 0].values
            if(i == 3): #noise
                plt.plot(dateStamps, parameterValues, label='Noise', lw=2, marker='o')
                plt.plot(dateStamps, pmValues, label='PM Values', lw=2, marker='s')
                plt.xlabel('Date&Time')
                plt.ylabel('Value')
                plt.grid(True, linestyle='dashed')
                plt.legend(loc='upper right')
                plt.gcf().autofmt_xdate()
                plt.gcf().set_size_inches((width_in, height_in), forward=False)
                plt.savefig(publicDir + 'noise_' + str(j) + str(k) + yesterday + '.png')
                plt.close('all')
            elif(i == 4): #temperature
                plt.plot(dateStamps, parameterValues, label='Temperature', lw=2, marker='o')
                plt.plot(dateStamps, pmValues, label='PM Values', lw=2, marker='s')
                plt.xlabel('Date&Time')
                plt.ylabel('Value')
                plt.grid(True, linestyle='dashed')
                plt.legend(loc='upper right')
                plt.gcf().autofmt_xdate()
                plt.gcf().set_size_inches((width_in, height_in), forward=False)
                plt.savefig(publicDir + 'temperature_' + str(j) + str(k) + yesterday + '.png')
                plt.close('all')
            elif(i == 9): #humidity
                plt.plot(dateStamps, parameterValues, label='Humidity', lw=2, marker='o')
                plt.plot(dateStamps, pmValues, label='PM Values', lw=2, marker='s')
                plt.xlabel('Date&Time')
                plt.ylabel('Value')
                plt.grid(True, linestyle='dashed')
                plt.legend(loc='upper right')
                plt.gcf().autofmt_xdate()
                plt.gcf().set_size_inches((width_in, height_in), forward=False)
                plt.savefig(publicDir + 'humidity_' + str(j) + str(k) + yesterday + '.png')
                plt.close('all')

weatherParameters = [pressure, temperature, humidity, windSpeed, windDir, clouds]
weatherParameters = np.array(weatherParameters)
for i in range(np.size(weatherParameters,0)):
    s = checkCounterValue(i)
    plt.plot(dateStamps, weatherParameters[i], label=s, lw=2, marker='o')
    plt.plot(dateStamps, pmValues, label='PM Values', lw=2, marker='s')
    plt.xlabel('Date&Time')
    plt.ylabel('Value')
    plt.grid(True, linestyle='dashed')
    plt.legend(loc='upper right')
    plt.gcf().autofmt_xdate()
    plt.gcf().set_size_inches((width_in, height_in), forward=False)
    plt.savefig(publicDir + s + '_' + yesterday + '.png')
    plt.close('all')
