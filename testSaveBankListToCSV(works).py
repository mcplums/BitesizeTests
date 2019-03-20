from lbcapi import api
import urlparse
import urllib, json
import threading
import traceback
import time
import multiprocessing
import pandas as pd
import common
import seleniumScrape
import csv
import sys
import krakenex
import pickle
import smtplib
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText

if __name__ == '__main__':
    manager = multiprocessing.Manager()
    return_dict = manager.dict()
    p = multiprocessing.Process(target=seleniumScrape.scrapeBank, args=('halifax',return_dict))
    p.start()
    p.join(60)
    if p.is_alive():
        print('Terminating Bank Scrape')
        p.terminate()
        p.join()
halifaxTransactionsList = return_dict.values()	

# f = open('halifaxTransactionsListNew.pckl', 'wb')
# pickle.dump(halifaxTransactionsList, f)
# f.close()

# df = pd.DataFrame(halifaxTransactionsList)
# df.to_csv('stfunew.csv', header=False, sep=',', index=False)
# print(df)

# f = open('halifaxTransactionsList.pckl', 'rb')
# halifaxTransactionsList = pickle.load(f)
# f.close()

# print(halifaxTransactionsList)

df = pd.DataFrame(halifaxTransactionsList[0])
df.to_csv('stfu.csv', header=False, sep=',', index=False)
print(df)
